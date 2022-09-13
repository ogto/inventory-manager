<?php
use phpGrid\C_DataGrid;

require_once("./phpGrid/conf.php");
require_once('./inc/head.php');
?>

<h1>My Inventory Manager Reports</h1>

<?php
$_GET['currentPage'] = 'reports';
require_once('./inc/menu.php');
?>

<br />
<div id="label_info"></div>


<?php
$dgProd = new \C_DataGrid('SELECT id, ProductLabel, InventoryReceived FROM products', 'id', 'products');
$dgProd->set_col_hidden('id', false);
$dgProd->set_dimension('auto', 'auto')->set_pagesize(100);
$onGridLoadComplete = <<<ONGRIDLOADCOMPLETE
function(status, rowid)
{
	var dataX = [];
	var dataY = [];

	d1 = $('#products').jqGrid('getCol', 'ProductLabel', false);
	d2 = $('#products').jqGrid('getCol', 'InventoryReceived', false);
	
	npoints = d1.length;
	for(var i=0; i < npoints; i++){
		dataX[i] = [i+1, d1[i]];
		dataY[i] = [i+1, parseInt(d2[i])];
	}

    var pieData = [];
    for(var j=0; j < dataX.length; j++)
    {
        pieData.push([dataX[j][1], dataY[j][1]]);
    }
    console.log(pieData);
    _PieChart.series[0].data = pieData;
    _PieChart.replot({resetAxes:true});
}
ONGRIDLOADCOMPLETE;
$dgProd->add_event("jqGridLoadComplete", $onGridLoadComplete);
$dgProd->display();
?>

<style>
div#resizable,
div#gbox_products{
  float: left;
}
#label_info{
  color:green;
}
</style>

<?php
require_once('./inc/footer.php');
?>