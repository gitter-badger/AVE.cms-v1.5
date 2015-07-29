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

/**
 * Обработка условий запроса.
 * Возвращает строку условий в SQL-формате
 *
 * @param int $id	идентификатор запроса
 * @return string
 */
function request_get_condition_sql_string($id, $update_db=false)
{
	global $AVE_DB, $AVE_Core;

	$id = (int)$id;
	$from = array();
	$where = array();

	$sql_ak = $AVE_DB->Query("
		SELECT *
		FROM " . PREFIX . "_request_conditions
		WHERE request_id = '" . $id . "' AND condition_status = '1'
		ORDER BY condition_position ASC
	");

	// Обрабатываем выпадающие списки

	if (!defined('ACP'))
	{
		$doc = 'doc_' . $AVE_Core->curentdoc->Id;
		if (isset($_POST['req_' . $id])) $_SESSION[$doc]['req_' . $id] = $_POST['req_' . $id];
		elseif (isset($_SESSION[$doc]['req_' . $id])) $_POST['req_' . $id] = $_SESSION[$doc]['req_' . $id];
	}
	if (!empty($_POST['req_' . $id]) && is_array($_POST['req_' . $id]))
	{
		$i=1;
		foreach ($_POST['req_' . $id] as $fid => $val)
		{
			if (!($val != '' && isset($_SESSION['val_' . $fid]) && in_array($val, $_SESSION['val_' . $fid]))) continue;
			$from_dd[] = "%%PREFIX%%_document_fields AS t0$i, ";
			$where_dd[] = "((t0$i.document_id = a.Id) AND (t0$i.rubric_field_id = $fid AND t0$i.field_value = '$val'))";
			++$i;
		}
	}

	$i = 0;

	while ($row_ak = $sql_ak->FetchRow())
	{
		// id поля рубрики
		$fid = $row_ak->condition_field_id;
		// значение для условия
		$val = trim($row_ak->condition_value);
		// если это поле используется для выпадающего списка или пустое значение для условия, пропускаем
		if (isset($_POST['req_' . $id]) && isset($_POST['req_' . $id][$fid]) || $val==='') continue;
		// И / ИЛИ
		if (!isset($join) && $row_ak->condition_join) $join = $row_ak->condition_join;
		// тип сравнения
		$type = $row_ak->condition_compare;

		// выясняем, числовое поле или нет
		if (!isset($numeric[$fid]))
		{
			$numeric[$fid] = (bool)$AVE_DB->Query("
				SELECT rubric_field_numeric
				FROM " . PREFIX . "_rubric_fields
				WHERE Id = '" . $fid . "'
			")->GetCell();
		}
		$fv = $numeric[$fid] ? "t$fid.field_number_value" : "UPPER(t$fid.field_value)";

		// подставляем название таблицы в свободные условия
		$val = addcslashes(str_ireplace(array('[field]','[numeric_field]'),$fv,$val),"'");

		// формируем выбор таблицы
		// первый раз евалом проходим значение и запоминаем это в переменной $v[$i]
		// как только таблица выбрана, фиксируем это в $t[$fid], чтобы не выбирать по несколько раз одни и те же таблицы
		$from[] = "<?php \$v[$i] = trim(eval2var(' ?>$val<? ')); \$t = array(); if (\$v[$i]>'' && !isset(\$t[$fid])) {echo \"%%PREFIX%%_document_fields AS t$fid,\"; \$t[$fid]=1;}?>";

		// обрабатываем условия
		switch ($type)
		{
			case 'N<':case '<': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv < UPPER('\$v[$i]'))) $join\" : ''?>"; break;
			case 'N>':case '>': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv > UPPER('\$v[$i]'))) $join\" : ''?>"; break;
			case 'N<=':case '<=': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv <= UPPER('\$v[$i]'))) $join\" : ''?>"; break;
			case 'N>=':case '>=': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv >= UPPER('\$v[$i]'))) $join\" : ''?>"; break;

			case '==': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv = UPPER('\$v[$i]'))) $join\" : ''?>"; break;
			case '!=': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv != UPPER('\$v[$i]'))) $join\" : ''?>"; break;
			case '%%': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv LIKE UPPER('%\$v[$i]%'))) $join\" : ''?>"; break;
			case '%': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv LIKE UPPER('\$v[$i]%'))) $join\" : ''?>"; break;
			case '--': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv NOT LIKE UPPER('%\$v[$i]%'))) $join\" : ''?>"; break;
			case '!-': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv NOT LIKE UPPER('\$v[$i]%'))) $join\" : ''?>"; break;

			case 'SEGMENT': $where[] = "<?
				\$v[$i]['seg']=@explode(',',\$v[$i]);
				\$v[$i]['seg'][0]=(int)trim(\$v[$i]['seg'][0]);
				\$v[$i]['seg'][1]=(int)trim(\$v[$i]['seg'][1]);
				echo (\$v[$i]>'' && \$v[$i]{0}!=',' && \$v[$i]['seg'][0] <= \$v[$i]['seg'][1]) ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv >= '\" . \$v[$i]['seg'][0] . \"' AND $fv <= '\" . \$v[$i]['seg'][1] . \"')) $join\" : '');?>"; break;
			case 'INTERVAL': $where[] = "<?
				\$v[$i]['seg']=@explode(',',\$v[$i]);
				\$v[$i]['seg'][0]=(int)trim(\$v[$i]['seg'][0]);
				\$v[$i]['seg'][1]=(int)trim(\$v[$i]['seg'][1]);
				echo (\$v[$i]>'' && \$v[$i]{0}!=',' && \$v[$i]['seg'][0] < \$v[$i]['seg'][1]) ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv > '\" . \$v[$i]['seg'][0] . \"' AND $fv < '\" . \$v[$i]['seg'][1] . \"')) $join\" : '');?>"; break;

			case 'IN=': $where[] = "<?=(\$v[$i]>'' && \$v[$i]{0}!=',') ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv IN (\$v[$i]))) $join\" : ''?>"; break;
			case 'NOTIN=': $where[] = "<?=(\$v[$i]>'' && \$v[$i]{0}!=',') ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv NOT IN (\$v[$i]))) $join\" : ''?>"; break;

			case 'ANY': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND $fv=ANY(\$v[$i]))) $join\" : ''?>"; break;
			case 'FRE': $where[] = "<?=\$v[$i]>'' ? \"(t$fid.document_id = a.id AND (t$fid.rubric_field_id = '$fid' AND (\$v[$i]))) $join\" : ''?>"; break;
		}
		$i++;
	}

	$retval = array();

	if (!empty($where) || !empty($where_dd))
	{
		if (!empty($where_dd)){
			$from = (isset($from_dd) ? array_merge($from, $from_dd) : $from);
			$from = implode(' ', $from);
			$where_dd = (isset($where_dd) ? ' AND ' : '') . implode(' AND ', $where_dd);
			$where = implode(' ', $where) . " <?php \$a = array(); echo (!array_sum(\$a) || '$join'=='AND') ? '1=1' : '1=0'?>";
			$retval = array('from'=>$from,'where'=> $where.$where_dd);
		} else {
			$from = implode(' ', $from);
			$where = implode(' ', $where) . " <?php \$a = array(); echo (!array_sum(\$a) || '$join'=='AND') ? '1=1' : '1=0'?>";
			$retval = array('from'=>$from,'where'=> $where);
		}
	}

	// если вызвано из админки или просили обновить, обновляем запрос в бд
	if (defined('ACP') || $update_db)
	{
		$AVE_DB->Query("
			UPDATE " . PREFIX . "_request
			SET	request_where_cond = '" . ($retval ? addslashes(serialize($retval)) : '') . "'
			WHERE Id = '" . $id . "'
		");
	}

	return @$retval;
}


/*
* Функция принимает строку, и возвращает
* адрес первого изображения, которую найдет
*/

function getImgSrc($data)
{
	preg_match_all("/(<img )(.+?)( \/)?(>)/u", $data, $images);
	$host = $images[2][0];

	if (preg_match("/(src=)('|\")(.+?)('|\")/u", $host, $matches) == 1)
	$host = $matches[3];

	preg_match('@/index\.php\?.*thumb=(.*?)\&@i', $host, $matches);
	if (isset($matches[1])) {
		return $matches[1];
	} else {
		preg_match('/(.+)'.THUMBNAIL_DIR.'\/(.+)-.\d+x\d+(\..+)/u', $host, $matches);
		if (isset($matches[1])) {
			return $matches[1] . $matches[2] . $matches[3];
		} else {
			return $host;
		}
	}
}

/**
 * Функция обработки тэгов полей с использованием шаблонов
 * в соответствии с типом поля
 *
 * @param int $rubric_id	идентификатор рубрики
 * @param int $document_id	идентификатор документа
 * @param int $maxlength	максимальное количество символов обрабатываемого поля
 * @return string
 */
function request_get_document_field($field_id, $document_id, $maxlength = '', $rubric_id=0)
{
	if (!is_numeric($document_id) || $document_id < 1) return '';

	$document_fields = get_document_fields($document_id);

	if (!is_array($document_fields[$field_id]))$field_id=intval($document_fields[$field_id]);

	if (empty($document_fields[$field_id])) return '';

	$field_value = trim($document_fields[$field_id]['field_value']);
	if ($field_value == '' && $document_fields[$field_id]['tpl_req_empty']) return '';

	$func = 'get_field_' . $document_fields[$field_id]['rubric_field_type'];

	if(!is_callable($func)) $func = 'get_field_default';

	$field_value = $func($field_value, 'req', $field_id, $document_fields[$field_id]['rubric_field_template_request'], $document_fields[$field_id]['tpl_req_empty'], $maxlength, $document_fields, $rubric_id, $document_fields[$field_id]['rubric_field_default']);

	if ($maxlength != '')
	{
		if ($maxlength == 'more' || $maxlength == 'esc'|| $maxlength == 'img')
		{
			if($maxlength == 'more')
			{
				$teaser = explode('<a name="more"></a>', $field_value);
				//$teaser = explode('<hr />', $field_value);
				$field_value = $teaser[0];
			}
			elseif($maxlength == 'esc')
			{
				$field_value = addslashes($field_value);
			}
			elseif($maxlength == 'img')
			{
				$field_value = getImgSrc($field_value);
			}
		}
		elseif (is_numeric($maxlength))
		{
			if ($maxlength < 0)
			{
				$field_value = str_replace(array("\r\n","\n","\r"), " ", $field_value);
				$field_value = strip_tags($field_value, "<a>");
				$field_value = preg_replace('/  +/', ' ', $field_value);
				$field_value = trim($field_value);
				$maxlength = abs($maxlength);
			}
			if ($maxlength != 0)
			{
				$field_value = mb_substr($field_value, 0, $maxlength) . ((strlen($field_value) > $maxlength) ? '... ' : '');
			}
		}
		else return false;
	}

	return $field_value;
}

function showteaser($id, $tparams = ''){
	$item = showrequestelement($id, '', $tparams);
	$item = str_replace('[tag:path]', ABS_PATH, $item);
	$item = str_replace('[tag:mediapath]', ABS_PATH . 'templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/', $item);
	return $item;
}

//Функция получения уникальных параметров для каждого тизера
function f_params_of_teaser($id_param_array,$num){
	global $AVE_DB, $params_of_teaser;
	return $params_of_teaser[$id_param_array][$num];
}

function showrequestelement($mixed, $template = '', $tparams = ''){

global $AVE_DB, $req_item_num, $params_of_teaser, $use_cache;

	if (is_array($mixed)) $mixed = $mixed[1];

	$row = (is_object($mixed) ? $mixed : $AVE_DB->Query("
			SELECT
				a.Id,
				a.rubric_id,
				a.document_parent,
				a.document_title,
				a.document_alias,
				a.document_author_id,
				a.document_count_view,
				a.document_published,
				a.document_meta_keywords
			FROM
				" . PREFIX . "_documents AS a
			WHERE
			a.Id = '" . intval($mixed) . "'
			GROUP BY a.Id
			LIMIT 1
		")->FetchRow());

		if(!$row) return '';

		$tparams_id = '';

		if ($tparams!=''){
			$tparams_id = $row->Id.md5($tparams); // Создаем уникальный id для каждого набора параметров
			$params_of_teaser[$tparams_id] = Array(); // Для отмены лишних ворнингов
			$tparams = trim($tparams,'[]:'); // Удаляем: слева ':[', справа ']'
			$params_of_teaser[$tparams_id] = explode('|',$tparams); // Заносим параметры в массив уникального id
		};

		$template = ($template > '' ? $template : $AVE_DB->Query(
					"SELECT rubric_teaser_template FROM " . PREFIX . "_rubrics WHERE Id='" . intval($row->rubric_id) . "'"
				)->GetCell());

		$cachefile_docid = BASE_DIR . '/cache/sql/request/' . $row->Id . '/request-' . md5($template) . '.cache';

		if(!file_exists($cachefile_docid))
			{
				$template = preg_replace("/\[tag:if_notempty:rfld:([a-zA-Z0-9-_]+)]\[(more|esc|img|[0-9-]+)]/u", '<'.'?php if((htmlspecialchars(request_get_document_field(\'$1\', '.$row->Id.', \'$2\', '.(int)$row->rubric_id.'), ENT_QUOTES)) != \'\') { '.'?'.'>', $template);
				$template = preg_replace("/\[tag:if_empty:rfld:([a-zA-Z0-9-_]+)]\[(more|esc|img|[0-9-]+)]/u", '<'.'?php if((htmlspecialchars(request_get_document_field(\'$1\', '.$row->Id.', \'$2\', '.(int)$row->rubric_id.'), ENT_QUOTES)) == \'\') { '.'?'.'>', $template);
				$template = str_replace('[tag:if:else]', '<?php }else{ ?>', $template);
				$template = str_replace('[tag:/if]', '<?php } ?>', $template);

				$item = preg_replace_callback('/\[tag:sysblock:([0-9-]+)\]/', 'parse_sysblock', $template);
				$item = preg_replace('/\[tag:rfld:([a-zA-Z0-9-_]+)]\[(more|esc|img|[0-9-]+)]/e', "request_get_document_field(\"$1\", $row->Id, \"$2\"," . (int)$row->rubric_id . ")", $item);
				$item = str_replace('[tag:path]', ABS_PATH, $item);
				$item = str_replace('[tag:mediapath]', ABS_PATH . 'templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/', $item);
				$item = preg_replace('/\[tag:watermark:(.+?):([a-zA-Z]+):([0-9]+)\]/e', 'watermarks(\'$1\', \'$2\', $3)', $item);
				$item = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $item);

				if ($tparams != ''){
					// Заменяем tparam в тизере
					// $item = preg_replace('/\[tparam:([0-9]+)\]/', '<'.'?php echo $params_of_teaser["'.$tparams_id.'"][$1]'.'?'.'>', $item); // косячная версия, пока оставил
					$item = preg_replace('/\[tparam:([0-9]+)\]/e', "f_params_of_teaser(\"".$tparams_id."\",$1".")", $item);
				}else{
					// Если чистый запрос тизера, просто вытираем tparam
					$item = preg_replace('/\[tparam:([0-9]+)\]/', '', $item);
				}

				// Блок для проверки передачи
				/*
					if(count($params_of_teaser[$tparams_id])){
						_echo($params_of_teaser);
						_echo($row_Id_mas);
						_echo($item, true);
					}
				*/

				$link = rewrite_link('index.php?id=' . $row->Id . '&amp;doc=' . (empty($row->document_alias) ? prepare_url($row->document_title) : $row->document_alias));
				$item = str_replace('[tag:link]', $link, $item);
				$item = str_replace('[tag:docid]', $row->Id, $item);
				$item = str_replace('[tag:docitemnum]', $req_item_num, $item);
				$item = str_replace('[tag:adminlink]', 'index.php?do=docs&action=edit&rubric_id='.$row->rubric_id.'&Id='.$row->Id.'', $item);
				$item = str_replace('[tag:doctitle]', stripslashes(htmlspecialchars_decode($row->document_title)), $item);
				$item = str_replace('[tag:dockeywords]', stripslashes(htmlspecialchars_decode($row->document_meta_keywords)), $item);
				$item = str_replace('[tag:docparent]', $row->document_parent, $item);
				$item = str_replace('[tag:docdate]', pretty_date(strftime(DATE_FORMAT, $row->document_published)), $item);
				$item = str_replace('[tag:doctime]', pretty_date(strftime(TIME_FORMAT, $row->document_published)), $item);
				$item = preg_replace('/\[tag:date:([a-zA-Z0-9-. \/]+)\]/e', "rus_date(date('$1', ".$row->document_published."))", $item);
				if (preg_match('/\[tag:docauthor]/u', $item)) {
					$item = str_replace('[tag:docauthor]', get_username_by_id($row->document_author_id), $item);
				}
				$item = str_replace('[tag:docauthorid]', $row->document_author_id, $item);
				$item = preg_replace('/\[tag:docauthoravatar:(\d+)\]/e', "getAvatar(".intval($row->document_author_id).",\"$1\")", $item);

				if (isset($use_cache) && $use_cache == 1){
					// Кеширование элементов запроса
					if(!file_exists(dirname($cachefile_docid))) @mkdir(dirname($cachefile_docid), 0777, true);
					file_put_contents($cachefile_docid, $item);
				}

			}
			else
			{
				$item = file_get_contents($cachefile_docid);
			}

			$item = str_replace('[tag:docviews]', $row->document_count_view, $item);
			$item = str_replace('[tag:doccomments]', isset($row->nums) ? $row->nums : '', $item);
			//$item = str_replace('[tag:docvotes]', isset($row->votes) ? $row->votes : '', $item);
			//$item = str_replace('[tag:docdayviews]', isset($row->dayviews) ? $row->dayviews : '', $item);

		return $item;
}

/**
 * Обработка тега запроса.
 * Возвращает список документов удовлетворяющих параметрам запроса
 * оформленный с использованием шаблона
 *
 * @param int $id	идентификатор запроса
 * @return string
 */



function request_parse($id, $params=array())
{
	global $AVE_Core, $AVE_DB, $request_documents;

	// если id пришёл из тега, берём нужную часть массива
	if (is_array($id)) $id = $id[1];

	$t = array();
	$a = array();
	$v = array();

	$request = $AVE_DB->Query("
		SELECT *
		FROM " . PREFIX . "_request
		WHERE Id = '" . $id . "'
	")->FetchRow();

	$request_c = $AVE_DB->Query("
		SELECT DISTINCT(condition_field_id) AS fid
		FROM " . PREFIX . "_request_conditions
		WHERE request_id = '" . $id . "' AND condition_status = '1'
	");

	$conditions = array();
	while ($conds = $request_c->FetchArray()) $conditions[] = $conds['fid'];

	// выходим, если нет запроса
	if (!is_object($request)) return '';

	// фиксируем время начала генерации запроса
	$gen_start = microtime();

	// массив для полей SELECT
	$request_select = array();
	// массив для присоединения таблиц JOIN
	$request_join = array();
	// массив для добавления условий WHERE
	$request_where = array();
	// массив для сортировки результатов ORDER BY
	$request_order = array();
	// массив для сортировки результатов ORDER BY
	$request_order_fields = array();

	$request_order_str = '';
	$request_select_str = '';

	// сортировка по полям из переданных параметров
	if (empty($params['SORT']) && !empty($_REQUEST['requestsort_'.$id]) && !is_array($_REQUEST['requestsort_'.$id])){
		//разрешаем перебор полей для сортировки через ";"
		$sort = explode(';', $_REQUEST['requestsort_'.$id]);
		foreach($sort as $v){
			$v1 = explode('=', $v);
			//Если хотим сортировку DESC то пишем alias=0
			$params['SORT'][$v1[0]] = (isset($v1[1]) && $v1[1]==0 ? 'DESC' : 'ASC');
		}
	}

	if (!empty($params['SORT']) && is_array($params['SORT']))
	{
		foreach($params['SORT'] as $fid => $sort)
		{
			$fid = (int)get_field_num($request->rubric_id, $fid);

			if ((int)$fid <= 0)
				continue;

			$sort = strtolower($sort);

			if (!in_array($fid, $conditions))
			{
				$request_join[] = "<? if (preg_match('t[]'))?><?=!@\$t[$fid] ? \"LEFT JOIN " . PREFIX . "_document_fields AS t$fid ON (t$fid.document_id = a.Id AND t$fid.rubric_field_id='$fid')\" : ''?>";
			}

			$asc_desc = strpos(strtolower($sort),'asc') !== false ? 'ASC' : 'DESC';

			$request_order['field-'.$fid] = "t$fid.field_value " . $asc_desc;

			$request_order_fields[] = $fid;
		}
	}
	// сортировка по полю из настроек (только если не передана другая в параметрах)
	elseif ($request->request_order_by_nat)
	{
		$fid = (int)$request->request_order_by_nat;
		// добавляем с учётом переменной $t из условий, чтобы не выбирать те же таблиы заново - это оптимизирует время
		if (!in_array($fid, $conditions)){
			$request_join[] = "<?=(!isset(\$t[$fid])) ? \"LEFT JOIN " . PREFIX . "_document_fields AS t$fid ON (t$fid.document_id = a.Id AND t$fid.rubric_field_id='$fid')\" : ''?>";
		}
		$request_order['field-'.$fid] = "t$fid.field_value " . $request->request_asc_desc;
		$request_order_fields[] = $fid;
	}

	unset($conditions);

	// вторичная сортировка по параметру документа - добавляем в конец сортировок
	if (! empty($params['RANDOM']))
	{
		$request_order['sort'] = ($params['RANDOM'] == 1) ? 'RAND()' : '';
	}
	elseif ($request->request_order_by)
	{
		$request_order['sort'] = ($request->request_order_by == 'RAND()') ? 'RAND()' : 'a.' . $request->request_order_by . ' ' . $request->request_asc_desc;
	}

	// заменяем field_value на field_number_value во всех полях для сортировки, если поле числовое
	if (!empty($request_order_fields))
	{
		$sql_numeric = $AVE_DB->Query("
			SELECT Id
			FROM " . PREFIX . "_rubric_fields
			WHERE Id IN (" . implode(',', $request_order_fields) . ") AND rubric_field_numeric = '1'
		");
		if($sql_numeric->_result->num_rows > 0){
			while ($fid = (int)$sql_numeric->FetchRow()->Id)
				$request_order['field-'.$fid] = str_replace('field_value','field_number_value', $request_order['field-'.$fid]);
		}
	}

	// статус: если в параметрах, то его ставим. иначе выводим только активные доки
	$request_where[] = "a.document_status = '" . ((isset($params['STATUS'])) ? (int)$params['STATUS'] : '1') . "'";

	// не выводить текущий документ
	if ($request->request_hide_current)
		$request_where[] = "a.Id != '" . get_current_document_id() . "'";

	// язык
	if ($request->request_lang)
		$request_where[] = "a.document_lang = '" . $_SESSION['user_language'] . "'";

	// дата публикации документов
	if (get_settings('use_doctime'))
		$request_where[] = "a.document_published <= UNIX_TIMESTAMP() AND (a.document_expire = 0 OR a.document_expire >=UNIX_TIMESTAMP())";

	// условия запроса
	// если используется выпадающий список, получаем строку без сохранения
	if (!empty($_POST['req_' . $id]) || !empty($_SESSION['doc_' . $AVE_Core->curentdoc->Id]['req_' . $id]))
		$where_cond = request_get_condition_sql_string($request->Id, false);
	// если условия пустые, получаем строку с сохранением её в бд
	elseif (!$request->request_where_cond)
		$where_cond = request_get_condition_sql_string($request->Id, true);
	// иначе, берём из запроса
	else $where_cond = unserialize($request->request_where_cond);

	$where_cond['from'] = (isset($where_cond['from'])) ? str_replace('%%PREFIX%%', PREFIX, $where_cond['from']) : '';
	if (isset($where_cond['where'])) $request_where[] = $where_cond['where'];

	// родительский документ
	if (isset($params['PARENT']) && (int)$params['PARENT'] > 0)
		$request_where[] = "a.document_parent = '" . (int)$params['PARENT'] . "'";

	// автор
	// если задано в параметрах
	if (isset($params['USER_ID']))
		$user_id = (int)$params['USER_ID'];
	// если стоит галка, показывать только СВОИ документы в настройках
	// аноним не увидит ничего, так как 0 юзера нет
	elseif ($request->request_only_owner == '1')
		$user_id = (int)$_SESSION['user_id'];

	// если что-то добавили, пишем
	if (isset($user_id)) $request_where[] = "a.document_author_id = '" . $user_id . "'";

	// произвольные условия
	if (isset($params['USER_WHERE']) && $params['USER_WHERE'] > '')
	{
		if (is_array($params['USER_WHERE']))
			$request_where = array_merge($request_where,$params['USER_WHERE']);
		else
			$request_where[] = $params['USER_WHERE'];
	}

	// готовим строку с условиями
	array_unshift($request_where,"
		a.Id != '1' AND a.Id != '" . PAGE_NOT_FOUND_ID . "' AND
		a.rubric_id = '" . $request->rubric_id . "' AND
		a.document_deleted != '1'");

	$request_where_str = '(' . implode(') AND (',$request_where) . ')';

	// количество выводимых доков
	$params['LIMIT']=(!empty($params['LIMIT'])
		? $params['LIMIT']
		: (!empty($_REQUEST['requestlimiter_'.$id])
			? $_REQUEST['requestlimiter_'.$id]
			: (int)$request->request_items_per_page));

	$limit = (isset($params['LIMIT']) && is_numeric($params['LIMIT']) && $params['LIMIT'] > '') ? (int)$params['LIMIT'] : (int)$request->request_items_per_page;

	$start = ($request->request_show_pagination == 1)
		? get_current_page('apage') * $limit - $limit
		: 0;

	$limit_str = ($limit > 0)
		? "LIMIT " . $start . "," . $limit
		: '';

	// готовим строку с сортировкой
	if ($request_order) $request_order_str = "ORDER BY " . implode(', ',$request_order);

	// готовим строку с полями
	if ($request_select) $request_select_str = ',' . implode(",\r\n",$request_select);

	unset($a, $t, $v);

	// составляем запрос к БД
	$sql = " ?>
		SELECT STRAIGHT_JOIN SQL_CALC_FOUND_ROWS
			a.*
			" . $request_select_str . "
		FROM
			" . $where_cond['from'] . "
			" . PREFIX . "_documents AS a
			" . implode(' ',$request_join) . "
		WHERE
			" . $request_where_str . "
		GROUP BY a.Id
		" . $request_order_str . "
		" . $limit_str . "
	<?php ";

//	if (UGROUP ==1) _echo($sql);
	$sql_request = eval2var($sql);
//	if (UGROUP ==1) _echo($sql_request);

	unset($sql);

	// выполняем запрос к бд
	$sql = $AVE_DB->Query($sql_request, (int)$request->request_cache_lifetime, 'rub_' . $request->rubric_id);

	if($request->request_show_pagination == 1 && empty($params['SHOW']))
	{
		$num_items = $AVE_DB->NumAllRows($sql_request, (int)$request->request_cache_lifetime, 'rub_' . $request->rubric_id);
	} else {
		$num_items = $AVE_DB->GetFoundRows();
	}

	unset($sql_request);

	// приступаем к обработке шаблона
	$main_template = $request->request_template_main;

	if ($num_items > 0)
	{
		$main_template = preg_replace('/\[tag:if_empty](.*?)\[\/tag:if_empty]/si', '', $main_template);
		$main_template = str_replace (array('[tag:if_notempty]','[/tag:if_notempty]'), '', $main_template);
	}
	else
	{
		$main_template = preg_replace('/\[tag:if_notempty](.*?)\[\/tag:if_notempty]/si', '', $main_template);
		$main_template = str_replace (array('[tag:if_empty]','[/tag:if_empty]'), '', $main_template);
	}

	$page_nav = '';

	if ($request->request_show_pagination == 1 && empty($params['SHOW']))
	{
		$num_pages = ($limit > 0) ? ceil($num_items / $limit) : 0;

		if (isset($_REQUEST['apage']) && is_numeric($_REQUEST['apage']) && $_REQUEST['apage'] > $num_pages)
		{
			$redirect_link = rewrite_link('index.php?id=' . $AVE_Core->curentdoc->Id
				. '&amp;doc=' . (empty($AVE_Core->curentdoc->document_alias) ? prepare_url($AVE_Core->curentdoc->document_title) : $AVE_Core->curentdoc->document_alias)
				. ((isset($_REQUEST['artpage']) && is_numeric($_REQUEST['artpage'])) ? '&amp;artpage=' . $_REQUEST['artpage'] : '')
				. ((isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? '&amp;page=' . $_REQUEST['page'] : ''));

			header('Location:' . $redirect_link);
			exit;
		}

		@$GLOBALS['page_id'][$_REQUEST['id']]['apage'] = (@$GLOBALS['page_id'][$_REQUEST['id']]['apage'] > $num_pages ? @$GLOBALS['page_id'][$_REQUEST['id']]['apage'] : $num_pages);

		// обрабатываем тег навигации
		$page_nav = '';

		if ($num_pages > 1)
		{
			$queries = '';

			if ($request->request_use_query == 1 || (isset($params['ADD_GET']) && $params['ADD_GET'] == 1))
				$queries = ($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

			$page_nav = '<a class="page_nav" href="index.php?id='
				. $AVE_Core->curentdoc->Id

				. '&amp;doc=' . (empty($AVE_Core->curentdoc->document_alias)
					? prepare_url($AVE_Core->curentdoc->document_title)
					: $AVE_Core->curentdoc->document_alias)

				. ((isset($_REQUEST['artpage']) && is_numeric($_REQUEST['artpage']))
					? '&amp;artpage=' . $_REQUEST['artpage']
					: '')

				. '&amp;apage={s}'

				. ((isset($_REQUEST['page']) && is_numeric($_REQUEST['page']))
					? '&amp;page=' . $_REQUEST['page']
					: '')

				// добавляем GET-запрос в пагинацию
				. clean_php($queries)

				. '" data-page="{s}">{t}</a>';

			$page_nav = get_pagination($num_pages, 'apage', $page_nav, get_settings('navi_box'));

			// Костыль
			$page_nav = str_ireplace('"//"', '"/"', str_ireplace('///', '/', rewrite_link($page_nav)));
		}
	}

	// элементы запроса
	$rows = array();
	// id найденных документов
	$request_documents = array();

	while ($row = $sql->FetchRow())
	{
		array_push($request_documents, $row->Id);
		array_push($rows, $row);
	}

	// обрабатываем шаблоны элементов
	$items = '';
	$x = 0;
	$items_count = count($rows);

	global $req_item_num, $use_cache;

	$use_cache = $request->request_cache_elements;

	$item = '';

	foreach ($rows as $row)
	{
		$x++;
		$last_item = ($x == $items_count ? true : false);
		$item_num = $x;
		$req_item_num = $item_num;
		$item = showrequestelement($row, $request->request_template_item);
		$item = '<'.'?php $item_num='.var_export($item_num,1).'; $last_item='.var_export($last_item,1).'?'.'>'.$item;
		$item = '<?php $req_item_id = ' . $row->Id . ';?>' . $item;
		$item = str_replace('[tag:if_first]', '<'.'?php if(isset($item_num) && $item_num===1) { ?'.'>', $item);
		$item = str_replace('[tag:if_not_first]', '<'.'?php if(isset($item_num) && $item_num!==1) { ?'.'>', $item);
		$item = str_replace('[tag:if_last]', '<'.'?php if(isset($last_item) && $last_item) { ?'.'>', $item);
		$item = str_replace('[tag:if_not_last]', '<'.'?php if(isset($item_num) && !$last_item) { ?'.'>', $item);
		$item = preg_replace('/\[tag:if_every:([0-9-]+)\]/u', '<'.'?php if(isset($item_num) && !($item_num % $1)){ '.'?'.'>', $item);
		$item = preg_replace('/\[tag:if_not_every:([0-9-]+)\]/u', '<'.'?php if(isset($item_num) && ($item_num % $1)){ '.'?'.'>', $item);
		$item = str_replace('[tag:/if]', '<'.'?php  } ?>', $item);
		$item = str_replace('[tag:if_else]', '<'.'?php  }else{ ?>', $item);
		$items .= $item;
	}

	// парсим тизер документа
	//$items = preg_replace_callback('/\[tag:teaser:(\d+)\]/', "showteaser", $items);

	// обрабатываем теги запроса
	$main_template = preg_replace_callback('/\[tag:sysblock:([0-9-]+)\]/', 'parse_sysblock', $main_template);
	$main_template = str_replace('[tag:pages]', $page_nav, $main_template);
	$main_template = preg_replace('/\[tag:date:([a-zA-Z0-9-. \/]+)\]/e', "rus_date(date('$1', ".$AVE_Core->curentdoc->document_published."))", $main_template);
	$main_template = str_replace('[tag:docdate]', pretty_date(strftime(DATE_FORMAT, $AVE_Core->curentdoc->document_published)), $main_template);
	$main_template = str_replace('[tag:doctime]', pretty_date(strftime(TIME_FORMAT, $AVE_Core->curentdoc->document_published)), $main_template);

	if (preg_match('/\[tag:docauthor]/u', $item))
	{
		$main_template = str_replace('[tag:docauthor]', get_username_by_id($AVE_Core->curentdoc->document_author_id), $main_template);
	}

	$main_template = str_replace('[tag:doctotal]', $num_items, $main_template);
	$main_template = str_replace('[tag:pagetitle]', stripslashes(htmlspecialchars_decode($AVE_Core->curentdoc->document_title)), $main_template);
	$main_template = str_replace('[tag:alias]', $AVE_Core->curentdoc->document_alias, $main_template);
	$main_template = preg_replace('/\[tag:dropdown:([,0-9]+)\]/e', "request_get_dropdown(\"$1\", " . $request->rubric_id . ", " . $request->Id . ");", $main_template);

	// вставляем элементы запроса
	$return = str_replace('[tag:content]', $items, $main_template);
	$return = parse_hide($return);

	// парсим тизер документа
	//$return = preg_replace_callback('/\[tag:teaser:(\d+)\]/e', "showteaser", $return);

	$return = str_replace('[tag:path]', ABS_PATH, $return);
	$return = str_replace('[tag:mediapath]', ABS_PATH . 'templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/', $return);

	// парсим модули
	$return = $AVE_Core->coreModuleTagParse($return);

	// фиксируем время генерации запроса
	$gen_end = microtime()-$gen_start;
	$GLOBALS['block_generate'][] = array('REQUEST_' . $id => $gen_end);

//	Статистика
//	$return .= "<br>Найдено: $num_items<br>Показано: $items_count<br>Время: ".number_format($gen_end, 3, ',', ' ')." сек<br>Память: ".number_format(memory_get_peak_usage()/1024, 0, ',', ' ') . 'kb';

	return $return;
}

/**
 * Функция получения содержимого поля для обработки в шаблоне запроса
 * <pre>
 * Пример использования в шаблоне:
 *   <li>
 *	 <?php
 *	  $r = request_get_document_field_value(12, [tag:docid]);
 *	  echo $r . ' (' . strlen($r) . ')';
 *	 ?>
 *   </li>
 * </pre>

 *
 * @param int $rubric_id	идентификатор поля, для [tag:rfld:12][150] $rubric_id = 12
 * @param int $document_id	идентификатор документа к которому принадлежит поле.
 * @param int $maxlength	необязательный параметр, количество возвращаемых символов.
 * 							Если данный параметр указать со знаком минус
 * 							содержимое поля будет очищено от HTML-тегов.
 * @return string
 */
function request_get_document_field_value($rubric_id, $document_id, $maxlength = 0)
{

	if (!is_numeric($rubric_id) || $rubric_id < 1 || !is_numeric($document_id) || $document_id < 1) return '';

	$document_fields = get_document_fields($document_id);

	$field_value = isset($document_fields[$rubric_id]) ? $document_fields[$rubric_id]['field_value'] : '';

	if (!empty($field_value))
	{
		$field_value = strip_tags($field_value, '<br /><strong><em><p><i>');
		$field_value = str_replace('[tag:mediapath]', ABS_PATH . 'templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/', $field_value);
	}

	if (is_numeric($maxlength) && $maxlength != 0)
	{
		if ($maxlength < 0)
		{
			$field_value = str_replace(array("\r\n", "\n", "\r"), ' ', $field_value);
			$field_value = strip_tags($field_value, "<a>");
			$field_value = preg_replace('/  +/', ' ', $field_value);
			$maxlength = abs($maxlength);
		}
		$field_value = mb_substr($field_value, 0, $maxlength) . (strlen($field_value) > $maxlength ? '... ' : '');
	}

	return $field_value;
}

/**
 * Функция формирования выпадающих списков
 * для управления условиями запроса в публичной части
 *
 * @param string $dropdown_ids	идентификаторы полей
 * 								типа выпадающий список указанные через запятую
 * @param int $rubric_id		идентификатор рубрики
 * @param int $request_id		идентификатор запроса
 * @return string
 */
function request_get_dropdown($dropdown_ids, $rubric_id, $request_id)
{
	global $AVE_Core, $AVE_DB, $AVE_Template;

	$dropdown_ids = explode(',', preg_replace('/[^,\d]/', '', $dropdown_ids));
	$dropdown_ids[] = 0;
	$dropdown_ids = implode(',', $dropdown_ids);
	$doc = 'doc_' . $AVE_Core->curentdoc->Id;
	$control = array();

	$sql = $AVE_DB->Query("
		SELECT
			Id,
			rubric_field_title,
			rubric_field_default
		FROM " . PREFIX . "_rubric_fields
		WHERE Id IN(" . $dropdown_ids . ")
		AND rubric_id = '" . $rubric_id . "'
		AND rubric_field_type = 'dropdown'
	",-1,'rub_'.$rubric_id);
	while ($row = $sql->FetchRow())
	{
		$dropdown['titel'] = $row->rubric_field_title;
		$dropdown['selected'] = isset($_SESSION[$doc]['req_' . $request_id][$row->Id]) ? $_SESSION[$doc]['req_' . $request_id][$row->Id] : '';
		$dropdown['options'] = $_SESSION['val_' . $row->Id] = explode(',', $row->rubric_field_default);
		$control[$row->Id] = $dropdown;
	}

	$AVE_Template->assign('request_id', $request_id);
	$AVE_Template->assign('ctrlrequest', $control);
	return $AVE_Template->fetch(BASE_DIR . '/templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/modules/request/remote.tpl');
}
?>
