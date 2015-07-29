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

require(BASE_DIR . '/class/class.sysblocks.php');
$AVE_SysBlock = new AVE_SysBlock;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/sysblocks.txt', 'sysblocks');

switch ($_REQUEST['action'])
{
	case '':
		if (check_permission_acp('sysblocks_view'))
		{
			$AVE_SysBlock->sys_blockList();
		}
		break;

	case 'new':
		if (check_permission_acp('sysblocks_edit'))
		{
			$_SESSION['use_editor'] = get_settings('use_editor');
			$AVE_SysBlock->sys_blockNew();
		}
		break;

	case 'edit':
		if (check_permission_acp('sysblocks_edit'))
		{
			$_SESSION['use_editor'] = get_settings('use_editor');
			$AVE_SysBlock->sys_blockEdit(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

	case 'save':
		if (check_permission_acp('sysblocks_edit'))
		{
			$AVE_SysBlock->sys_blockSave(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

	case 'del':
		if (check_permission_acp('sysblocks_edit'))
		{
			$AVE_SysBlock->sys_blockDelete($_REQUEST['id']);
		}
		break;

	case 'multi':
		if (check_permission_acp('sysblocks_edit'))
		{
			$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : $_REQUEST['sub'];
			$errors = array();
			switch ($_REQUEST['sub'])
			{
				case 'save':
					$ok = true;
					$row = $AVE_DB->Query("
						SELECT sysblock_name
						FROM " . PREFIX . "_sysblocks
						WHERE sysblock_name = '" . $_REQUEST['sysblock_name'] . "'
					")->FetchRow();

					if (@$row->sysblock_name != '')
					{
						array_push($errors, $AVE_Template->get_config_vars('SYSBLOCK_EXIST'));
						$AVE_Template->assign('errors', $errors);
						$ok = false;
					}

					if ($_REQUEST['sysblock_name'] == '')
					{
						array_push($errors, $AVE_Template->get_config_vars('SYSBLOCK_COPY_TIP'));
						$AVE_Template->assign('errors', $errors);
						$ok = false;
					}

					if ($ok)
					{
						$row = $AVE_DB->Query("
							SELECT sysblock_text
							FROM " . PREFIX . "_sysblocks
							WHERE id = '" . (int)$_REQUEST['id'] . "'
						")->FetchRow();

						$AVE_DB->Query("
							INSERT
							INTO " . PREFIX . "_sysblocks
							SET
								Id = '',
								sysblock_name     = '" . $_REQUEST['sysblock_name'] . "',
								sysblock_text      = '" . addslashes($row->sysblock_text) . "',
								sysblock_author_id = '" . $_SESSION['user_id'] . "',
								sysblock_created   = '" . time() . "'
						");

						reportLog($_SESSION['user_name'] . ' - создал копию системного блока (' . (int)$_REQUEST['id'] . ')', 2, 2);

						header('Location:index.php?do=sysblocks'.'&cp=' . SESSION);
					}
					$AVE_Template->assign('content', $AVE_Template->fetch('sysblocks/multi.tpl'));
					break;
			}
		}
}
?>