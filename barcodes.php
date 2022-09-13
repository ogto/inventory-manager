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

$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

echo '<ul class="barcode">';
while($row = $db->fetch_array_assoc($results)) {
	for($i = 0; $i < $db->num_fields($results); $i++) {
	    $col_name = $db->field_name($results, $i);
	    $db[$count][$col_name] = $row[$col_name];
	}

	$code = str_pad($db[$count]['id'], 8, '0', STR_PAD_LEFT);
	$label = $db[$count]['ProductLabel'];

	echo '<li><div>';
	echo $generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 50);
	echo "<div>$code</div>";
	echo "<div>$label</div>";
	echo '</div></li>';

	$count++;

}
echo '</ul>';

require_once('./inc/footer.php');
