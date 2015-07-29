<?php
ob_start();
ob_implicit_flush(0);

define('BASE_DIR', str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));

require(BASE_DIR . '/inc/init.php');
if (!isset($_SESSION['user_id']))
{
	header('Location:index.php');
	exit;
}

if  ($_SESSION['use_editor']!= 2)
{
	header('Location:index.php');
	exit;
}
include_once(dirname(dirname(__FILE__)) . "/config.php");

$root=WEBSITEROOT_LOCALPATH;
$newfolder = $root . $_POST["folder"];

$parent = dirname($newfolder);

if(!is_writable($parent)) {
	echo "Write permission required";
	exit();
}

if(!file_exists ($newfolder)) {
	//create the folder
	mkdir($newfolder);
} else {
	echo "Folder already exists.";
}
?>