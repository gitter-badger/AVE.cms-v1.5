<?php

/**
 * AVE.cms
 *
 * Класс предназначен для создания обертки над MySql запросами к БД.
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 * @license GPL v.2
 */

/***************************************************************************
 * Класс, предназначенный для работы с ошибками
 ***************************************************************************/
class AVE_DB_Exception extends Exception
{
	/**
	 * @param string $e
	 */
	function __construct($e)
	{
		parent::__construct($e);
	}
}

/***************************************************************************
 * Класс, предназначенный для работы с результатами выполнения MySQL-запроса
 ***************************************************************************/
class AVE_DB_Result
{

/******************
 *
 *	Свойства класса
 *
 ******************/

	/**
	 * Конечный результат выполнения запроса
	 *
	 * @var resource
	 */
	public $_result = null;

/************************
 *
 *	Внешние методы класса
 *
 ************************/

	/**
	 * Конструктор, возвращает объект с указателем на результат выполнения SQL-запроса
	 *
	 * @param $_result
	 *
	 * @internal param resource $result указателем на результат выполнения SQL-запроса
	 * @return \AVE_DB_Result object
	 */
	public function __construct($_result)
	{
		$this->_result = $_result;
	}

	/**
	 * Метод, предназначенный для обработки результата запроса.
	 * Возвращает как ассоциативный, так и численный массив.
	 *
	 * @return array
	 */
	public function FetchArray()
	{
		if(is_array($this->_result))
		{
			$a = current($this->_result);

			next($this->_result);

			$b = array();

			if(!is_array($a))
				return false;

			foreach($a as $k => $v) $b[] = $v;

			return array_merge($b, $a);
		}

		return @mysqli_fetch_array($this->_result);
	}

	/**
	 *  Метод, предназначенный для обработки результата запроса.
	 *  Возвращает только ассоциативный массив.
	 *
	 * @return array
	 */
	public function FetchAssocArray()
	{
		if(is_array($this->_result))
		{
			$a = current($this->_result);

			next($this->_result);

			return $a;
		}
		return @mysqli_fetch_assoc($this->_result);
	}

	/**
	 * Метод, предназначенный для обработки результата запроса, возвращая данные в виде объекта.
	 *
	 * @return object
	 */
	public function FetchRow()
	{
		if(is_array($this->_result)){

			$a = $this->FetchAssocArray();

			return array2object($a);
		}
		return @mysqli_fetch_object($this->_result);
	}

	/**
	 * Метод, предназначенный для возвращения данных результата запроса
	 *
	 * @return mixed
	 */
	public function GetCell()
	{
		if(is_array($this->_result)){

			$a = current($this->_result);

			if(is_array($a)){
				return current($a);
			}
			else
			{
				return false;
			}
		}

		if ($this->NumRows())
		{
			$a = @mysqli_fetch_row($this->_result);
			return $a[0];
		}

		return false;
	}

	/**
	 * Метод, предназначенный для перемещения внутреннего указателя в результате запроса
	 *
	 * @param int $id - номер ряда результатов запроса
	 * @return bool
	 */
	public function DataSeek($id = 0)
	{
		if(is_array($this->_result))
		{
			//не нашел как переместить указатель в массиве на конкретный
			reset($this->_result);

			for($x = 0; $x == $id; $x++)
				next($this->_result);

			return $id; //эээ а что вернуть то надо было?
		}
		return @mysqli_data_seek($this->_result, $id);
	}

	/**
	 * Метод, предназначенный для получения количества рядов результата запроса
	 *
	 * @return int
	 */
	public function NumRows()
	{
		if(is_array($this->_result))
		{
			return (int)count($this->_result);
		}

		return (int)@mysqli_num_rows($this->_result);
	}

	/**
	 * Метод, предназначенный для получения количества полей результата запроса
	 *
	 * @return int
	 */
	public function NumFields()
	{
		if(is_array($this->_result)){

			$a = current($this->_result);

			return count($a);
		}
		return (int)mysqli_num_fields($this->_result);
	}

	/**
	 * Метод, предназначенный для получения названия указанной колонки результата запроса
	 *
	 * @param int $i - индекс колонки
	 * @return string
	 */
	public function FieldName($i)
	{
		if(is_array($this->_result)){

			$a = current($this->_result);

			$b = array_keys($a);

			return($b[$i]);
		}
		@mysqli_field_seek($this->_result, $i);

		$field = @mysqli_fetch_field($this->_result);

		return $field->name;
	}

	/**
	 * Метод, предназначенный для освобождения памяти от результата запроса
	 *
	 * @return bool
	 */
	public function Close()
	{
		@mysqli_free_result($this->_result);
		unset($this);
		return true;
	}

	/**
	 * Возвращает объект результата _result.
	 *
	 * @internal param $void
	 * @return resource
	 */
	public function getResult()
	{
		return $this->_result;
	}

	/**
	 * Удаляем объект
	 */
	public function __destruct()
	{
		$this->Close();
	}

}


/**************************************************************
 *
 * Класс, предназначенный для работы непосредственно с MySQL БД
 *
 **************************************************************/
class AVE_DB
{

/**
 *	Свойства класса
 */

	/**
	 * Хост
	 *
	 * @var string
	 */
	protected $db_host;

	/**
	 * Имя пользователя
	 *
	 * @var string
	 */
	protected $db_user;

	/**
	 * Пароль
	 *
	 * @var string
	 */
	protected $db_pass;

	/**
	 * Номер порта
	 *
	 * @var int
	 */
	protected $db_port;

	/**
	 * Сокет
	 *
	 * @var int
	 */
	protected $db_socket;

	/**
	 * Имя текущей БД.
	 *
	 * @var string
	 */
	protected $db_name;

	/**
	 * Префикс БД.
	 *
	 * @var string
	 */
	protected $db_prefix;

	/**
	 * Стандартный объект соединения сервером MySQL.
	 *
	 * @var mysqli
	 */
	protected $mysqli;

	/**
	 * Список выполненных запросов
	 *
	 * @var array
	 */
	public $_query_list;

	/**
	 * Метки времени до и после выполнения SQL-запроса
	 *
	 * @var array
	 */
	public $_time_exec;

	/**
	 * Последний запрос SQL-запроса
	 *
	 * @var array
	 */
	public $_last_query;

	/**
	 * Конструктор
	 *
	 * @param $db
	 *
	 * @throws AVE_DB_Exception
	 * @return \AVE_DB AVE_DB - объект
	 */
	private function __construct($db)
	{
		$this->db_host 		= $db['dbhost'];
		$this->db_user 		= $db['dbuser'];
		$this->db_password 	= $db['dbpass'];
		$this->db_prefix	= $db['dbpref'];

		if(!isset($db['dbport']))
			$this->db_port = ini_get ('mysqli.default_port');
		else
			$this->db_port = (isset($db['dbport']) ? $db['dbport'] : null);

		if(!isset($db['dbsock']))
			$this->db_socket = ini_get ('mysqli.default_socket');
		else
			$this->db_port = (isset($db['dbsock']) ? $db['dbsock'] : null);

		$this->Connect();

		// Определяем профилирование
		if (defined('PROFILING') && PROFILING)
		{
			// mysqli_query($this->mysqli, "QUERY_CACHE_TYPE = OFF");
			// mysqli_query($this->mysqli, "FLUSH TABLES");
			if (mysqli_query($this->mysqli, "SET PROFILING_HISTORY_SIZE = 100"))
			{
				mysqli_query($this->mysqli,"SET PROFILING = 1");
			}
			else
			{
				define('SQL_PROFILING_DISABLE', 1);
			}
		}
	}

	/**
	 * Устанавливает соеденение с базой данных.
	 *
	 * @throws AVE_DB_Exception
	 * @internal param void
	 * @return void
	 */
	private function Connect()
	{
		if (!is_object($this->mysqli) || !$this->mysqli instanceof mysqli)
		{
			$this->mysqli = @new mysqli($this->db_host, $this->db_user, $this->db_password, null, $this->db_port, $this->db_socket);
			if ($this->mysqli->connect_error)
			{
				throw new AVE_DB_Exception(__METHOD__ . ': ' . $this->mysqli->connect_error);
			}
		}
	}


	/**
	 * Задает набор символов по умолчанию.
	 *
	 * @param string $charset
	 *
	 * @throws AVE_DB_Exception
	 * @return AVE_DB
	 */
	public function setCharset($charset)
	{
		if (!$this->mysqli->set_charset($charset))
		{
			throw new AVE_DB_Exception(__METHOD__ . ': ' . $this->mysqli->error);
		}

		return $this;
	}

	/**
	 * Устанавливает имя используемой СУБД.
	 *
	 * @param string $database_name - имя базы данных
	 * @throws AVE_DB_Exception
	 * @return AVE_DB
	 */
	public function setDatabaseName($database_name)
	{
		if (!$database_name)
		{
			throw new AVE_DB_Exception(__METHOD__ . ': Не указано имя базы данных');
		}

		$this->db_name = $database_name;

		if (!$this->mysqli->select_db($this->db_name))
		{
			throw new AVE_DB_Exception(__METHOD__ . ': ' . $this->mysqli->error);
		}

		return $this;
	}

	/**
	 * Создает инстанс данного класса.
	 *
	 * @uses $AVE_DB = AVE_DB::getInstance($server, $username, $password, $port, $socket);
	 * @param $db
	 * @return object возвращает инстанс данного класса.
	 */
	public static function getInstance($db = array())
	{
		return new self($db);
	}

	/**
	 * Возвращает префикс БД.
	 *
	 * @param void
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->db_prefix;
	}

	/**
	 * Возвращает кодировку по умолчанию, установленную для соединения с БД.
	 *
	 * @param void
	 * @return string
	 */
	public function getCharset()
	{
		return $this->mysqli->character_set_name();
	}

	/**
	 * Возвращает имя текущей БД.
	 *
	 * @param void
	 * @return string
	 */
	public function getDatabaseName()
	{
		return $this->db_name;
	}

	/**
	 * Получает количество рядов, задействованных в предыдущей MySQL-операции.
	 * Возвращает количество рядов, задействованных в последнем запросе INSERT, UPDATE или DELETE.
	 * Если последним запросом был DELETE без оператора WHERE,
	 * все записи таблицы будут удалены, но функция возвратит ноль.
	 *
	 * @see mysqli_affected_rows
	 * @param void
	 * @return int
	 */
	public function getAffectedRows()
	{
		return $this->mysqli->affected_rows;
	}

	/**
	 * Возвращает последний выполненный MySQL-запрос.
	 *
	 * @param void
	 * @return string
	 */
	public function getQueryString()
	{
		return $this->_last_query;
	}

	/**
	 * Возвращает массив со всеми исполненными SQL-запросами в рамках текущего объекта.
	 *
	 * @param void
	 * @return array
	 */
	public function getQueries()
	{
		return $this->_query_list;
	}

	/**
	 * Возвращает id, сгенерированный предыдущей операцией INSERT.
	 *
	 * @see mysqli_insert_id
	 * @param void
	 * @return int
	 */
	public function getLastInsertId()
	{
		return $this->mysqli->insert_id;
	}


	/**
	 * Метод, предназначенный для получения функции из которой пришел запрос с ошибкой
	 *
	 * @return string
	 */
	public function getCaller()
	{
		if (! function_exists('debug_backtrace')) return '';

		$stack = debug_backtrace();
		$stack = array_reverse($stack);

		$caller = array();

		foreach ((array)$stack as $call)
		{
			if (@$call['class'] == __CLASS__) continue;

			$function = $call['function'];

			if (isset($call['class']))
			{
				$function = $call['class'] . "->$function";
			}
			$caller[] =
				(array (
					'call_file' => (isset($call['file']) ? $call['file'] : 'Unknown'),
					'call_func' => $function,
					'call_line' => (isset($call['line']) ? $call['line'] : 'Unknown')
				));
		}

		return $caller;
	}



/************************* Внешние методы класса *************************/

	/**
	 * Метод, предназначенный для выполнения запроса к MySQL
	 *
	 * @param string $query - текст SQL-запроса
	 * @param bool $log - записать ошибки в лог? по умолчанию включено
	 * @return object/bool - объект с указателем на результат выполнения запроса
	 */
	public function Real_Query($query, $log = true)
	{
		//$this->_time_exec[] = microtime();
		$result = @mysqli_query($this->mysqli, $query);
		//$this->_time_exec[] = microtime();

		$this->_last_query = $query;

		$this->_query_list[] = $query;

		if (!$result && $log) $this->_error('query', $query);

		if (is_object($result) && $result instanceof mysqli_result)
		{
			return new AVE_DB_Result($result);
		}

		return $result;
	}

	/**
	 * Метод, предназначенный для выполнения запроса к MySQL и возвращение результата в виде асоциативного массива с поддержкой кеша
	 *
	 * @param string $query		- текст SQL-запроса
	 * @param integer $TTL		- время жизни кеша (-1 безусловный кеш)
	 * @param string $cache_id  - Id файла кеша
	 * @param bool $log			- записать ошибки в лог? по умолчанию включено
	 * @return array			- асоциативный массив с результом запроса
	 */
	public function Query($query, $TTL = null, $cache_id = '', $log = true)
	{
		if(substr($cache_id, 0, 3) == 'doc')
		{
			$cache_id = (int)str_replace('doc_', '', $cache_id);
			$cache_id = 'doc/' . (floor($cache_id / 1000)) . '/' . $cache_id;
		}

		//$query = filter_var($query, FILTER_SANITIZE_STRING);

		$result = array();

		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
/*
		// Не знаю кто поставил эту заглушку, но я выкл ее
		if (defined('ACP')) $TTL = null;
*/
		if($TTL && $TTL != "nocache")
		{
			$cache_file = md5($query);

			$cache_dir = BASE_DIR . '/cache/sql/' . (trim($cache_id) > '' ? trim($cache_id) . '/' : substr($cache_file, 0, 2) . '/' . substr($cache_file, 2, 2) . '/' . substr($cache_file, 4, 2) . '/');

			if(! file_exists($cache_dir))
				mkdir($cache_dir, 0777, true);

			if(! (file_exists($cache_dir . $cache_file) && ($TTL == -1 ? true : @time() - @filemtime($cache_dir . $cache_file) < $TTL)))
			{
				$res = $this->Real_Query($query, $log);

				while ($mfa = $res->FetchAssocArray())
					$result[] = $mfa;

				file_put_contents($cache_dir . $cache_file, serialize($result));
			}
			else
			{
				$result = unserialize(file_get_contents($cache_dir . $cache_file));
			}

			return new AVE_DB_Result($result);
		}

		else
			return $this->Real_Query($query, $log);
	}

	/**
	 * This method is needed for prepared statements. They require
	 * the data type of the field to be bound with "i" s", etc.
	 * This function takes the input, determines what type it is,
	 * and then updates the param_type.
	 *
	 * @param mixed $item Input to determine the type.
	 *
	 * @return string The joined parameter types.
	 */
	protected function DetermineType($item)
	{
		switch (gettype($item))
		{
			case 'NULL':
			case 'string':
				return 's';
				break;

			case 'boolean':
			case 'integer':
				return 'i';
				break;

			case 'blob':
				return 'b';
				break;

			case 'double':
				return 'd';
				break;
		}
		return '';
	}

	/**
	 * Метод, предназначенный для экранирования специальных символов в строках для использования в выражениях SQL
	 *
	 * @param mixed $value - обрабатываемое значение
	 * @return mixed
	 */
	public function Escape($value)
	{
		if (! is_numeric($value))
		{
			$value = mysqli_real_escape_string($this->mysqli, $value);
		}

		return $value;
	}

	/**
	 * Метод, предназначенный для экранирования специальных символов в строках для использования в выражениях SQL
	 *
	 * @param  mixed $value - обрабатываемое значение
	 * @return mixed - возвращает строку запроса вычещенной
	 */
	public function EscStr($value)
	{
		$value = htmlspecialchars($value);

		$value = strtr($value, array(
			'{' 		=> '&#123;',
			'}' 		=> '&#125;',
			'$' 		=> '&#36;',
			'&amp;gt;' 	=> '&gt;',
			"'"			=> "&#39;"
		));

		if( !is_array( $value ) )
		{
			$value = $this->mysqli->real_escape_string( $value );
		}
		else
		{
			$value = array_map( array( $this, 'escape' ), $value );
		}

		return $value;
	}

	/**
	 * Метод, предназначенный для возвращения ID записи, сгенерированной при последнем INSERT-запросе
	 *
	 * @return int
	 */
	public function InsertId()
	{
		return (int)mysqli_insert_id($this->mysqli);
	}

	/**
	 * Метод, предназначенный для возвращения количества всех найденных записей (после запроса)
	 *
	 * @return int
	 */
	public function GetFoundRows()
	{
		$result = $this->Query('SELECT FOUND_ROWS();');
		$strRow = $result->FetchArray();
		return (int)$strRow[0];
	}

	/**
	 * Метод, предназначенный для возвращения количества всех найденных записей (после запроса типа "SELECT SQL_CALC_FOUND_ROWS * ...")
	 *
	 * @param $query
	 * @param null $TTL
	 * @param string $cache_id
	 * @return int
	 */
	public function NumAllRows($query, $TTL = null, $cache_id = '')
	{
		if($TTL)
		{
			$cache_file = md5($query).'.count';

			$cache_dir = BASE_DIR.'/cache/sql/'.(trim($cache_id) > '' ? trim($cache_id).'/' : substr($cache_file, 0, 2).'/'.substr($cache_file, 2, 2).'/'.substr($cache_file, 4, 2).'/');

			if(!file_exists($cache_dir)) mkdir($cache_dir, 0777, true);

			if(!(file_exists($cache_dir.$cache_file) && ($TTL==-1 ? true : @time()-@filemtime($cache_dir.$cache_file) < $TTL)))
			{
				if($query <> $this->_last_query)
				{
					$res = $this->Real_Query($query);
				}
				else
				{
					$res = (int)$this->Query("SELECT FOUND_ROWS()")->GetCell();
					file_put_contents($cache_dir . $cache_file, $res);
				}

				return $res;
			}
			else
			{
				return file_get_contents($cache_dir . $cache_file);
			}
		}
		return (int)$this->Query("SELECT FOUND_ROWS()")->GetCell();
	}

	/**
	 * Метод, предназначенный для формирования статистики выполнения SQL-запросов.
	 *
	 * @param string $type - тип запрашиваемой статистики
	 * <pre>
	 * Возможные значения:
	 *     list  - список выполненых зпаросов
	 *     time  - время исполнения зпросов
	 *     count - количество выполненных запросов
	 * </pre>
	 * @return mixed
	 */
	public function DBStatisticGet($type = '')
	{
		switch ($type)
		{
			case 'list':
				list($s_dec, $s_sec) = explode(' ', $GLOBALS['start_time']);
				$query_list = '';
				$nq = 0;
				//$time_exec = 0;
				$arr = $this->_time_exec;
				$co = sizeof($arr);
				for ($it=0;$it<$co;)
				{
					list($a_dec, $a_sec) = explode(' ', $arr[$it++]);
					list($b_dec, $b_sec) = explode(' ', $arr[$it++]);
					$time_main = ($a_sec - $s_sec + $a_dec - $s_dec)*1000;
					$time_exec = ($b_sec - $a_sec + $b_dec - $a_dec)*1000;
					$query = sizeof(array_keys($this->_query_list, $this->_query_list[$nq])) > 1
						? "<span style=\"background-color:#ff9;\">" . $this->_query_list[$nq++] . "</span>"
						: $this->_query_list[$nq++];
					$query_list .= (($time_exec > 1) ? "<li style=\"color:#c00\">(" : "<li>(")
						. round($time_main) . " ms) " . $time_exec . " ms " . $query . "</li>\n";
				}

				return $query_list;
				break;

			case 'time':
				$arr = $this->_time_exec;
				$time_exec = 0;
				$co = sizeof($arr);
				for ($it=0; $it < $co;) {
					list($a_dec, $a_sec) = explode(" ", $arr[$it++]);
					list($b_dec, $b_sec) = explode(" ", $arr[$it++]);
					$time_exec += $b_sec - $a_sec + $b_dec - $a_dec;
				}

				return $time_exec;
				break;

			case 'count':
				return sizeof($this->_query_list);
				break;

			default:
				return '';
				break;
		}
	}

	/**
	 * Метод, предназначенный для формирования статистики выполнения SQL-запросов.
	 *
	 * @param string $type - тип запрашиваемой статистики
	 * <pre>
	 * Возможные значения:
	 *     list  - список выполненых зпаросов
	 *     time  - время исполнения зпросов
	 *     count - количество выполненных запросов
	 * </pre>
	 * @return mixed
	 */
	public function DBProfilesGet($type = '')
	{
		static $result, $list, $time, $count;

		if (!(defined('PROFILING') && PROFILING) || defined('SQL_PROFILING_DISABLE')) return false;

		if (!$result)
		{
			$list = "<table width=\"100%\" style=\"color:#000; font-size: 11px; font-family: Consolas, Verdana, Arial;\">"
				. "\n\t<col width=\"20\">\n\t<col width=\"70\">";

			$result = mysqli_query($this->mysqli, "SHOW PROFILES");

			while (list($qid, $qtime, $qstring) = @mysqli_fetch_row($result))
			{
				$time += $qtime;

				$list .= "\n\t<tr style=\"background:#eee; margin:5px; padding:5px; min-width:600px;\">\n\t\t<td><strong>"
					. $qid
					. "</strong></td>\n\t\t<td><strong>"
					. number_format($qtime * 1, 6, ',', '')
					. "</strong></td>\n\t\t<td><strong>"
					. $qstring
					. "</strong></td>\n\t</tr>";

				$res = mysqli_query($this->mysqli, "
					SELECT STATE, FORMAT(DURATION, 6) AS DURATION
					FROM INFORMATION_SCHEMA.PROFILING
					WHERE QUERY_ID = " . $qid
				);

				while (list($state, $duration) = @mysqli_fetch_row($res))
				{
					$list .= "\n\t<tr>\n\t\t<td>&nbsp;</td><td>"
					. number_format($duration * 1, 6, ',', '')
					. "</td>\n\t\t<td>" . $state . "</td>\n\t</tr>";
				}
			}

			$time = number_format($time * 1, 6, ',', '');
			$list .= "\n</table>";
			$count = @mysqli_num_rows($result);
		}

		switch ($type)
		{
			case 'list':  return $list;  break;
			case 'time':  return $time;  break;
			case 'count': return $count; break;
		}

		return false;
	}

	/**
	 * Закрывает MySQL-соединение.
	 *
	 * @param void
	 * @return AVE_DB
	 */
	private function Close()
	{
		if (is_object($this->mysqli) && $this->mysqli instanceof mysqli)
		{
			@$this->mysqli->close();
		}

		return $this;
	}

	/**
	 * Метод, предназначенный для обработки ошибок
	 *
	 * @param string $type - тип ошибки (при подключении к БД или при выполнении SQL-запроса)
	 * @param string $query - текст SQL запроса вызвавшего ошибку
	 * @access private
	 */
	public function _error($type, $query = '')
	{
		if ($type != 'query')
		{
			display_notice('Error ' . $type . ' MySQL database.');
		}
		else
		{
			$my_error = mysqli_error($this->mysqli);

			$log = array(
				'sql_error' => $my_error,
				'sql_query' => htmlentities(stripslashes($query), ENT_QUOTES),
				'caller' => $this->getCaller(),
				'url' => HOST . $_SERVER['SCRIPT_NAME']. '?' . $_SERVER['QUERY_STRING']
			);

			reportSqlLog($log);

			// Если в настройках системы установлен параметр на отправку сообщений на e-mail, тогда
			if (SEND_SQL_ERROR)
			{
				// Формируем текст сообщения с ошибкой
				$mail_body = ('SQL ERROR: ' . $my_error . PHP_EOL
					. 'TIME: '  . date('d-m-Y, H:i:s') . PHP_EOL
					. 'URL: '   . HOST . $_SERVER['SCRIPT_NAME']
					. '?' . $_SERVER['QUERY_STRING'] . PHP_EOL
					. $this->getCaller() . PHP_EOL
					. 'QUERY: ' . stripslashes($query) . PHP_EOL
				);

				// Отправляем сообщение
				send_mail(
					get_settings('mail_from'),
					$mail_body,
					'MySQL Error!',
					get_settings('mail_from'),
					get_settings('mail_from_name'),
					'text'
				);
			}
		}
	}

	/**
	 * Удаляем объект
	 *
	 * @param void
	 */
	public function __destruct()
	{
		$this->Close();
	}

	/**
	 * Метод, предназначенный для получения информации о сервере MySQL
	 *
	 * @param void
	 * @return string
	 */
	public function mysql_version()
	{
		return @mysqli_get_server_info($this->mysqli);
	}

	/**
	 * Метод, предназначенный для очищения кеша документов
	 *
	 * @param $cache_id
	 * @return bool
	 */
	public function clearcache($cache_id){
		$cache_id = (substr($cache_id, 0, 3) == 'doc' ? 'doc/' . intval(floor((int)substr($cache_id, 4)) / 1000) . '/' . (int)substr($cache_id, 4) : $cache_id);
		$cache_dir = BASE_DIR . '/cache/sql/' . (trim($cache_id) > '' ? trim($cache_id) . '/' : '');
		return rrmdir($cache_dir);
	}

	/**
	 * Метод, предназначенный для очищения кеша запросов
	 *
	 * @param $cache_id
	 * @return bool
	 */
	public function clearcacherequest($cache_id){
		$cache_id = (substr($cache_id, 0, 3) == 'doc' ? 'request/' . (int)substr($cache_id, 4) : $cache_id);
		$cache_dir = BASE_DIR . '/cache/sql/' . (trim($cache_id) > '' ? trim($cache_id) . '/' : '');
		return rrmdir($cache_dir);
	}

} // End AVE_DB class
?>
