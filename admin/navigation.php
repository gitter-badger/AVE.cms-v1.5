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

require(BASE_DIR . '/class/class.navigation.php');
$AVE_Navigation = new AVE_Navigation;

$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/navigation.txt', 'navi');

switch ($_REQUEST['action'])
{
	case '':
		if (check_permission_acp('navigation_view'))
		{
			$AVE_Navigation->navigationList();
		}
		break;

	case 'new':
		if (check_permission_acp('navigation_edit'))
		{
			require(BASE_DIR . '/class/class.user.php');
			$AVE_User = new AVE_User;
			$AVE_Navigation->navigationNew();
		}
		break;

	case 'templates':
		if (check_permission_acp('navigation_edit'))
		{
			require(BASE_DIR . '/class/class.user.php');
			$AVE_User = new AVE_User;
			$AVE_Navigation->navigationEdit($_REQUEST['id']);
		}
		break;

	case 'copy':
		if (check_permission_acp('navigation_edit'))
		{
			$AVE_Navigation->navigationCopy($_REQUEST['id']);
		}
		break;

	case 'delete':
		if (check_permission_acp('navigation_edit'))
		{
			$AVE_Navigation->navigationDelete($_REQUEST['id']);
		}
		break;

	case 'entries':
		if (check_permission_acp('navigation_edit'))
		{
			$AVE_Navigation->navigationItemList($_REQUEST['id']);
		}
		break;

	case 'quicksave':
		if (check_permission_acp('navigation_edit'))
		{
			$AVE_Navigation->navigationItemEdit($_REQUEST['id']);
		}
		break;
}
?>