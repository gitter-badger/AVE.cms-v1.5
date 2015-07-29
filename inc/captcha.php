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

define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__FILE__))));

require(BASE_DIR . '/inc/config.php');

// Работа с сессиями
if (!SESSION_SAVE_HANDLER)
{
	// Класс для работы с сессиями
	require(BASE_DIR . '/class/class.session.files.php');
	$ses_class = new AVE_Session();
}
else
{
	// Класс для работы с сессиями
	require(BASE_DIR . '/class/class.session.php');
	$ses_class = new AVE_Session_DB();
}

/* Изменяем save_handler, используем функции класса */
session_set_save_handler (
	array(&$ses_class, '_open'),
	array(&$ses_class, '_close'),
	array(&$ses_class, '_read'),
	array(&$ses_class, '_write'),
	array(&$ses_class, '_destroy'),
	array(&$ses_class, '_gc')
);

/* Страт сессии */
session_name('avecms');
session_start();

unset($_SESSION['captcha_keystring']);

require(BASE_DIR . '/lib/kcaptcha/kcaptcha.php');

$captcha = new KCAPTCHA();

$_SESSION['captcha_keystring'] = $captcha->getKeyString();

?>