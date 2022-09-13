<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_GET['currentPage'] = 'barcodes';

// require_once dirname(__DIR__) . '/inventory-manager-master/vendor/autoload.php';
require_once('./inc/head.php');
?>

<h1>My Inventory Manager</h1>

<?
require_once('./inc/menu.php');
require_once("./phpGrid/conf.php");
use phpGrid\C_DataGrid;

$db = new \C_DataGrid('SELECT * FROM products');
$db->display();
$count = 0;

// $generator = new Picqer\Barcode\BarcodeGeneratorHTML();

require_once('./inc/footer.php');
