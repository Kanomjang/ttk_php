<?php
$class_active_01 = "";
$class_active_02 = "";
$class_active_03 = "";
$class_active_04 = "";
$class_active_05 = "";
$class_active_06 = "";
$class_active_07 = "";
$class_active_08 = "";
$class_active_09 = "";
$class_active_10 = "";
$class_active_11 = "";
$class_active_12 = "";
$class_active_13 = "";
$class_active_14 = "";
if ($position_active == '1') {
  $class_active_01 = "class=\"active\"";
} elseif ($position_active == '2') {
  $class_active_02 = "class=\"active\"";
} elseif ($position_active == '3') {
  $class_active_03 = "class=\"active\"";
} elseif ($position_active == '4') {
  $class_active_04 = "class=\"active\"";
} elseif ($position_active == '5') {
  $class_active_05 = "class=\"active\"";
} elseif ($position_active == '6') {
  $class_active_06 = "class=\"active\"";
} elseif ($position_active == '7') {
  $class_active_07 = "class=\"active\"";
} elseif ($position_active == '8') {
  $class_active_08 = "class=\"active\"";
} elseif ($position_active == '9') {
  $class_active_09 = "class=\"active\"";
} elseif ($position_active == '10') {
  $class_active_10 = "class=\"active\"";
} elseif ($position_active == '11') {
  $class_active_11 = "class=\"active\"";
} elseif ($position_active == '12') {
  $class_active_12 = "class=\"active\"";
} elseif ($position_active == '13') {
  $class_active_13 = "class=\"active\"";
} elseif ($position_active == '14') {
  $class_active_14 = "class=\"active\"";
} else {
  $class_active_00 = "class=\"active\"";
}
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active treeview menu-open">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li <?php echo $class_active_01 ?>><a href="./print_asset_edu.php"><i class="fa fa-circle-o"></i>
                            งานทรัพย์สิน</a></li>
                    <li <?php echo $class_active_02 ?>><a href="./building_location.php"><i class="fa fa-circle-o"></i>
                            งานอาคาร/สถานที่</a></li>
                    <li <?php echo $class_active_03 ?>><a href="./dept_org.php"><i class="fa fa-circle-o"></i>
                            โครงสร้างองค์กร</a></li>
                    <li <?php echo $class_active_04 ?>><a href="./dept_org.php"><i class="fa fa-circle-o"></i>
                            จัดการบุคลากร</a></li>
                    <li <?php echo $class_active_05 ?>><a href="./com_accounting.php"><i class="fa fa-circle-o"></i>
                            บัญชีแยกประเภท</a></li>
                    <li <?php echo $class_active_06 ?>><a href="./com_accounts_receivable.php"><i
                                class="fa fa-circle-o"></i> ลูกหนี้การค้า</a></li>
                    <li <?php echo $class_active_07 ?>><a href="./com_accounts_payable.php"><i
                                class="fa fa-circle-o"></i> เจ้าหนี้การค้า</a></li>
                    <li <?php echo $class_active_08 ?>><a href="./com_raw_material_details.php"><i
                                class="fa fa-circle-o"></i> รายละเอียดวัตถุดิบ</a></li>
                    <li <?php echo $class_active_09 ?>><a href="./com_raw_materials.php"><i class="fa fa-circle-o"></i>
                            คลังวัตถุดิบ</a></li>
                    <li <?php echo $class_active_10 ?>><a href="./com_purchasing_jobs.php"><i
                                class="fa fa-circle-o"></i>
                            ใบขอซื้อ</a></li>
                    <li <?php echo $class_active_12 ?>><a href="./com_check_purchase_order.php"><i
                                class="fa fa-circle-o"></i> ตรวจสอบใบสั่งซื้อ</a></li>
                    <li <?php echo $class_active_11 ?>><a href="./com_document_approval.php"><i
                                class="fa fa-circle-o"></i> อนุมัติใบขอซื้อ/ใบสั่งซื้อ</a></li>
                    <li <?php echo $class_active_13 ?>><a href="./com_purchase_order_report.php"><i
                                class="fa fa-circle-o"></i> ใบขอซื้อ/ใบสั่งซื้อ</a></li>
                    <li <?php echo $class_active_14 ?>><a href="./com_check_for_products.php"><i
                                class="fa fa-circle-o"></i> ตรวจรับสินค้า</a></li>
                    <li <?php echo $class_active_100 ?>><a href="./com_sale_report.php"><i class="fa fa-circle-o"></i>
                            Sele Daily Report</a></li>
                </ul>
            </li>
    </section>
    <!-- /.sidebar -->
</aside>