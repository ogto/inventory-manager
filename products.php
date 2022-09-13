<?php
use phpGrid\C_DataGrid;

include_once("phpGrid/conf.php");
include_once('inc/head.php');
?>

<h1>My Inventory Manager</h1>

<?php
$_GET['currentPage'] = 'products';
include_once('inc/menu.php');
?>

<?php
error_reporting(E_ALL&~E_WARNING);
$dgProd = new \C_DataGrid('SELECT * FROM products', 'id', 'products');
$dgProd->set_col_hidden('id', false);
$dgProd->enable_autowidth(true)->set_dimension('auto', '200px')->set_pagesize(100);

$dgProd->set_col_title('ProductName', 'Name');
$dgProd->set_col_title('PartNumber', 'Part Number');
$dgProd->set_col_title('ProductLabel', 'Label');
$dgProd->set_col_title('StartingInventory', 'Starting Inventory');
$dgProd->set_col_title('InventoryReceived', 'Inventory Received');
$dgProd->set_col_title('InventoryShipped', 'Inventory Shipped');
$dgProd->set_col_title('InventoryOnHand', 'Inventory On Hand');
$dgProd->set_col_title('MinimumRequired', 'Minimum Required');

$dgProd->set_col_format('StartingInventory', 'integer', array('thousandsSeparator'=>',', 'defaultValue'=>'0'));
$dgProd->set_col_format('InventoryReceived', 'integer', array('thousandsSeparator'=>',', 'defaultValue'=>'0'));
$dgProd->set_col_format('InventoryShipped', 'integer', array('thousandsSeparator'=>',', 'defaultValue'=>'0'));
$dgProd->set_col_format('InventoryOnHand', 'integer', array('thousandsSeparator'=>',', 'defaultValue'=>'0'));
$dgProd->set_col_format('MinimumRequired', 'integer', array('thousandsSeparator'=>',', 'defaultValue'=>'0'));

$dgProd->set_col_format('InventoryOnHand', 'CELL', array("condition"=>"lt",
                                                  "value"=>"1",
                                                  "css"=> array("color"=>"red","background-color"=>"#DCDCDC")));

$dgProd->set_col_property('StartingInventory', array('classes'=>'number-columns'));
$dgProd->set_col_property('InventoryReceived', array('classes'=>'number-columns'));
$dgProd->set_col_property('InventoryShipped', array('classes'=>'number-columns'));
$dgProd->set_col_property('InventoryOnHand', array('classes'=>'number-columns'));
$dgProd->set_col_property('MinimumRequired', array('classes'=>'number-columns'));

$onGridLoadComplete = <<<ONGRIDLOADCOMPLETE
function(status, rowid)
{
    var ids = jQuery("#products").jqGrid('getDataIDs');
    for (var i = 0; i < ids.length; i++)
    {
        var rowId = ids[i];
        var rowData = jQuery('#products').jqGrid ('getRowData', rowId);

        var inventoryOnHand = $("#products").jqGrid("getCell", rowId, "InventoryOnHand");
        var minimumRequired = $("#products").jqGrid("getCell", rowId, "MinimumRequired");

        // compare two dates and set custom display in another field "status" 
        console.log(inventoryOnHand + " | " + minimumRequired);
        if(parseInt(inventoryOnHand) < parseInt(minimumRequired)){
            
            $("#products").jqGrid("setCell", rowId, "PartNumber", '', {'background-color':'gold'}); 
                
        }
    }

}
ONGRIDLOADCOMPLETE;
$dgProd->add_event("jqGridLoadComplete", $onGridLoadComplete);
$dgProd->enable_edit('FORM');

// Purchases detail grid
// $dgPur = new \C_DataGrid('SELECT id, PurchaseDate, ProductId, NumberReceived, SupplierId FROM purchases', 'id', 'purchases');
// $dgPur->set_col_hidden('id', false)->set_caption('Incoming Purchases');
// $dgPur->set_col_edittype('ProductId', 'select', "select id, ProductLabel from products");
// $dgPur->set_col_edittype('SupplierId', 'select', "select id, supplier from suppliers");
// $dgPur->set_dimension('800px');

$dgPur = new \C_DataGrid('SELECT id, PurchaseDate, ProductId, NumberReceived, SupplierId FROM purchases', 'id', 'purchases');
$dgPur->set_col_hidden('id', false);

$dgPur->set_col_title('PurchaseDate', 'Date of Purchase');
$dgPur->set_col_title('ProductId', 'Product');
$dgPur->set_col_title('NumberReceived', 'Number Received');
$dgPur->set_col_title('SupplierId', 'Supplier');

$dgPur->set_col_edittype('ProductId', 'autocomplete', "select id, concat(lpad(id, 8, '0'), ' | ', ProductLabel) from products");
$dgPur->set_col_edittype('SupplierId', 'autocomplete', "select id, supplier from suppliers");

// $dgPur->enable_edit('FORM');
$dgPur->set_pagesize(100);

$dgPur->set_col_width('PurchaseDate', '50px');
$dgPur->set_col_width('NumberReceived', '35px');

$dgPur -> set_group_properties('ProductId', false, true, true, false);
$dgPur -> set_group_summary('NumberReceived','sum');

$dgPur->enable_autowidth(true);

$dgPur->enable_edit('FORM');
$dgPur->display();

// Orders detail grid
// $dgOrd = new \C_DataGrid('SELECT id, OrderDate, ProductId, NumberShipped, First, Last FROM orders', 'id', 'orders');
// $dgOrd->set_sortname('OrderDate', 'DESC')->set_caption('Outgoing Orders');
// $dgOrd->set_col_hidden('id', false);
// $dgOrd->set_col_edittype('ProductId', 'select', "select id, ProductLabel from products");
// $dgOrd->set_dimension('800px');

$dgOrd = new \C_DataGrid('SELECT id, OrderDate, ProductId, NumberShipped, First, Last FROM orders', 'id', 'orders');
$dgOrd->set_sortname('OrderDate', 'DESC');
$dgOrd->set_col_hidden('id', false);

$dgOrd->set_col_title('OrderDate', 'Order Date');
$dgOrd->set_col_title('ProductId', 'Product');
$dgOrd->set_col_title('NumberShipped', 'Number Shipped');

$dgOrd->set_col_edittype('ProductId', 'autocomplete', "select id, ProductLabel from products");

// $dgOrd->enable_edit('FORM');
$dgOrd->set_pagesize(100);

$dgOrd->set_col_width('OrderDate', '30px');
$dgOrd->set_col_width('NumberShipped', '35px');
$dgOrd->set_col_width('First', '20px');
$dgOrd->set_col_width('Last', '20px');

$dgOrd->set_grid_method('setGroupHeaders', array(
                                array('useColSpanStyle'=>true),
                                'groupHeaders'=>array(
                                        array('startColumnName'=>'First',
                                              'numberOfColumns'=>2,
                                              'titleText'=>'Customer Name') )));

$dgOrd->enable_autowidth(true);
$dgOrd->enable_edit('FORM');

$dgOrd->display();

// $dgProd->set_masterdetail($dgPur, 'ProductId', 'id');
// $dgProd->set_masterdetail($dgOrd, 'ProductId', 'id');
// $dgProd->display();
?>

<span style="background-color:gold">______</span> -- Indicating inventory that needs reorder.<br />
<span style="background-color:#DCDCDC">______</span> -- Negative inventory on hand!

<style>
.number-columns{
	font-weight: 700 !important;
}
</style>


<?php
include_once('inc/footer.php');
?>
