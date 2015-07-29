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
 * Обработка тега системного блока
 *
 * @param int $id идентификатор системного блока
 */
function parse_sysblock($id)
{
	global $AVE_DB, $AVE_Core;

	$gen_time = microtime();

	if (is_array($id)) $id = $id[1];

	if (is_numeric($id))
	{
		$eval_sysblock = false;

		if($id < 0){
			$id = abs($id);
			$eval_sysblock = true;
		}

		$cache_file = BASE_DIR.'/cache/sql/sysblock-'.$id.'.cache';

		if(!file_exists(dirname($cache_file))) mkdir(dirname($cache_file),0766,true);

		if(file_exists($cache_file)) {
			$return = file_get_contents($cache_file);
		} else {
			$return = $AVE_DB->Query("
				SELECT sysblock_text
				FROM " . PREFIX . "_sysblocks
				WHERE id = '" . $id . "'
				LIMIT 1
			")->GetCell();
			file_put_contents($cache_file,$return);
		}

		// парсим теги
		$search = array(
			'[tag:mediapath]',
			'[tag:path]',
			'[tag:docid]'
		);

		$replace = array(
			ABS_PATH . 'templates/' . ((defined('THEME_FOLDER') === false) ? DEFAULT_THEME_FOLDER : THEME_FOLDER) . '/',
			ABS_PATH,
			get_current_document_id()
		);

		$return = str_replace($search, $replace, $return);

		$return = preg_replace_callback('/\[tag:home]/', 'get_home_link', $return);
		$return = preg_replace_callback('/\[tag:breadcrumb]/', 'get_breadcrumb', $return);
		$return = preg_replace_callback('/\[tag:request:(\d+)\]/', 'request_parse', $return);

		if (isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
			// парсим теги полей документа в шаблоне рубрики
			$return = preg_replace_callback('/\[tag:fld:([a-zA-Z0-9-_]+)\]/', 'document_get_field', $return);
			$return = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $return);
		}

		if($eval_sysblock) $return = eval2var('?'.'>' . $return . '<'.'?');

		$gen_time = microtime()-$gen_time;
		$GLOBALS['block_generate'][] = array('SYSBLOCK_'.$id=>$gen_time);

		return $return;
	}
}
?>