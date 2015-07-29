<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 * @license GPL v.2
 */

if (!defined('ACP'))
{
	header('Location:index.php');
	exit;
}

global $AVE_DB, $AVE_Template;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/templates.txt');

$formaction = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'new')
	? 'index.php?do=templates&action=new&sub=savenew'
	: 'index.php?do=templates&action=edit&sub=save';
$AVE_Template->assign('formaction', $formaction);

switch ($_REQUEST['action'])
{
	case'':
		if (check_permission_acp('template_view'))
		{
			
			// Сss файлы
			$dir = BASE_DIR.'/templates/' . DEFAULT_THEME_FOLDER . '/css/';
			if($handle = opendir($dir))
			{
				$css_files = Array();
				$ii = 0;
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && substr($file, -3) == 'css')
					{
					  if(!is_dir($dir  ."/" . $file))
						$files[$ii]['filename'] = $file;
						$files[$ii]['filesize'] = filesize($dir  ."/" . $file);
						$css_files[] = $files;
					}
					$ii = $ii++;
				}
				closedir($handle);
			}
			$AVE_Template->assign('css_files', $css_files);

			// Js файлы
			$dir = BASE_DIR.'/templates/'.DEFAULT_THEME_FOLDER.'/js/';
			if($handle = opendir($dir))
			{
				$js_files = Array();
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && substr($file,-2) == 'js')
					{
					  if(!is_dir($dir."/".$file))
						$files[$ii]['filename'] = $file;
						$files[$ii]['filesize'] = filesize($dir  ."/" . $file);
						$js_files[] = $files;
					}
				}
				closedir($handle);
			}
			$AVE_Template->assign('js_files', $js_files);

			$items   = array();
			$num_tpl = $AVE_DB->Query("
				SELECT COUNT(*)
				FROM " . PREFIX . "_templates
			")->GetCell();

			$page_limit = (isset($_REQUEST['set']) && is_numeric($_REQUEST['set'])) ? (int)$_REQUEST['set'] : 30;
			$pages      = ceil($num_tpl / $page_limit);
			$set_start  = get_current_page() * $page_limit - $page_limit;

			if ($num_tpl > $page_limit)
			{
				$page_nav = " <a class=\"pnav\" href=\"index.php?do=templates&page={s}&amp;cp=" . SESSION. "\">{t}</a> ";
				$page_nav = get_pagination($pages, 'page', $page_nav);
				$AVE_Template->assign('page_nav', $page_nav);
			}

			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_templates
				LIMIT " . $set_start . "," . $page_limit . "
			");

			while ($row = $sql->FetchRow())
			{
				$inuse = $AVE_DB->Query("
					SELECT 1
					FROM
						" . PREFIX . "_rubrics AS rubric,
						" . PREFIX . "_module AS module
					WHERE
						rubric.rubric_template_id = '" . $row->Id . "' OR
						module.ModuleTemplate = '" . $row->Id . "'
					LIMIT 1
				")->NumRows();

				if (!$inuse) $row->can_deleted = 1;

				$row->template_author = get_username_by_id($row->template_author_id);
				array_push($items, $row);
				unset($row);
			}

			$AVE_Template->assign('items', $items);
			$AVE_Template->assign('content', $AVE_Template->fetch('templates/templates.tpl'));
		}
		break;


	case 'new':
		if (check_permission_acp('template_edit'))
		{
			$_REQUEST['sub'] = (isset($_REQUEST['sub'])) ? $_REQUEST['sub'] : '';
			switch ($_REQUEST['sub'])
			{
				case '':
					$AVE_Template->assign('content', $AVE_Template->fetch('templates/form.tpl'));
					break;

				case 'savenew':
					$save = true;
					$errors = array();
					$row->template_text = pretty_chars($_REQUEST['template_text']);
					$row->template_text = stripslashes($row->template_text);
					$row->template_title = stripslashes($_REQUEST['template_title']);

					if (empty($_REQUEST['template_title']))
					{
						$save = false;
						$errors[] = $AVE_Template->get_config_vars('TEMPLATES_REPORT_ERROR_TITLE');
					}

					if (empty($_REQUEST['template_text']))
					{
						$save = false;
						$errors[] = $AVE_Template->get_config_vars('TEMPLATES_REPORT_ERROR_TEXT');
					}

					$check_code = strtolower($_REQUEST['template_text']);
					if (is_php_code($check_code) && !check_permission('template_php'))
					{
						$AVE_Template->assign('php_forbidden', 1);
						$save = false;
					}

					if ($save === false)
					{
						$AVE_Template->assign('row', $row);
						$AVE_Template->assign('errors', $errors);
						$AVE_Template->assign('content', $AVE_Template->fetch('templates/form.tpl'));
					}
					else
					{
						$sql = $AVE_DB->Query("
							INSERT
							INTO " . PREFIX . "_templates
							SET
								Id                 = '',
								template_title     = '" . $_REQUEST['template_title'] . "',
								template_text      = '" . pretty_chars($_REQUEST['template_text']) . "',
								template_author_id = '" . $_SESSION['user_id'] . "',
								template_created   = '" . time() . "'
						");
						$iid = $AVE_DB->InsertId();

						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_NEW') . '(' . stripslashes(htmlspecialchars($_REQUEST['template_text'], ENT_QUOTES)) . ') (Id:' . (int)$iid . ')');

						if (!$_REQUEST['next_edit']) {
							header('Location:index.php?do=templates&cp=' . SESSION);
						} else {
							header('Location:index.php?do=templates&action=edit&Id=' . (int)$template_new . '&cp=' . SESSION);
						}

						exit;

					}
					break;
			}
		}
		break;


	case 'delete' :
		if (check_permission_acp('template_edit'))
		{
			$Used = $AVE_DB->Query("
				SELECT rubric_template_id
				FROM " . PREFIX . "_rubrics
				WHERE rubric_template_id = '" . (int)$_REQUEST['Id'] . "'
			")->GetCell();

			if ($Used >= 1 || $_REQUEST['Id'] == 1)
			{
				reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_ID_ERR') . ' -  (' . templateName((int)$_REQUEST['Id']) . ')');

				header('Location:index.php?do=templates');
				exit;
			}
			else
			{
				$template_name = templateName((int)$_REQUEST['Id']);

				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_templates
					WHERE Id = '" . (int)$_REQUEST['Id'] . "'
				");
				$AVE_DB->Query("
					ALTER
					TABLE " . PREFIX . "_templates
					PACK_KEYS = 0
					CHECKSUM = 0
					DELAY_KEY_WRITE = 0
					AUTO_INCREMENT = 1
				");

				reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_DELETE') . ' -  (' . $template_name . ')');

				header('Location:index.php?do=templates');
				exit;
			}
		}
		break;

	case 'edit':
		if (check_permission_acp('template_edit'))
		{
			$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : $_REQUEST['sub'];
			switch ($_REQUEST['sub'])
			{
				case '':
					$row = $AVE_DB->Query("
						SELECT *
						FROM " . PREFIX . "_templates
						WHERE Id = '" . $_REQUEST['Id'] . "'
					")->FetchRow();

					$check_code = strtolower($row->template_text);
					if (is_php_code($check_code) && !check_permission('template_php'))
					{
						$AVE_Template->assign('php_forbidden', 1);
						$AVE_Template->assign('read_only', 'readonly');
					}

					$row->template_text = pretty_chars($row->template_text);
					$row->template_text = stripslashes($row->template_text);
					$AVE_Template->assign('row', $row);
					break;

				case 'save':
					$ok = true;
					$check_code = strtolower($_REQUEST['template_text']);
					if (is_php_code($check_code) && !check_permission('template_php') )
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP') . ' (' . stripslashes($_REQUEST['template_title']) . ')');
						$AVE_Template->assign('php_forbidden', 1);
						$ok = false;
						$message = $AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP_ERR');
						$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
						$theme = 'error';
					}

					if ($ok === false)
					{
						if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
							echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
							exit;
						} else {
							$row->template_text = stripslashes($_REQUEST['template_text']);
							$AVE_Template->assign('row', $row);
						}
					}
					else
					{

						$sql = $AVE_DB->Query("
							UPDATE " . PREFIX . "_templates
							SET
								template_title = '" . $_REQUEST['template_title'] . "',
								template_text  = '" . $_REQUEST['template_text'] . "'
							WHERE
								Id = '" . (int)$_REQUEST['Id'] . "'
						");

						if ($sql->_result === false) {
							$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED_ERR');
							$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
							$theme = 'error';
						}else{
							$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED');
							$header = $AVE_Template->get_config_vars('TEMPLATES_SUCCESS');
							$theme = 'accept';
							reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_CHANGE') . ' -  (' . stripslashes($_REQUEST['template_title']) . ')');
						}

						if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
							echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
						} else {
							$AVE_Template->assign('message', $message);
							header('Location:index.php?do=templates&cp=' . SESSION);
						}
						exit;

					}
					break;
			}
			$AVE_Template->assign('content', $AVE_Template->fetch('templates/form.tpl'));
		}
		break;

	case 'edit_css':
		if (check_permission_acp('template_edit'))
		{
			$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : $_REQUEST['sub'];
			switch ($_REQUEST['sub'])
			{

				case 'save':
					$dir = BASE_DIR.'/templates/'.DEFAULT_THEME_FOLDER.'/css/'.$_REQUEST['name_file'];
					//$_REQUEST['code_text'] = str_ireplace(array('<style>','</style>'), "", $_REQUEST['code_text']);

					$check_code = stripcslashes($_REQUEST['code_text']);

					if (is_php_code($check_code))
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP_CSS') . ' -  (' . stripslashes($_REQUEST['name_file']) . ')');

						$message = $AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP_ERR');
						$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
						$theme = 'error';

						if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
							echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
							exit;
						}
						
						header('Location:index.php?do=templates');
						exit;
					}

					$result = file_put_contents($dir, trim($check_code));

					if ($result === false) {
						$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED_ERR_FILE');
						$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
						$theme = 'error';
					}else{
						$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED_FILE');
						$header = $AVE_Template->get_config_vars('TEMPLATES_SUCCESS');
						$theme = 'accept';
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_FILE') . ' -  (' . stripslashes($dir) . ')');
					}

					if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
						echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
					} else {
						$AVE_Template->assign('message', $message);
						header('Location:index.php?do=templates&cp=' . SESSION);
					}

					exit;

				case 'delete':

					$file = BASE_DIR . '/templates/' . DEFAULT_THEME_FOLDER . '/css/'. $_REQUEST['name_file'];

					if (!is_file($file)) return false;

					if (@unlink($file))
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_DEL_OK') . ' ('.basename($_REQUEST['name_file']).')');
					}
					else
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_DEL_ER') . ' ('.basename($_REQUEST['name_file']).')');
					}

					header('Location:index.php?do=templates&cp=' . SESSION);
					exit;

				default:
					$dir = BASE_DIR.'/templates/'.DEFAULT_THEME_FOLDER.'/css/'.stripslashes($_REQUEST['name_file']);
					$code_text = file_get_contents($dir);
					$formaction = "index.php?do=templates&action=edit_css&sub=save&name_file=".stripslashes($_REQUEST['name_file']);
					$AVE_Template->assign('formaction', $formaction);
					$AVE_Template->assign('code_text', $code_text);
					break;
			}
			$AVE_Template->assign('content', $AVE_Template->fetch('templates/edit_css.tpl'));
		}
		break;

	case 'edit_js':
		if (check_permission_acp('template_edit'))
		{
			$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : $_REQUEST['sub'];
			switch ($_REQUEST['sub'])
			{
				case 'save':
					$dir = BASE_DIR.'/templates/'.DEFAULT_THEME_FOLDER.'/js/'.$_REQUEST['name_file'];
					//$_REQUEST['code_text'] = str_ireplace(array('<script>','</script>'), "", $_REQUEST['code_text']);

					$check_code = stripcslashes($_REQUEST['code_text']);

					if (is_php_code($check_code))
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP_JS') . ' -  (' . stripslashes($_REQUEST['name_file']) . ')');

						$message = $AVE_Template->get_config_vars('TEMPLATES_REPORT_PHP_ERR');
						$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
						$theme = 'error';

						if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
							echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
							exit;
						}

						header('Location:index.php?do=templates');
						exit;
					}

					$result = file_put_contents($dir, trim($check_code));

					if ($result === false) {
						$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED_ERR_FILE');
						$header = $AVE_Template->get_config_vars('TEMPLATES_ERROR');
						$theme = 'error';
					}else{
						$message = $AVE_Template->get_config_vars('TEMPLATES_SAVED_FILE');
						$header = $AVE_Template->get_config_vars('TEMPLATES_SUCCESS');
						$theme = 'accept';
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_FILE') . ' -  (' . stripslashes($dir) . ')');
					}

					if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
						echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
					} else {
						$AVE_Template->assign('message', $message);
						header('Location:index.php?do=templates&cp=' . SESSION);
					}
					exit;

				case 'delete':

					$file = BASE_DIR . '/templates/' . DEFAULT_THEME_FOLDER . '/js/'. $_REQUEST['name_file'];

					if (!is_file($file)) return false;

					if (@unlink($file))
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_DEL_OK') . ' ('.basename($_REQUEST['name_file']).')');
					}
					else
					{
						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_DEL_ER') . ' ('.basename($_REQUEST['name_file']).')');
					}

					header('Location:index.php?do=templates&cp=' . SESSION);
					exit;


				default:
					$dir = BASE_DIR.'/templates/'.DEFAULT_THEME_FOLDER.'/js/'.stripslashes($_REQUEST['name_file']);
					$code_text = file_get_contents($dir);
					$formaction = "index.php?do=templates&action=edit_js&sub=save&name_file=".stripslashes($_REQUEST['name_file']);
					$AVE_Template->assign('formaction', $formaction);
					$AVE_Template->assign('code_text', $code_text);
					break;
			}
			$AVE_Template->assign('content', $AVE_Template->fetch('templates/edit_js.tpl'));
		}
		break;

	case 'multi':
		if (check_permission_acp('template_edit'))
		{
			$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : $_REQUEST['sub'];
			$errors = array();
			switch ($_REQUEST['sub'])
			{
				case 'save':
					$ok = true;
					$row = $AVE_DB->Query("
						SELECT template_title
						FROM " . PREFIX . "_templates
						WHERE template_title = '" . $_REQUEST['template_title'] . "'
					")->FetchRow();

					if (@$row->template_title != '')
					{
						array_push($errors, $AVE_Template->get_config_vars('TEMPLATES_EXIST'));
						$AVE_Template->assign('errors', $errors);
						$ok = false;
					}

					if ($_REQUEST['template_title'] == '')
					{
						array_push($errors, $AVE_Template->get_config_vars('TEMPLATES_NO_NAME'));
						$AVE_Template->assign('errors', $errors);
						$ok = false;
					}

					if ($ok)
					{
						$row = $AVE_DB->Query("
							SELECT template_text
							FROM " . PREFIX . "_templates
							WHERE Id = '" . (int)$_REQUEST['Id'] . "'
						")->FetchRow();

						$AVE_DB->Query("
							INSERT
							INTO " . PREFIX . "_templates
							SET
								Id = '',
								template_title     = '" . $_REQUEST['template_title'] . "',
								template_text      = '" . addslashes($row->template_text) . "',
								template_author_id = '" . $_SESSION['user_id'] . "',
								template_created   = '" . time() . "'
						");

						reportLog($AVE_Template->get_config_vars('TEMPLATES_REPORT_COPY') . ' -  (<strong>' . $_REQUEST['template_title'].'</strong> - '.templateName((int)$_REQUEST['Id']) . ')');
						header('Location:index.php?do=templates'.'&cp=' . SESSION);
					}
					break;
			}
		}

		$AVE_Template->assign('content', $AVE_Template->fetch('templates/multi.tpl'));
		break;
}
?>