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
 * Функция обработки навигации
 *
 * @param int $navi_tag - идентификатор меню навигации
 * @return mixed|string
 */
function parse_navigation($navi_tag)
{
	global $AVE_DB, $AVE_Core;
	
	$gen_time = microtime();

	// извлекаем id из аргумента
	$navi_id  = (int)$navi_tag[1];

	// извлекаем level из аргумента
	$navi_print_level = $navi_tag[2];

	// получаем меню навигации по id,
	// и если такой не существует, выводим сообщение
	$navi_menu = get_navigations($navi_id);
	if (!$navi_menu)
	{
		echo 'Menu ', $navi_id, ' not found!';
		return;
	}

	// выставляем гостевую группу по дефолту 
	if (!defined('UGROUP')) define('UGROUP', 2);
	// выходим, если навиг. не предназначена для текущей группы
	if (!in_array(UGROUP, $navi_menu->navi_user_group)) return;

	// Находим активный пункт (связь текущего открытого документа и навигации). Нас интересуют:
	//		1) документы, которые сами связаны с пунктом меню
	//		2) пункты навигации, у которых ссылка совпадает с алиасом дока
	//		3) текущий level, текущий id
	// возвращаем в $navi_active через запятую id пунктов:
	//		1) активный пункт
	//		2) родители активного пункта
	// после ; через запятую все level-ы текущего пути, чтобы потом взять max
	// после ; id текущего пункта

	// id текущего документа. Если не задан, то главная страница
	$doc_active_id = (int)(($_REQUEST['id']) ? $_REQUEST['id'] : 1);
	// алиас текущего документа
	$alias = ltrim($AVE_Core->curentdoc->document_alias);
	// запрос для выборки по текущему алиасу
	$sql_doc_active_alias = '';
	if ($AVE_Core->curentdoc->Id == $doc_active_id)
	{
		$sql_doc_active_alias = "
			OR nav.document_alias = '" . $alias . "'
			OR nav.document_alias = '/" . $alias . "'
			OR nav.document_alias = '" . $alias . URL_SUFF . "'
			OR nav.document_alias = '/" . $alias . URL_SUFF . "'
		";
	}

	$navi_active = $AVE_DB->Query("
		SELECT CONCAT_WS(
				';',
				CONCAT_WS(',', nav.Id, nav.parent_id, nav2.parent_id),
				CONCAT_WS(',', nav.navi_item_level),
				nav.Id
			)
		FROM
			" . PREFIX . "_navigation_items AS nav
		JOIN
			" . PREFIX . "_documents AS doc
		LEFT JOIN
			" . PREFIX . "_navigation_items AS nav2 ON nav2.Id = nav.parent_id
		WHERE nav.navi_item_status = 1
			AND nav.navi_id = " . $navi_id . "
			AND doc.Id = " . $doc_active_id . "
			AND (
				nav.navi_item_link = 'index.php?id=" . $doc_active_id . "'" . 
				$sql_doc_active_alias . "
				OR nav.Id = doc.document_linked_navi_id
			)
	")->GetCell();

	$navi_active = @explode(';',$navi_active);

	// готовим 2 переменные с путём
	if ($navi_active[0]) $navi_active_way = @explode(',',$navi_active[0]);

	$navi_active_way[] = '0';

	$navi_active_way_str = implode(',',$navi_active_way);

	// текущий уровень
	$navi_active_level = (int)max(@explode(',', (isset($navi_active[1]) ? (int)$navi_active[1] : 0)))+1;

	// текущий id
	$navi_active_id = (isset($navi_active[2]) ? (int)$navi_active[2] : 0);

	// если просят вывести какие-то конкретные уровни:
	$sql_navi_level = '';
	$sql_navi_active = '';
	if($navi_print_level)
	{
		$sql_navi_level = ' AND navi_item_level IN (' . $navi_print_level . ') ';
		$sql_navi_active = ' AND parent_id IN(' . $navi_active_way_str . ') ';
	}
	// обычное использование навигации
	else
	{
		switch ($navi_menu->navi_expand_ext)
		{
			// все уровни
			case 1:
				$navi_parent = 0;
				break;

			// текущий и родительский уровни
			case 0:
				$sql_navi_active = ' AND parent_id IN(' . $navi_active_way_str . ') ';
				$navi_parent = 0;
				break;

			// только текущий уровень
			case 2:
				$sql_navi_level = ' AND navi_item_level = ' . $navi_active_level . ' ';
				$navi_parent = $navi_active_id;
				break;
		}
	}

	// запрос пунктов меню
	$sql_navi_items = $AVE_DB->Query("
		SELECT *
		FROM " . PREFIX . "_navigation_items
		WHERE navi_item_status = '1'
		AND navi_id = '" . $navi_id . "'" .
		$sql_navi_level .
		$sql_navi_active . "
		ORDER BY navi_item_position ASC
	");

	$navi_items = array();

	while ($row_navi_items = $sql_navi_items->FetchAssocArray())
	{
		$navi_items[$row_navi_items['parent_id']][] = $row_navi_items;
	}
	if($navi_print_level)
	{
		$keys = array_keys($navi_items);
		$navi_parent = $keys[0];
	}

	// Парсим теги в шаблонах пунктов
	$navi_item_tpl = array(
		1 =>  array(
			'inactive'	=> $navi_menu->navi_level1,
			'active'	=> $navi_menu->navi_level1active
		),
		2 =>  array(
			'inactive'	=> $navi_menu->navi_level2,
			'active'	=> $navi_menu->navi_level2active
		),
		3 =>  array(
			'inactive'	=> $navi_menu->navi_level3,
			'active'	=> $navi_menu->navi_level3active
		)
	);

	// запускаем рекурсивную сборку навигации
	if ($navi_items) $navi = printNavi($navi_menu,$navi_items,$navi_active_way,$navi_item_tpl,$navi_parent);

	// преобразуем все ссылке в коде
	$navi = rewrite_link($navi);
	// удаляем переводы строк и табуляции
	$navi = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $navi);
	$navi = str_replace(array("\n","\r"),'',$navi);

	$gen_time = microtime()-$gen_time;
	$GLOBALS['block_generate'][] = array('NAVIGATION_'.$navi_id=>$gen_time);

	return $navi;
}


/**
 * Рекурсивная функция для формирования меню навигации
 *
 * @param object	$navi_menu меню (шаблоны, параметры)
 * @param array		$navi_items (пункты по родителям)
 * @param array		$navi_active_way ("активный путь")
 * @param array		$navi_item_tpl (шаблоны пунктов)
 * @param int		$parent (исследуемый родитель, изначально 0 - верхний уровень)
 * @return string	$navi - готовый код навигации
 */
function printNavi($navi_menu,$navi_items,$navi_active_way,$navi_item_tpl,$parent=0)
{
	// выясняем уровень
	$navi_item_level = $navi_items[$parent][0]['navi_item_level'];

	// собираем каждый пункт в данном родителе -> в переменной $item
	
	foreach ($navi_items[$parent] as $row)
	{
		// Проверяем пункт меню на принадлежность к "активному пути" и выбираем шаблон
		$item = (in_array($row['Id'], $navi_active_way)) ? $navi_item_tpl[$navi_item_level]['active'] : $navi_item_tpl[$navi_item_level]['inactive'];

		################### ПАРСИМ ТЕГИ ###################
		// id
		@$item = str_replace('[tag:linkid]', $row['Id'], $item);
		// название
		@$item = str_replace('[tag:linkname]', $row['title'], $item);
		// ссылка
		if (strpos($row['navi_item_link'], 'module=') === false && start_with('index.php?', $row['navi_item_link']))
		{
			$item = str_replace('[tag:link]', $row['navi_item_link'] . "&amp;doc=" . ((!$row['document_alias']) ? prepare_url($row['title']) : $row['document_alias']), $item);
			$item = str_ireplace('"//"','"/"',str_ireplace('///','/',rewrite_link($item)));
		}
		else
		{
			$item = str_replace('[tag:link]', $row['navi_item_link'], $item);
			if (start_with('www.', $row['navi_item_link'])) $item = str_replace('www.', 'http://www.', $item);
		}
		// target
		$item = str_replace('[tag:target]', (empty($row['navi_item_target']) ? '_self' : $row['navi_item_target']), $item);
		// описание
		@$item = str_replace('[tag:desc]', stripslashes($row['navi_item_desc']), $item);
		// изображение
		@$item = str_replace('[tag:img]', stripslashes($row['navi_item_Img']), $item);

		if ($row['navi_item_Img'] != ''){
			@$img = explode(".", $row['navi_item_Img']);
			@$row['Img_act'] = $img[0]."_act.".$img[1];
			@$item = str_replace('[tag:img_act]', stripslashes($row['Img_act']), $item);
		}
		if ($row['navi_item_Img_id'] != ''){
			@$item = str_replace('[tag:img_id]', stripslashes($row['navi_item_Img_id']), $item);
		}

		################### /ПАРСИМ ТЕГИ ##################

		// Определяем тег для вставки следующего уровня
		switch ($navi_item_level)
		{
			case 1 :
				$tag = '[tag:level:2]';
				break;
			case 2 :
				$tag = '[tag:level:3]';
				break;

			default:
				$tag = '';
		}

		// Если есть подуровень, то заново запускаем для него функцию и вставляем вместо тега
		if (!empty($navi_items[$row['Id']]))
		{
			$item_sublevel = printNavi($navi_menu,$navi_items,$navi_active_way,$navi_item_tpl,$row['Id']);
			$item = @str_replace($tag,$item_sublevel,$item);
		}
		// Если нет подуровня, то удаляем тег
		else $item = @str_replace(@$tag,'',$item);

		// Подставляем в переменную навигации готовый пункт
		if (empty($navi)) $navi = '';
		$navi .= $item;
	}

	// Вставляем все пункты уровня в шаблон уровня
	switch ($navi_item_level)
	{
		case 1 :
			$navi = str_replace("[tag:content]",$navi, $navi_menu->navi_level1begin);
			break;
		case 2 :
			$navi = str_replace("[tag:content]",$navi, $navi_menu->navi_level2begin);
			break;
		case 3 :
			$navi = str_replace("[tag:content]",$navi, $navi_menu->navi_level3begin);
			break;
	}

	// Возвращаем сформированный уровень

	return $navi;
}


/**
 * Возвращает меню навигации
 *
 * @param int $id идентификатор меню навигации
 * @return string|mixed объект с навигацией по id, либо массив всех навигаций
 */
function get_navigations($id = null)
{
	global $AVE_DB;

	static $navigations = null;

	if ($navigations == null)
	{
		$navigations = array();

		$sql = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_navigation");

		while ($row = $sql->FetchRow())
		{
			$row->navi_user_group = explode(',', $row->navi_user_group);
			$navigations[$row->id] = $row;
		}
	}

	if ($id) return $navigations[$id];
	else return $navigations;
}


/**
 * Проверка прав доступа к навигации по группе пользователя
 *
 * @param int $id идентификатор меню навигации
 * @return boolean
 */
function check_navi_permission($id)
{
	$navigation = get_navigations($id);

	if (empty($navigation->navi_user_group)) return false;

	if (!defined('UGROUP')) define('UGROUP', 2);
	if (!in_array(UGROUP, $navigation->navi_user_group)) return false;

	return true;
}
?>