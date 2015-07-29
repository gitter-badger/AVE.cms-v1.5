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
 * @todo
 * <pre>
 * добавить в настройки выбор срока доставки означающий "самовывоз" для delivery
 * добавить в настройки выбор доставки для определения стоимости локальной доставки - local_delivery_cost
 * добавить в карточку товара поле страны происхождения - country_of_origin
 * добавить в карточку товара и категории стоимость переходов - bid, cbid,
 * валюты перенести в настройки
 * </pre>
 */

function db_connect()
{
	@require('./db.config.php');

	if (! isset($config)) die;

	if (! @mysql_select_db($config['dbname'], @mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']))) die;

	if (! defined('PREFIX')) define('PREFIX', $config['dbpref']);

	@mysql_query("SET NAMES 'cp1251'");
}
db_connect();

// общая информация о магазине и валюты
$sql = mysql_query("
	SELECT
		status,
		Waehrung,
		site_name,
		company_name,
		custom,
		delivery,
		delivery_local,
		downloadable,
		track_label
	FROM
		" . PREFIX . "_settings,
		" . PREFIX . "_modul_shop
");
list($shop_active, $shop_currency_id, $shop_name, $shop_company, $custom, $delivery, $delivery_local, $downloadable, $track_label) = mysql_fetch_row($sql);

if ($shop_active != 1) exit;

function is_ssl()
{
	if (isset($_SERVER['HTTPS']))
	{
		if ('on' == strtolower($_SERVER['HTTPS'])) return true;
		if ('1' == $_SERVER['HTTPS']) return true;
	}
	elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT']))
	{
		return true;
	}

	return false;
}

function set_host()
{
	if (isset($_SERVER['HTTP_HOST']))
	{
		// Все символы $_SERVER['HTTP_HOST'] приводим к строчным и проверяем
		// на наличие запрещённых символов в соответствии с RFC 952 и RFC 2181.
		$_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
		if (!preg_match('/^\[?(?:[a-z0-9-:\]_]+\.?)+$/', $_SERVER['HTTP_HOST']))
		{
			// $_SERVER['HTTP_HOST'] не соответствует спецификациям.
			// Возможно попытка взлома, даём отлуп статусом 400.
			header('HTTP/1.1 400 Bad Request');
			exit;
		}
	}
	else
	{
		$_SERVER['HTTP_HOST'] = '';
	}

	$ssl = is_ssl();
	$shema = ($ssl) ? 'https://' : 'http://';
	$host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
	$port = ($_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' || $ssl) ? '' : ':' . $_SERVER['SERVER_PORT'];
	list($abs_path) = explode('/inc', (!strstr($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) && (@php_sapi_name() == 'cgi')) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

	define('HOME_URL', $shema . $host . $port . $abs_path . '/');
}
set_host();

@require('../modules/shop/funcs/func.rewrite.php');
@require('../class/class.yml.php');

$AVE_YML = new AVE_YML('windows-1251');

// информация о магазине
$shop_url = HOME_URL . shopRewrite('index.php?module=shop');
$AVE_YML->ymlElementShopSet($shop_name, $shop_company, $shop_url);

// вылюты
$AVE_YML->ymlElementCurrencySet($shop_currency_id, 1);
$AVE_YML->ymlElementCurrencySet('USD', 'CBRF', 3);
$AVE_YML->ymlElementCurrencySet('UAH', 'NBU', 1);

// категории
$sql = mysql_query("
	SELECT
		Id,
		parent_id,
		KatName
	FROM " . PREFIX . "_modul_shop_kategorie
");

while (list($cat_id, $cat_parent_id, $cat_name) = mysql_fetch_row($sql))
{
	if ($cat_parent_id)
	{
		$AVE_YML->ymlElementCategorySet($cat_name, $cat_id, $cat_parent_id);
	}
	else
	{
		$AVE_YML->ymlElementCategorySet($cat_name, $cat_id);
	}
}

// товарные предложения
//if ($downloadable) ? "IF(VersandZeitId == " . $downloadable . ", 'true', 'false') AS downloadable," : "";
$sql = mysql_query("
	SELECT
		art.Id AS url,
		Preis AS price,
		'" . $shop_currency_id . "' AS currencyId,
		KatId AS categoryId,
		art.Bild AS picture,
		'true' AS delivery,
		ArtName AS name,
		vend.Name AS vendor,
		ArtNr AS vendorCode,
		TextLang AS description,
		" . ($custom ? "IF(VersandZeitId = " . $custom . ", 0, 1)" : 1) . " AS available,
		" . ($downloadable ? "IF(VersandZeitId = " . $downloadable . ", 'true', 'false') AS downloadable," : '') . "
		parent_id
	FROM
		" . PREFIX . "_modul_shop_artikel AS art
	LEFT JOIN
		" . PREFIX . "_modul_shop_hersteller AS vend
			ON vend.Id = Hersteller
	LEFT JOIN
		" . PREFIX . "_modul_shop_kategorie AS cat
			ON cat.Id = KatId
	WHERE
		status = 1
	AND
		(Lager > 0" . ($custom ? " OR VersandZeitId = " . $custom : '') . ")
	AND
		Erschienen <= " . time() . "
	AND
		Preis != '0.00'
");

while ($row = mysql_fetch_assoc($sql))
{
	$offer_id = $row['url'];
	$row['url'] = HOME_URL
		. shopRewrite("index.php?module=shop&amp;action=product_detail"
			. "&amp;product_id=" . $offer_id
			. "&amp;categ=" . $row['categoryId']
			. "&amp;navop=" . (0 == $row['parent_id'] ? $row['categoryId'] : $row['parent_id']))
		. ($track_label ? "#ym" : ""
	);

	if (empty($row['picture']))
	{
		unset($row['picture']);
	}
	else
	{
		$row['picture'] = HOME_URL . 'modules/shop/uploads/' . $row['picture'];
	}

	$offer_available = $row['available'];

	unset($row['available'], $row['parent_id']);

	$AVE_YML->ymlElementOfferSet($offer_id, $row, $offer_available);
}

mysql_free_result($sql);

header('Content-type: text/xml');

print_r($AVE_YML->ymlGet());

?>