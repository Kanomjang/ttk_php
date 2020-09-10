<?php
include("../lib_org.php");
//if($_SESSION['id_emp_use']){ 
$position_active='1';
$id_emp_use = $_SESSION['id_emp_use'];
if ($_POST["add_asset"]) {
  $id_gdept = $_POST['id_gdept'];
  $id_ag = $_POST['id_ag'];
  $id_at = $_POST['id_at'];
  $id_at1 = $_POST['id_at1'];
  $id_loc = $_POST['id_loc'];
  $floor_location = $_POST['floor_location'];
  $id_loc_master = $_POST['id_loc_master'];
  $year_asset = $_POST['year_asset'];
  $date_order = $_POST['date_order'];
  $date_dept = $_POST['date_dept'];
  $date_location = $_POST['date_location'];
  $status_asset_type = $_POST['status_asset_type'];
  $name_asset = $_POST['name_asset'];
  $comment = $_POST['comment'];
  $cost_price = $_POST['cost_price'];
  $carcass_price = $_POST['carcass_price'];
  $count_num = $_POST['count_num'];
  $id_at1 = explode("-", $id_at1);
  add_asset($id_gdept, $id_ag, $id_at1[1], $id_loc, $id_loc_master, $year_asset, $date_order, $date_dept, $date_location, $name_asset, $cost_price, $count_num, $status_asset_type, $comment, $carcass_price, $id_at1[0]);
  header("location: http://localhost/main_php/print_asset_edu.php");
  exit(0);
}
if ($_POST['save_asset']) {
  $id_ae = $_POST['id_ae'];
  $id_gdept = $_POST['id_gdept'];
  $id_loc = $_POST['id_loc'];
  $floor_location = $_POST['floor_location'];
  $id_loc_master = $_POST['id_loc_master'];
  $date_order = $_POST['date_order'];
  $date_dept = $_POST['date_dept'];
  $date_location = $_POST['date_location'];
  $status_asset_type = $_POST['status_asset_type'];
  $name_asset = $_POST['name_asset'];
  $comment = $_POST['comment'];
  $cost_price = $_POST['cost_price'];
  $carcass_price = $_POST['carcass_price'];
  $id_ae = base64_decode(base64_decode("$id_ae"));
#  print"$id_gdept, $id_loc, $id_loc_master, $date_order, $date_dept, $date_location, $name_asset, $cost_price, $id_ae, $status_asset_type, $comment, $carcass_price<br>";exit;
  save_asset($id_gdept, $id_loc, $id_loc_master, $date_order, $date_dept, $date_location, $name_asset, $cost_price, $id_ae, $status_asset_type, $comment, $carcass_price);
}
if ($_GET['delete_asset']) {
  $id_ae = $_GET['delete_asset'];
  $id_ae = base64_decode(base64_decode("$id_ae"));
  delete_asset($id_ae);
  	if (array_key_exists("closeme",$_GET)){
	?>
	<script  language="JavaScript">
	window.opener.location.reload(true);
	window.close();
	</script>
	<?php
	exit();
	}

}


$select_asset = select_asset();

#	print_asset_dept($select_asset,$id_emp_use);
#	exit;
//include("../content.php");

#-----------จบตัดเพจ-----------#
print_asset_dept($select_asset,$title_head_html,$position_active);

//}else{echo "โปรด Login ก่อน";}
#################################################
function count_asset()
{
  global $host, $user, $passwd, $database;
  $count_asset = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select count(asset_edu.id_ae) from asset_edu,asset_type,asset_group where asset_edu.id_at = asset_type.id_at and asset_group.id_ag = asset_type.id_ag";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_asset = $row["count(asset_edu.id_ae)"];
  }
  return $count_asset;
}
#################################################
function delete_asset($id_ae)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM asset_edu WHERE id_ae=$id_ae";
  mysqli_query($connect, $sql_query1);
  $sql_query2 = "DELETE FROM asset_location WHERE id_ae=$id_ae";
  mysqli_query($connect, $sql_query2);
  $sql_query3 = "DELETE FROM asset_dept WHERE id_ae=$id_ae";
  mysqli_query($connect, $sql_query3);
}
#################################################
function save_asset($id_gdept, $id_loc, $id_loc_master, $date_order, $date_dept, $date_location, $name_asset, $cost_price, $id_ae, $status_asset_type, $comment, $carcass_price)
{
  global $host, $user, $passwd, $database;
  $yyyymmdd_order = explode("/", $date_order);
  $yyyymmdd_dept = explode("/", $date_dept);
  $yyyymmdd_location = explode("/", $date_location);
  $date_order = "$yyyymmdd_order[2]-$yyyymmdd_order[0]-$yyyymmdd_order[1]";
  $date_dept = "$yyyymmdd_dept[2]-$yyyymmdd_dept[0]-$yyyymmdd_dept[1]";
  $date_location = "$yyyymmdd_location[2]-$yyyymmdd_location[0]-$yyyymmdd_location[1]";

  $check_id_gdept = check_id_gdept($id_ae);
  $check_id_loc_master = check_id_loc_master($id_ae);
  if ($check_id_gdept != $id_gdept) {
    insert_gdept($id_ae, $id_gdept, $date_dept);
  }
  if ($check_id_loc_master != $id_loc_master) {
    insert_loc_master($id_ae, $id_loc_master, $date_location);
  }

  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "update asset_edu set name_asset='$name_asset',cost_price='$cost_price',date_order='$date_order',status_asset_type='$status_asset_type',comment='$comment',carcass_price='$carcass_price' where id_ae='$id_ae'";
  #print"$sql_query <br>";exit;
  mysqli_query($connect, $sql_query);
  $select_id_al = select_id_al($id_ae);
  update_loc_master($select_id_al, $date_location);
  $select_id_ad = select_id_ad($id_ae);
  update_gdept($select_id_ad, $date_dept);
}
#################################################
function select_id_ad($id_ae)
{
  global $host, $user, $passwd, $database;
  $select_id_ad = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select id_ad from asset_dept where id_ae='$id_ae' order by id_ad DESC limit 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_ad = $row["id_ad"];
  }
  return $select_id_ad;
}
#################################################
function select_id_al($id_ae)
{
  global $host, $user, $passwd, $database;
  $select_id_al = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select id_al from asset_location where id_ae='$id_ae' order by id_al DESC limit 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_al = $row["id_al"];
  }
  return $select_id_al;
}
##############################
function update_loc_master($id_al, $date_location)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "update asset_location set date_location='$date_location' where id_al='$id_al'";
  mysqli_query($connect, $sql_query);
}
##############################
function update_gdept($id_ad, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "update asset_dept set date_dept='$date_dept' where id_ad='$id_ad'";
  mysqli_query($connect, $sql_query);
}
##############################
function insert_loc_master($id_ae, $id_loc_master, $date_location)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "insert into asset_location(id_ae,id_loc_master,date_location,id_use) values('$id_ae','$id_loc_master','$date_location','$id_emp_use')";
  mysqli_query($connect, $sql_query);
}
##############################
function insert_gdept($id_ae, $id_gdept, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "insert into asset_dept(id_ae,id_gdept,date_dept,id_use) values('$id_ae','$id_gdept','$date_dept','$id_emp_use')";
  mysqli_query($connect, $sql_query);
}
##############################
function check_id_loc_master($id_ae)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT id_loc_master FROM asset_location where id_ae='$id_ae' order by id_al DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_id_loc_master = $row['id_loc_master'];
  }
  return $check_id_loc_master;
}
##############################
function check_id_gdept($id_ae)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT id_gdept FROM asset_dept where id_ae='$id_ae' order by id_ad DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_id_gdept = $row['id_gdept'];
  }
  return $check_id_gdept;
}
#################################################

function add_asset($id_gdept, $id_ag, $id_at1, $id_loc, $id_loc_master, $year_asset, $date_order, $date_dept, $date_location, $name_asset, $cost_price, $count_num, $status_asset_type, $comment, $carcass_price, $id_at2)
{
  global $host, $user, $passwd, $database, $id_emp_use, $homepage;
  #include "../phpqrcode/qrlib.php";

  $code_year = substr($year_asset, 2);
  $code_id_ag = code_id_ag($id_ag);
  if (strlen($id_at1) == "1") {
    $code_id_at = "0$id_at1";
  } else {
    $code_id_at = "$id_at1";
  }
  $yyyymmdd_order = explode("/", $date_order);
  $yyyymmdd_dept = explode("/", $date_dept);
  $yyyymmdd_location = explode("/", $date_location);
  $yearasset = $year_asset - 543;
  $date_order = "$yyyymmdd_order[2]-$yyyymmdd_order[0]-$yyyymmdd_order[1]";
  $date_dept = "$yyyymmdd_dept[2]-$yyyymmdd_dept[0]-$yyyymmdd_dept[1]";
  $date_location = "$yyyymmdd_location[2]-$yyyymmdd_location[0]-$yyyymmdd_location[1]";
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT max(code_ad) FROM asset_edu WHERE id_at= '$id_at2'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $code_ad = $row["max(code_ad)"];
  }

  $id_at = select_id_at($id_ag, $id_at1);
  for ($i = 0; $i < $count_num; $i++) {
    $code_ad = $code_ad + 1;
    $CodeAd =  substr("00000" . $code_ad, -4, 4);
    $id_asset = "$code_id_ag$code_id_at$code_year$CodeAd";
    $sql_query = "insert into asset_edu(id_at,code_ad,id_asset,name_asset,year_asset,cost_price,date_order,status_asset_type,comment,carcass_price,id_use) values('$id_at','$code_ad','$id_asset','$name_asset','$yearasset','$cost_price','$date_order','$status_asset_type','$comment','$carcass_price','$id_emp_use')";
    mysqli_query($connect, $sql_query);

    $sql_query = "SELECT id_ae FROM asset_edu order by id_ae DESC limit 0,1";
    $shows = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($shows)) {
      $id_ae = $row["id_ae"];
    }
    if ($id_gdept) {
      $sql_query = "insert into asset_dept(id_ae,id_gdept,date_dept,id_use) values('$id_ae','$id_gdept','$date_dept','$id_emp_use')";
      mysqli_query($connect, $sql_query);
    }
    if ($id_loc_master) {
      $sql_query = "insert into asset_location(id_ae,id_loc_master,date_location,id_use) values('$id_ae','$id_loc_master','$date_location','$id_emp_use')";
      mysqli_query($connect, $sql_query);
    }
  }
}
#################################################
function select_id_at($id_ag, $id_at1)
{
  global $host, $user, $passwd, $database;
  $select_id_at = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select id_at from asset_type where id_ag='$id_ag' and code_at='$id_at1'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_at = $row["id_at"];
  }
  return $select_id_at;
}
#################################################
function code_id_ag($id_ag)
{
  global $host, $user, $passwd, $database;
  $code_id_ag = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select code_asset from asset_group where id_ag='$id_ag'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $code_id_ag = $row["code_asset"];
  }
  return $code_id_ag;
}
#################################################
function select_asset()
{
  global $host, $user, $passwd, $database;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select asset_edu.last_update,asset_edu.id_ae,id_asset,name_asset,name_asset_group,name_asset_type,DATE_FORMAT(date_order, '%e %M %Y') from asset_edu,asset_type,asset_group where asset_edu.id_at = asset_type.id_at and asset_group.id_ag = asset_type.id_ag order by id_ae DESC limit 0,1000";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $last_update = $row["last_update"];
    $id_ae = $row["id_ae"];
    $id_asset = $row["id_asset"];
    $name_asset = $row["name_asset"];
    $name_asset_group = $row["name_asset_group"];
    $name_asset_type = $row["name_asset_type"];
    $date_order = $row["DATE_FORMAT(date_order, '%e %M %Y')"];
    $print[$a] = "
		<tr>
			<TD><span style='display:none'>$last_update</span><FONT SIZE=2><A href='#' onclick=\"ShowNewPage('print_asset_pc.php?asset_edu=$id_ae')\">$id_asset</A></FONT></TD>
			<TD>&nbsp;&nbsp;<FONT SIZE=2><A href='#' onclick=\"ShowNewPage('print_asset_pc.php?asset_edu=$id_ae')\">$name_asset_group</A></FONT></TD>
			<TD>&nbsp;&nbsp;<FONT SIZE=2>$name_asset_type</FONT></TD>
			<TD>&nbsp;&nbsp;<FONT SIZE=2>$name_asset</FONT></TD>
			<TD><FONT SIZE=2>$date_order</FONT></TD>
			<TD><FONT SIZE=2><A href='#' onclick=\"ShowQR('show_qr_asset.php?id_ae=$id_ae&id_asset=$id_asset&name_asset=$name_asset')\"class=\"btn btn-info\" role=\"button\">QR Code</FONT></TD>
			<TD><A href='add_asset_edu_dept.php?edit_asset_edu=$id_ae'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('add_asset_edu_dept.php?delete_asset_edu=$id_ae')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
		//<FONT SIZE=2><A href='#' onclick=\"ShowDelete('add_asset_edu_dept.php?delete_asset_edu=$id_ae')\">ลบ</FONT>
    $a++;
  }
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_asset['count_asset'] = count($print);
  #print"$select_asset[count_asset]<br>";
  $select_asset['data'] = implode("\n", $print);
  return $select_asset;
}
#################################################
function print_asset_dept($select_asset,$title_head_html,$position_active)
{
print"<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>$title_head_html</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
  <!-- jvectormap -->
  <link rel=\"stylesheet\" href=\"../bower_components/jvectormap/jquery-jvectormap.css\">
  <!-- Theme style -->
  <link rel=\"stylesheet\" href=\"../dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"../dist/css/skins/_all-skins.min.css\">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
  <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel=\"stylesheet\"
        href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\">
</head>
<body class=\"hold-transition skin-blue sidebar-mini\">
<div class=\"wrapper\">
";
include("../header.php");
include("../sidebar.php");

print"
<!-- Tell the browser to be responsive to screen width -->
<!-- DataTables -->
<link rel=\"stylesheet\" href=\"../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css\">
<div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
        งานทะเบียนทรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">ทะเบียนทรัพย์สิน</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"ion ion-ios-gear-outline\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$select_asset[count_asset]</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->

      <!-- fix for small devices only -->
      <div class=\"clearfix visible-sm-block\"></div>
    </div>
    <!-- /.row -->

    <!-- Main content -->
    <section class=\"content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <div class=\"box\">
            <div class=\"box-header\">
              <h3 class=\"box-title\">ทะเบียนทรัพย์สิน</h3>

              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_asset_edu_dept.php?add_asset_edu=add\"><i class=\"fa fa-plus\"></i> เพิ่มทรัพย์สิน</a></li>
                    <li><a href=\"select_asset_group_com.php\"><i class=\"fa fa-share\"></i> ประเภททรัพย์สิน</a></li>
                  </ul>
                </div>
                <!-- <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
 -->
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>เลขทะเบียน</th>
                    <th>ประเภท</th>
                    <th>หมวด</th>
                    <th>รายละเอียด</th>
                    <th>วันสั่งซื้อ</th>
                    <th>QR Code</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $select_asset[data]
                </tbody>
                <tfoot>
                  <tr>
                  <tr>
                    <th>เลขทะเบียน</th>
                    <th>ประเภท</th>
                    <th>หมวด</th>
                    <th>รายละเอียด</th>
                    <th>วันสั่งซื้อ</th>
                    <th>QR Code</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class=\"control-sidebar-bg\"></div>
</div>
<!-- jQuery 3 -->
<script src=\"../bower_components/jquery/dist/jquery.min.js\"></script>
<!-- Bootstrap 3.3.7 -->
<script src=\"../bower_components/bootstrap/dist/js/bootstrap.min.js\"></script>
<!-- DataTables -->
<script src=\"../bower_components/datatables.net/js/jquery.dataTables.min.js\"></script>
<script src=\"../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js\"></script>
<!-- SlimScroll -->
<script src=\"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\"></script>
<!-- FastClick -->
<script src=\"../bower_components/fastclick/lib/fastclick.js\"></script>
<!-- AdminLTE App -->
<script src=\"../dist/js/adminlte.min.js\"></script>
<!-- AdminLTE for demo purposes -->
<script src=\"../dist/js/demo.js\"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
      \"order\": [
        [0, \"desc\"]
      ]
    });
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<script language='JavaScript'>
  function ShowQR(name_file) {
    myWindow = window.open(name_file, '_blank', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=220,height=220')
  }

  function ShowNew(name_file) {
    myWindow = window.open(name_file, '_blank', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=500')
  }

  function ShowDelete(name_file) {
    myWindow = window.open(name_file, '_blank', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=250')
  }

  function ShowNewPage(name_file) {
    myWindow = window.open(name_file, '_blank', 'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')
  }
</script>
";
include("../footer.php");
print"</div>
<!-- ./wrapper -->
</div>
</body>

</html>
";
exit;
}
#################################################

?>