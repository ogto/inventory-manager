<div id="menu">
    <ul>
        <li><a href="products.php" <?php if($_GET['currentPage'] == 'products' ?? null) echo 'class="active"'; ?>>Current Inventory</a></li>
        <li><a href="incoming-purchases.php" <?php if($_GET['currentPage'] == 'incoming' ?? null) echo 'class="active"'; ?>>Incoming Purchases</a></li>
        <li><a href="outgoing-order.php" <?php if($_GET['currentPage'] == 'outgoing' ?? null) echo 'class="active"'; ?>>Outgoing Orders</a></li>
        <li><a href="reports.php" <?php if($_GET['currentPage'] == 'reports' ?? null) echo 'class="active"'; ?>>Reports</a></li>
        <li><a href="barcodes.php" <?php if($_GET['currentPage'] == 'barcodes' ?? null) echo 'class="active"'; ?>>Barcodes</a></li>
    </ul>
</div>
