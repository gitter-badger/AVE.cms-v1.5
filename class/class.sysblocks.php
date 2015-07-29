<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 */

class AVE_SysBlock
{

	/**
	 * Вывод списка системных блоков
	 */
	function sys_blockList()
	{
		global $AVE_DB, $AVE_Template;

		$sys_blocks = array();

		$sql = $AVE_DB->Query("
			SELECT * FROM " . PREFIX . "_sysblocks
			ORDER BY id
		");

		// Формируем массив из полученных данных
		while ($row = $sql->FetchRow())
		{
			$row->sysblock_author_id = get_username_by_id($row->sysblock_author_id);
			array_push($sys_blocks, $row);
		}

		$AVE_Template->assign('sys_blocks', $sys_blocks);
		$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/list.tpl'));
	}

	/**
	 * Сохранение системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 */
	function sys_blockSave($sysblock_id = null)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($sysblock_id))
		{

			$_REQUEST['sysblock_external'] = (isset($_REQUEST['sysblock_external'])) ? $_REQUEST['sysblock_external'] : 0;
			$_REQUEST['sysblock_ajax'] = (isset($_REQUEST['sysblock_ajax'])) ? $_REQUEST['sysblock_ajax'] : 0;
			$_REQUEST['sysblock_visual'] = (isset($_REQUEST['sysblock_visual'])) ? $_REQUEST['sysblock_visual'] : 0;

			$sql = $AVE_DB->Query("
				UPDATE " . PREFIX . "_sysblocks
				SET
					sysblock_name = '" . $_REQUEST['sysblock_name'] . "',
					sysblock_text = '" . $_REQUEST['sysblock_text'] . "',
					sysblock_external = '" . (int)$_REQUEST['sysblock_external'] . "',
					sysblock_ajax = '" . (int)$_REQUEST['sysblock_ajax'] . "',
					sysblock_visual = '" . (int)$_REQUEST['sysblock_visual'] . "'
				WHERE
					id = '" . $sysblock_id . "'
			");

			if ($sql->_result === false) {
				$message = $AVE_Template->get_config_vars('SYSBLOCK_SAVED_ERR');
				$header = $AVE_Template->get_config_vars('SYSBLOCK_ERROR');
				$theme = 'error';
			}else{
				$message = $AVE_Template->get_config_vars('SYSBLOCK_SAVED');
				$header = $AVE_Template->get_config_vars('SYSBLOCK_SUCCESS');
				$theme = 'accept';
				@unlink(BASE_DIR.'/cache/sql/sysblock-'.$sysblock_id.'.cache');
				reportLog($AVE_Template->get_config_vars('SYSBLOCK_SQLUPDATE') . " (" . stripslashes($_REQUEST['sysblock_name']) . ") (id: $sysblock_id)");
			}

			if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
				echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
			} else {
				$AVE_Template->assign('message', $message);
				header('Location:index.php?do=sysblocks&cp=' . SESSION);
			}
			exit;
		}
		else
		{
			$AVE_DB->Query("
				INSERT INTO " . PREFIX . "_sysblocks
				SET
					sysblock_name			= '" . $_REQUEST['sysblock_name'] . "',
					sysblock_text			= '" . $_REQUEST['sysblock_text'] . "',
					sysblock_author_id	= '" . (int)$_SESSION['user_id'] . "',
					sysblock_external		= '" . (int)$_REQUEST['sysblock_external'] . "',
					sysblock_ajax		= '" . (int)$_REQUEST['sysblock_ajax'] . "',
					sysblock_visual		= '" . (int)$_REQUEST['sysblock_visual'] . "',
					sysblock_created		= '" . time() . "'
			");

			$sysblock_id = $AVE_DB->InsertId();

			// Сохраняем системное сообщение в журнал
			reportLog($AVE_Template->get_config_vars('SYSBLOCK_SQLNEW') . " (" . stripslashes($_REQUEST['sysblock_name']) . ") (id: $sysblock_id)");
		}
		if (!isset($_REQUEST['next_edit']))
			header('Location:index.php?do=sysblocks&cp=' . SESSION);
		else
			header('Location:index.php?do=sysblocks&action=edit&&id='.$sysblock_id.'&cp='. SESSION);
	}

	/**
	 * Редактирование системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 */
	function sys_blockEdit($sysblock_id)
	{
		global $AVE_DB, $AVE_Template;

		$row = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_sysblocks
			WHERE id = '" . $sysblock_id . "'
		")->FetchAssocArray();

		if ((isset($_REQUEST['sysblock_visual']) && $_REQUEST['sysblock_visual'] == 1) ||  $row['sysblock_visual'] == 1)
		{
			switch ($_SESSION['use_editor']) {
				case '0': // CKEditor
					$oCKeditor = new CKeditor(); 
					$oCKeditor->returnOutput = true;
					$oCKeditor->config['customConfig'] = 'code.js';
					$oCKeditor->config['toolbar'] = 'Big';
					$oCKeditor->config['height'] = 400;
					$config = array();
					$row['sysblock_text'] = $oCKeditor->editor('sysblock_text', $row['sysblock_text'], $config);
					break;

				case '1': // Elrte и Elfinder 
					break;

				case '2': // Innova
					require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_set_sys.php");
					$row['sysblock_text']   ='<textarea style="width: 98%; height: 500px;" name="sysblock_text" Id="sysblock_text">' . $row['sysblock_text'] . '</textarea>';
					$row['sysblock_text']  .= $innova[1];
					break;
			}
			$AVE_Template->assign($row);
			$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form_visual.tpl'));
		}
		else
		{
			$AVE_Template->assign($row);
			$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form.tpl'));
		}
	}

	/**
	 * Создание системного блока
	 */
	function sys_blockNew()
	{
		global $AVE_DB, $AVE_Template;

		$row['sysblock_name'] = '';
		$row['sysblock_text'] = '';
		$row['sysblock_visual'] = (isset($_REQUEST['sysblock_visual']) && $_REQUEST['sysblock_visual'] != 0) ? $_REQUEST['sysblock_visual'] : '';

		if ((isset($_REQUEST['sysblock_visual']) && $_REQUEST['sysblock_visual'] == 1) ||  $row['sysblock_visual'] == 1)
		{
			switch ($_SESSION['use_editor']) {
				case '0': // CKEditor
					$oCKeditor = new CKeditor(); 
					$oCKeditor->returnOutput = true;
					$oCKeditor->config['customConfig'] = 'code.js';
					$oCKeditor->config['toolbar'] = 'Big';
					$oCKeditor->config['height'] = 400;
					$config = array();
					$row['sysblock_text'] = $oCKeditor->editor('sysblock_text', $row['sysblock_text'], $config);
					break;

				case '1': // Elrte и Elfinder 
					break;

				case '2': // Innova
					require(BASE_DIR . "/admin/templates/liveeditor/f_config/li_set_sys.php");
					$row['sysblock_text']   ='<textarea style="width: 98%; height: 500px;" name="sysblock_text" Id="sysblock_text">' . $row['sysblock_text'] . '</textarea>';
					$row['sysblock_text']  .= $innova[1];
					break;
			}
			$AVE_Template->assign($row);
			$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form_visual.tpl'));
		}
		else
		{
			$AVE_Template->assign($row);
			$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/form.tpl'));
		}
	}

	/**
	 * Удаление системного блока
	 *
	 * @param int $sysblock_id идентификатор системного блока
	 */
	function sys_blockDelete($sysblock_id)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($sysblock_id))
		{
			$row = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $sysblock_id . "'
			")->FetchRow();
			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $sysblock_id . "'
			");

			@unlink(BASE_DIR.'/cache/sql/sysblock-'.$sysblock_id.'.cache');
			reportLog($AVE_Template->get_config_vars('SYSBLOCK_SQLDEL') . " (" . stripslashes($row->sysblock_name) . ") (id: $sysblock_id)");
		}
		header('Location:index.php?do=sysblocks&cp=' . SESSION);
	}
}
?>