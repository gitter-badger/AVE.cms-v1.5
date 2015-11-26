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
 * Определяем пустое изображение
 */
@$img_pixel = 'templates/images/blanc.gif';


/**
 * Проверка папки /fields/ на наличие полей
 */
if (is_dir(BASE_DIR . '/fields/'))
{
	$d = dir(BASE_DIR . '/fields');

	while (false !== ($entry = $d->read()))
	{
		$field_dir = $d->path . '/' . $entry;

		if (is_dir($field_dir) && file_exists($field_dir . '/field.php'))
			require_once($field_dir . '/field.php');
	}

	$d->Close();
}


/**
 * Проверка папок /fields/ в модулях, на наличие полей
 */
$d = dir(BASE_DIR . '/modules');

while (false !== ($entry = $d->read()))
{
	$module_dir = $d->path . '/' . $entry;

	if (is_dir($module_dir) && file_exists($module_dir . '/field.php'))
		require_once($module_dir . '/field.php');
}

$d->Close();


/**
 * Поле по умолчанию
 *
 * @param        $field_value
 * @param        $action
 * @param int    $field_id
 * @param string $tpl
 * @param int    $tpl_empty
 * @param null   $maxlength
 * @param array  $document_fields
 * @param int    $rubric_id
 * @param null   $default
 *
 * @return string
 */
function get_field_default($field_value, $action, $field_id=0, $tpl='', $tpl_empty=0, &$maxlength=null, $document_fields=array(), $rubric_id=0, $default=null)
{
	switch ($action)
	{
		case 'edit':
				return '<input type="text" style="width: 400px" name="feld[' . $field_id . ']" value="' . $field_value . '">';
		case 'doc':
		case 'req':
			if (!$tpl_empty)
			{
				$field_param = explode('|', $field_value);
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $tpl);
			}
			return $field_value;

		default: return $field_value;
	}
}


/**
 * Возвращаем тип поля
 *
 * @return string
 */
function get_field_type($type = '')
{
	static $fields;

	if(is_array($fields))
		return $fields;

	$arr = get_defined_functions();

	$fields = array();
	$field = array();

	foreach($arr['user'] as $v)
	{
		if(trim(substr($v, 0, strlen('get_field_'))) == 'get_field_')
		{
			$d = '';

			$name = @$v('', 'name', '', '', 0, $d);

			$id = substr($v, strlen('get_field_'));

			if ($name != false && is_string($name))
				$fields[] = array('id' => $id,'name' => (isset($fields_vars[$name])
						? $fields_vars[$name]
						: $name));

			if (! empty($type) && $id == $type)
				$field =  array('id' => $id,'name' => (isset($fields_vars[$name])
						? $fields_vars[$name]
						: $name));
		}
	}

	$fields = msort($fields, array('name'));

	return (! empty($type)) ? $field : $fields;
}


/**
 * Возвращаем алиас по номеру поля
 *
 * @param $id
 * @return string
 */
function get_field_alias($id){
	global $AVE_DB;
	static $alias_field_id=array();
	if(isset($alias_field_id[$id])) return $alias_field_id[$id];
	$alias_field_id[$id] = $AVE_DB->Query("SELECT rubric_field_alias FROM " . PREFIX . "_rubric_fields WHERE Id=".intval($id))->GetCell();
	return $alias_field_id[$id];
}


/**
 * Возвращаем номер поля по рубрике и алиасу
 *
 * @param $rubric_id
 * @param $alias
 *
 * @return string
 */
function get_field_num($rubric_id, $alias){
	global $AVE_DB;
	static $alias_field_id=array();
	if(isset($alias_field_id[$rubric_id][$alias])) return $alias_field_id[$rubric_id][$alias];
	$alias_field_id[$rubric_id][$alias] = $AVE_DB->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE (rubric_field_alias='".addslashes($alias)."' OR Id='".intval($alias)."') AND rubric_id=".intval($rubric_id))->GetCell();
	return $alias_field_id[$rubric_id][$alias];
}

/**
 * Возвращаем
 *
 * @param $rubric_id
 * @param $id
 *
 * @return string
 */
function get_field_default_value($id)
{
	global $AVE_DB;

	static $alias_field_id = array();

	if(isset($alias_field_id[$id]))
		return $alias_field_id[$id];

	$alias_field_id[$id] = $AVE_DB->Query("SELECT rubric_field_default FROM " . PREFIX . "_rubric_fields WHERE Id = ".intval($id))->GetCell();

	return $alias_field_id[$id];
}

/**
 * Возвращаем шаблон tpl или пусто
 *
 * @param string $dir
 * @param int    $field_id идентификатор поля
 * @param string $type
 *
 * @return string
 */
function get_field_tpl($dir='', $field_id=0, $type='admin'){

	$alias_field_id = get_field_alias($field_id);

	switch ($type) {
		case '':
		case 'admin':
		default:
			$tpl = (file_exists($dir.'field-'.$field_id.'.tpl')) ? $dir.'field-'.$field_id.'.tpl' : ((file_exists($dir.'field-'.$alias_field_id.'.tpl')) ? $dir.'field-'.$alias_field_id.'.tpl' : $dir.'field.tpl');
			$tpl = (@filesize($tpl)) ? $tpl : '';
			break;

		case 'doc':
			$tpl = (file_exists($dir.'field-doc-'.$field_id.'.tpl')) ? $dir.'field-doc-'.$field_id.'.tpl' : ((file_exists($dir.'field-doc-'.$alias_field_id.'.tpl')) ? $dir.'field-doc-'.$alias_field_id.'.tpl' : $dir.'field-doc.tpl');
			$tpl = (@filesize($tpl)) ? $tpl : '';
			break;

		case 'req':
			$tpl = (file_exists($dir.'field-req-'.$field_id.'.tpl')) ? $dir.'field-req-'.$field_id.'.tpl' : ((file_exists($dir.'field-req-'.$alias_field_id.'.tpl')) ? $dir.'field-req-'.$alias_field_id.'.tpl' : $dir.'field-req.tpl');
			$tpl = (@filesize($tpl)) ? $tpl : '';
			break;
	}

	return $tpl;
}


/**
 * Формирование поля документа в соответствии с шаблоном отображения
 *
 * @param int  $field_id идентификатор поля
 * @param int  $document_id
 *
 * @return string
 */
function document_get_field($field_id, $document_id=null)
{
	global $AVE_Core;

	if (is_array($field_id)) $field_id = $field_id[1];

	$document_fields = get_document_fields(empty($document_id) ? $AVE_Core->curentdoc->Id : intval($document_id));

	if (!is_array($document_fields[$field_id]))$field_id = intval($document_fields[$field_id]);

	if (empty($document_fields[$field_id])) return '';

	$field_value = trim($document_fields[$field_id]['field_value']);

	$tpl_field_empty = $document_fields[$field_id]['tpl_field_empty'];

	// if ($field_value == '' && $tpl_field_empty) return '';

	$field_type = $document_fields[$field_id]['rubric_field_type'];

	$rubric_field_template = trim($document_fields[$field_id]['rubric_field_template']);

	$rubric_field_default = $document_fields[$field_id]['rubric_field_default'];

	//	$field_value = parse_hide($field_value);
	//	$field_value = ($length != '') ? truncate_text($field_value, $length, '…', true) : $field_value;

	$func='get_field_' . $field_type;

	if(!is_callable($func)) $func = 'get_field_default';

	$field_value = $func($field_value, 'doc', $field_id, $rubric_field_template, $tpl_field_empty, $maxlength, $document_fields, RUB_ID, $rubric_field_default);

	if (defined('UGROUP') && UGROUP == 1)
	{
		/*
					$f_value .= '<link rel="stylesheet" href="'. ABS_PATH .'inc/stdimage/gear.css" type="text/css" />';
					$f_value .= '<div class="contextual-links-wrapper contextual-links-processed">';
					$f_value .= '<span class="contextual-links-trigger" href="javascript:void(0);" onclick=window.open("'.ABS_PATH.'admin/index.php?do=docs&action=edit&closeafter=1&RubrikId=' . RUB_ID . '&Id=' . ((int)$_REQUEST['id'])
						. '&pop=1&feld=' . $field_id . '#' . $field_id . '","EDIT","left=0,top=0,width=1300,height=900,scrollbars=1");></span>';
					$f_value_end .= '</div>';
		*/
	}
	return $field_value;
}


/**
 * Функция получения содержимого поля для обработки в шаблоне рубрики
 *
 * @param int $field_id	идентификатор поля, для [tag:fld:12] $field_id = 12
 * @param int $length	необязательный параметр,
 * 						количество возвращаемых символов содержимого поля.
 * 						если данный параметр указать со знаком минус
 * 						содержимое поля будет очищено от HTML-тегов.
 * @return string
 */
function document_get_field_value($field_id, $length = 0)
{
	if (!is_numeric($field_id)) return '';

	$document_fields = get_document_fields(get_current_document_id());

	$field_value = trim($document_fields[$field_id]['field_value']);

	if ($field_value != '')
	{
		$field_value = strip_tags($field_value, "<br /><strong><em><p><i>");

		if (is_numeric($length) && $length != 0)
		{
			if ($length < 0)
			{
				$field_value = strip_tags($field_value);
				$field_value = preg_replace('/  +/', ' ', $field_value);
				$field_value = trim($field_value);
				$length = abs($length);
			}
			$field_value = truncate_text($field_value, $length, '…', true);
		}
	}

	return $field_value;
}


/**
 * Возвращаем истинное значение поля для документа
 *
 * @param int    $document_id id документа
 * @param string $field       id поля или его алиас
 *
 * @return string
 */
function get_document_field($document_id, $field)
{
	$document_fields = get_document_fields($document_id);

	if (!is_array($document_fields[$field])) $field = intval($document_fields[$field]);

	if (empty($document_fields[$field])) return false;

	$field_value = $document_fields[$field]['field_value'];

	return $field_value;
}


/**
 * Функция возвращает массив со значениями полей
 *
 * @param       $document_id
 * @param       array $values если надо вернуть документ с произвольными значениями - используется для ревизий документов
 * @internal    param int $id id документа
 * @return      array
 */
function get_document_fields($document_id, $values=null)
{
	global $AVE_DB, $request_documents;

	static $document_fields = array();

	if (!is_numeric($document_id)) return false;

	if (!isset ($document_fields[$document_id]))
	{
		$document_fields[$document_id] = false;
		$where = "WHERE doc_field.document_id = '" . $document_id . "'";
		$query="

			SELECT
				doc_field.Id,
				doc_field.document_id,
				doc_field.rubric_field_id,
				rub_field.rubric_field_alias,
				rub_field.rubric_field_type,
				rub_field.rubric_field_default,
				doc_field.field_value,
				text_field.field_value as field_value_more,
				doc.document_author_id,
				rub_field.rubric_field_title,
				rub_field.rubric_field_template,
				rub_field.rubric_field_template_request
			FROM
				" . PREFIX . "_document_fields AS doc_field

			JOIN
				" . PREFIX . "_rubric_fields AS rub_field
					ON doc_field.rubric_field_id = rub_field.Id
			LEFT JOIN
				" . PREFIX . "_document_fields_text AS text_field
					ON (doc_field.rubric_field_id = text_field.rubric_field_id AND doc_field.document_id = text_field.document_id)
			JOIN
				" . PREFIX . "_documents AS doc
					ON doc.Id = doc_field.document_id
			" . $where;
		$sql = $AVE_DB->Query($query,-1,'doc_'.$document_id);

		//Вдруг памяти мало!!!!
		if(memory_panic() && (count($document_fields) > 3))
		{
			$document_fields=array();
		}

		while ($row = $sql->FetchAssocArray())
		{
			$row['tpl_req_empty'] = (trim($row['rubric_field_template_request']) == '');
			$row['tpl_field_empty'] = (trim($row['rubric_field_template']) == '');

			$row['field_value']=(string)$row['field_value'].(string)$row['field_value_more'];

			if($values)
			{
				$row['field_value']=(isset($values[$row['rubric_field_id']]) ? $values[$row['rubric_field_id']] : $row['field_value']);
			}

			if ($row['field_value'] === '')
			{
				$row['rubric_field_template_request'] = preg_replace('/\[tag:if_notempty](.*?)\[\/tag:if_notempty]/si', '', $row['rubric_field_template_request']);
				$row['rubric_field_template_request'] = trim(str_replace(array('[tag:if_empty]','[/tag:if_empty]'), '', $row['rubric_field_template_request']));

				$row['rubric_field_template'] = preg_replace('/\[tag:if_notempty](.*?)\[\/tag:if_notempty]/si', '', $row['rubric_field_template']);
				$row['rubric_field_template'] = trim(str_replace(array('[tag:if_empty]','[/tag:if_empty]'), '', $row['rubric_field_template']));
			}
			else
			{
				$row['rubric_field_template_request'] = preg_replace('/\[tag:if_empty](.*?)\[\/tag:if_empty]/si', '', $row['rubric_field_template_request']);
				$row['rubric_field_template_request'] = trim(str_replace(array('[tag:if_notempty]','[/tag:if_notempty]'), '', $row['rubric_field_template_request']));

				$row['rubric_field_template'] = preg_replace('/\[tag:if_empty](.*?)\[\/tag:if_empty]/si', '', $row['rubric_field_template']);
				$row['rubric_field_template'] = trim(str_replace(array('[tag:if_notempty]','[/tag:if_notempty]'), '', $row['rubric_field_template']));
			}

			$document_fields[$row['document_id']][$row['rubric_field_id']] = $row;
			$document_fields[$row['document_id']][$row['rubric_field_alias']] = $row['rubric_field_id'];
		}
	}
	return $document_fields[$document_id];
}


/**
 * Возвращает содержимое поля документа по номеру
 *
 * @param int  $field_id ([tag:fld:X]) - номер поля
 * @param int  $doc_id
 * @param int  $parametr ([tag:parametr:X]) - часть поля
 *
 * @return string
 */
function get_field($field_id, $doc_id = null, $parametr = null)
{
	global $req_item_id;

	// если не передан $doc_id, то проверяем реквест
	if (!$doc_id && $req_item_id) $doc_id = $req_item_id;
	// или берём для текущего дока
	elseif (!$doc_id && $_REQUEST['id'] > 0) $doc_id = $_REQUEST['id'];
	elseif (!$doc_id) return;

	// забираем из базы массив полей
	$field = get_document_field($doc_id, $field_id);

	// возвращаем нужную часть поля
	if ($parametr !==  null)
	{
		$field = explode("|",$field);
		$field = array_values(array_diff($field, array('')));
		$field = $field[$parametr];
	}
	return $field;
}


/**
 * Возвращает элемент сериализованного поля по номеру и ключу
 *
 * @param int $field_id	([tag:fld:X]) - номер поля
 * @param int $item_id - номер элемента
 * @param int $doc_id	([tag:docid]) - id документа
 * @param int $parametr	([tag:parametr:X]) - номер параметра элемента
 * @return string
 */
function get_element($field_id, $item_id = 0, $parametr = null, $doc_id = null)
{
	global $req_item_id;

	// если не передан $doc_id, то проверяем реквест
	if (!$doc_id && $req_item_id) $doc_id = $req_item_id;
	// или берём для текущего дока
	elseif (!$doc_id && $_REQUEST['id'] > 0) $doc_id = $_REQUEST['id'];
	elseif (!$doc_id) return;

	// забираем из базы поле
	$field = get_field($field_id, $doc_id);
	$field = unserialize($field);

	// возвращаем нужную часть поля
	if ($parametr !==  null)
	{
		$field = $field[$item_id];
		$field = explode("|", $field);
		$field = $field[$parametr];
	} else {
		$field = $field[$item_id];
		$field = explode("|", $field);
		$field = $field[0];
	}
	return $field;
}

/**
 * Возвращает элемент сериализованного поля по номеру и ключу, через тег [tag:fld:XXX][XXX][XXX]
 *
 * @return string
 */
function return_element ()
{
	$param = func_get_args();
	$return = get_element($param[0][1], $param[0][2], $param[0][3]);
	return $return;
}

?>
