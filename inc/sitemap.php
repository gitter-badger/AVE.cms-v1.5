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

header("Content-type: text/xml");
@date_default_timezone_set('Europe/Moscow');

define('START_MICROTIME', microtime());

define('BASE_DIR', str_replace("\\", "/", rtrim($_SERVER['DOCUMENT_ROOT'],'/')));

define('ABS_PATH', str_ireplace(BASE_DIR,'/',str_replace("\\", "/", dirname(dirname(__FILE__)))));

if (! @filesize(BASE_DIR . '/inc/db.config.php'))
{
	header('Location: Location:install/index.php');
	exit;
}

if(substr($_SERVER['REQUEST_URI'], 0, strlen('/index.php?')) != '/index.php?')
{
	$_SERVER['REQUEST_URI'] = str_ireplace('_','-',$_SERVER['REQUEST_URI']);
}

require_once(BASE_DIR . '/inc/init.php');

$domain = 'http://'.$_SERVER['SERVER_NAME'];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?php echo $domain . '/'; ?></loc>
		<lastmod><?php echo date("Y-m-d"); ?></lastmod>
		<changefreq>always</changefreq>
	</url>
<?php
	$sql = "
			SELECT
				doc.Id,
				doc.document_alias,
				doc.document_changed
			FROM " . PREFIX . "_documents doc
			left join " . PREFIX . "_rubrics rub
				on rub.Id=doc.rubric_id
			left join " . PREFIX . "_rubric_permissions rubperm
				on rubperm.rubric_id=doc.rubric_id
			where
				rub.rubric_template NOT LIKE ''
				AND doc.document_status = 1
				AND doc.document_deleted = 1
				AND doc.document_expire > UNIX_TIMESTAMP()
				AND doc.Id != " . PAGE_NOT_FOUND_ID . "
				AND (document_meta_robots NOT LIKE '%noindex%' or document_meta_robots NOT LIKE '%nofollow%')
				AND (rubperm.user_group_id = 2 AND rubperm.rubric_permission LIKE '%docread%')
			ORDER BY document_changed DESC
	";

	$res = $AVE_DB->Query($sql);

	while($row = $res->FetchAssocArray()):
?>
	<url>
		<loc><?php echo $domain . ABS_PATH . $row['document_alias'] . URL_SUFF;?></loc>
		<lastmod><?php echo date("Y-m-d",$row["document_changed"]); ?></lastmod>
		<changefreq>weekly</changefreq>
	</url>
<?php endwhile; ?>
</urlset>