<?php

// Проверка
if (! defined('BASE_DIR'))
	exit('Access denied');

/**
 * This source file is part of the AVE.cms. More information,
 * documentation and tutorials can be found at http://www.ave-cms.ru
 *
 * @package      AVE.cms
 * @file         system/helpers/debug.php
 * @author       @
 * @copyright    2007-2015 (c) AVE.cms
 * @link         http://www.ave-cms.ru
 * @version      4.0
 * @since        $date$
 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
*/

class Debug {

	protected static $time = array();

	protected static $memory = array();


	public function __construct()
	{
		//
	}

	/**
	 * Функция для вывода переменной (для отладки)
	 *
	 * @param mixed $var любая переменная
	 */
	public static function _echo($var)
	{
		$backtrace = debug_backtrace();

		$backtrace = $backtrace[0];

		$fh = fopen($backtrace['file'], 'r');

		$line = 0;

		while (++$line <= $backtrace['line'])
		{
			$code = fgets($fh);
		}

		fclose($fh);

		preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

		ob_start();

		var_dump($var);

		$var_dump = htmlspecialchars(ob_get_contents());

		ob_end_clean();

		$var_dump = '
			<div style="border: 1px solid #bbb; margin: 5px; font-size: 11px; font-family: Consolas, Verdana, Arial;">
				<div style="background:#ccc; color: #000; margin: 0; padding: 5px;">
					var_dump(<strong>' . trim($name[1]) . '</strong>) - ' . self::_trace() .
				'</div>
				<pre style="background:#f0f0f0; color: #000; margin: 0; padding: 5px;">'
				. $var_dump .
				'</pre>
			</div>
		';

		echo $var_dump;
	}


	/**
	 * Функция для вывода переменной (для экспорта)
	 *
	 * @param mixed $var любая переменная
	 */
	public static function _exp($var)
	{
		$backtrace = debug_backtrace();

		$backtrace = $backtrace[0];

		$fh = fopen($backtrace['file'], 'r');

		$line = 0;

		while (++$line <= $backtrace['line'])
		{
			$code = fgets($fh);
		}

		fclose($fh);

		preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

		ob_start();

		var_export($var);


		$var_export = ob_get_contents();

		ob_end_clean();

		$var_dump = '
			<div style="border: 1px solid #bbb; margin: 5px; font-size: 11px; font-family: Consolas, Verdana, Arial;">
			<div style="background:#ccc; color: #000; margin: 0; padding: 5px;">var_export(<strong>'
			. trim($name[1]) . '</strong>) - ' . self::_trace() .
			'</div>
			<pre style="background:#f0f0f0; color: #000; margin: 0; padding: 5px;">'
			. $var_export .
			'</pre>
			</div>
		';

		echo $var_dump;
	}


	/**
	 * Функция для вывода переменной (для отладки)
	 *
	 * @param mixed $var любая переменная
	 * @param bool $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
	 */
	public static function _html($var, $exit=false)
	{
		ob_start();

		var_dump($var);

		$var_dump = ob_get_contents();

		ob_end_clean();

		$var_dump = '<pre style="background:#eee; color:#000; margin:5px; padding:5px; font-size: 11px; font-family: Consolas, Verdana, Arial;">' . htmlentities($var_dump, ENT_QUOTES) . '</pre>';

		echo $var_dump;

		if ($exit) exit;
	}


	/**
	 * Функция для записи переменной в файл (для отладки)
	 *
	 * @param mixed $var любая переменная
	 * @param bool $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
	 */
	public static function _dump($var, $exit=false)
	{
		ob_start();

		var_dump($var);

		$var_dump = htmlspecialchars(ob_get_contents());

		ob_end_clean();

		$var_dump = '<pre style="background:#eee; color:#000; margin:5px; padding:5px; font-size: 11px; font-family: Consolas, Verdana, Arial;">' . $var_dump . '</pre>';

		$var_dump = '<h4 style="color:#000; margin:5px; padding:5px; min-width:600px; font-size: 13px; font-family: Consolas, Verdana, Arial;">' . date("j F Y, H:i:s") . '</h4>' . $var_dump;

		file_put_contents(BASE_DIR . '/debug.html', $var_dump, FILE_APPEND);

		if ($exit) exit;
	}


	/**
	 * Функция для трейсинга дебаггера
	 *
	 * @param
	 * @return string
	 */
	public static function _trace()
	{
		$bt = debug_backtrace();

		$trace = $bt[1];

		$line = $trace['line'];

		$file = $trace['file'];

		$function = $trace['function'];

		$class = (isset($bt[2]['class'])
			? $bt[2]['class']
			: 'None');

		if (isset($bt[2]['class']))
		{
			$type = $bt[2]['type'];
		}
		else
		{
			$type = 'Unknow';
		}

		$function = isset($bt[2]['function'])
			? $bt[2]['function']
			: 'None';

		return sprintf('Class: <strong>%s</strong> | Type: <strong>%s</strong> | Function: <strong>%s</strong> | File: <strong>%s</strong> line <strong>%s</strong>', $class, $type, $function, $file, $line);
	}

	/**
	 * Функция отвечает за начало таймера
	 *
	 * @param string $name любая переменная (ключ массива)
	 */
	public static function startTime($name = '')
	{
		Debug::$time[$name] = microtime(true);
	}

	/**
	 * Функция отвечает за окончание таймера
	 *
	 * @param string $name любая переменная (ключ массива)
	 * @return
	 */
	public static function endTime($name = '')
	{
		if (isset(Debug::$time[$name]))
			return sprintf("%01.4f", microtime(true) - Debug::$time[$name]);
	}

	/**
	 * Функция отвечает за начало подсчета используеой памяти
	 *
	 * @param string $name любая переменная (ключ массива)
	 */
	public static function startMemory($name = '')
	{
		Debug::$memory[$name] = memory_get_usage();
	}

	/**
	 * Функция отвечает за окончание подсчета используемой памяти
	 *
	 * @param string $name любая переменная (ключ массива)
	 * @return
	 */
	public static function endMemory($name = '')
	{
		if (isset(Debug::$memory[$name]))
			return Debug::numFormat((memory_get_usage() - Debug::$memory[$name]) / 1024, 0, ',', '.') . ' Kb';
	}

	/**
	 * Форматированный вывод размера
	 *
	 * @param int $size размер
	 * @return string нормированный размер с единицой измерения
	 */
	public static function formatSize($size)
	{
		if ($size >= 1073741824)
		{
			$size = round($size / 1073741824 * 100) / 100 . ' Gb';
		}
		elseif ($size >= 1048576)
		{
			$size = round($size / 1048576 * 100) / 100 . ' Mb';
		}
		elseif ($size >= 1024)
		{
			$size = round($size / 1024 * 100) / 100 . ' Kb';
		}
		else
		{
			$size = $size . ' b';
		}

		return $size;
	}


	/**
	 * Форматированный вывод чисел
	 *
	 * @param int $number число
	 * @param int $decimal
	 * @param string $after
	 * @param string $thousand
	 * @return string
	 */
	public static function numFormat($number, $decimal = 0, $after = ',', $thousand= '.')
	{
		if ($number)
			return number_format($number, $decimal, $after, $thousand);

		return '';
	}
}
?>
