<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 * @author Aleksandr Salnikov (Repellent) webstudio3v.ru
 * @license GPL v.2
 */

if (!defined('ACP'))
{
	header('Location:index.php');
	exit;
}

global $AVE_DB, $AVE_Template;

require(BASE_DIR . '/class/class.liveeditor.php');
$AVE_LiveEditor = new AVE_LiveEditor;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/liveeditor.txt', 'liveeditor');

switch ($_REQUEST['action'])
{
	case '':
		if (check_permission_acp('ledit_liveeditor'))
		{
			$AVE_LiveEditor->live_editorList();
		}
		break;

	case 'edit':
		if (check_permission_acp('ledit_liveeditor'))
		{
			$AVE_LiveEditor->live_editorEdit(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

	case 'save':
		if (check_permission_acp('ledit_liveeditor'))
		{
			$AVE_LiveEditor->live_editorSave(isset($_REQUEST['id']) ? $_REQUEST['id'] : null);
		}
		break;

    case 'reg':
		if (check_permission_acp('ledit_liveeditor'))
		{
			$AVE_LiveEditor->live_editorReg();
		}
		break;
}
?>