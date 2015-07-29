<?php

// Проверка
if (!defined('BASE_DIR'))
	exit('Access denied');

/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         includes/error.php
 * @author       @
 * @copyright    2007-2015 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
*/


set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
{
	$error =
	sprintf('
		Lvl: <strong>%s</strong> | Message: <strong>%s</strong> | File: <strong>%s</strong> line: <strong>%s</strong>
	', $error_level, $error_message, $error_file, $error_line);

	switch ($error_level) {
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_PARSE:
			$color = '#f05050';
			errorLogs($error, "Fatal", $color);
			break;
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			$color = '#f05050';
			errorLogs($error, "Error", $color);
			break;
		case E_WARNING:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_USER_WARNING:
			$color = '#fad733';
			errorLogs($error, "Warning", $color);
			break;
		case E_NOTICE:
		case E_USER_NOTICE:
			$color = '#23b7e5';
			errorLogs($error, "Info", $color);
			break;
		case E_STRICT:
			$color = '#edf1f2';
			errorLogs($error, "Debug", $color);
			break;
		default:
			$color = '#fad733';
			errorLogs($error, "Warning", $color);
	}
}

function shutdownHandler()
{
	$lasterror = error_get_last();
	switch ($lasterror['type'])
	{
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_PARSE:
			$color = '#f05050';
			$error =
			sprintf('
				[SHUTDOWN] Lvl: <strong>%s</strong> | Message: <strong>%s</strong> | File: <strong>%s</strong> line: <strong>%s</strong>
			', $lasterror['type'], $lasterror['message'], $lasterror['file'], $lasterror['line']);
			errorLogs($error, "Fatal", $color);
	}
}

function errorLogs($error, $errlvl, $color)
{
	echo '<div style="background:'.$color.'; color: #000; margin:5px; padding:5px; font-size: 11px; font-family: Consolas, Verdana, Arial;"><strong>' . $errlvl . '</strong>' . $error . '</div>';
}
?>
