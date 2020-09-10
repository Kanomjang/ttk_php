<?php
include("../lib_org.php");
//if($_SESSION['id_emp_use']){ 
$position_active='5';
$id_emp_use = $_SESSION['id_emp_use'];
#$select_building_location = select_building_location();
print_accounting($title_head_html,$position_active);

//}else{echo "โปรด Login ก่อน";}
#################################################
function count_asset()
{
  global $host, $user, $passwd, $database;
  $count_asset = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select count(asset_edu.id_loc) from asset_edu,asset_type,asset_group where asset_edu.id_at = asset_type.id_at and asset_group.id_ag = asset_type.id_ag";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_asset = $row["count(asset_edu.id_loc)"];
  }
  return $count_asset;
}
#################################################
function delete_building_location($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM location_group WHERE id_loc=$id_loc";
  mysqli_query($connect, $sql_query1);
  $sql_query2 = "DELETE FROM location_master WHERE id_loc=$id_loc";
  mysqli_query($connect, $sql_query2);
}
#################################################
function save_building_location($name_location, $floor_location, $id_loc, $id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update location_group set name_location='$name_location',floor_location='$floor_location',id_use='$id_emp_use' where id_loc='$id_loc'";
  mysqli_query($connect, $sql_query);
}
#################################################
function select_id_ad($id_loc)
{
  global $host, $user, $passwd, $database;
  $select_id_ad = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_ad from asset_dept where id_loc='$id_loc' order by id_ad DESC limit 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_ad = $row["id_ad"];
  }
  return $select_id_ad;
}
#################################################
function select_id_al($id_loc)
{
  global $host, $user, $passwd, $database;
  $select_id_al = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_al from asset_location where id_loc='$id_loc' order by id_al DESC limit 0,1";
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
  $sql_query = "update asset_location set date_location='$date_location' where id_al='$id_al'";
  mysqli_query($connect, $sql_query);
}
##############################
function update_gdept($id_ad, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update asset_dept set date_dept='$date_dept' where id_ad='$id_ad'";
  mysqli_query($connect, $sql_query);
}
##############################
function insert_loc_master($id_loc, $id_loc_master, $date_location)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into asset_location(id_loc,id_loc_master,date_location,id_use) values('$id_loc','$id_loc_master','$date_location','$id_emp_use')";
  mysqli_query($connect, $sql_query);
}
##############################
function insert_gdept($id_loc, $id_dept, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into asset_dept(id_loc,id_dept,date_dept,id_use) values('$id_loc','$id_dept','$date_dept','$id_emp_use')";
  mysqli_query($connect, $sql_query);
}
##############################
function check_id_loc_master($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql = "SELECT id_loc_master FROM asset_location where id_loc='$id_loc' order by id_al DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_id_loc_master = $row['id_loc_master'];
  }
  return $check_id_loc_master;
}
##############################
function check_id_dept($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql = "SELECT id_dept FROM asset_dept where id_loc='$id_loc' order by id_ad DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_id_dept = $row['id_dept'];
  }
  return $check_id_dept;
}
#################################################
function add_building_room($name_location, $floor_location, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use, $homepage;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into name_location_master(name_location,floor_location,id_use) values('$name_location','$floor_location','$id_emp_use')";
    mysqli_query($connect, $sql_query);
}
#################################################
function select_id_at($id_ag, $id_at1)
{
  global $host, $user, $passwd, $database;
  $select_id_at = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_at from asset_type where id_ag='$id_ag' and code_at='$id_at1'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_at = $row["id_at"];
  }
  return $select_id_at;
}
#################################################
function count_com_ledger($id_ledger)
{
  global $host, $user, $passwd, $database;
  $count_com_ledger = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select count(id_cl) from com_ledger where id_ledger='$id_ledger'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_com_ledger = $row["count(id_cl)"];
  }
  return $count_com_ledger;
}
#################################################
function select_building_location()
{
  global $host, $user, $passwd, $database;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_loc,name_location,floor_location from location_group  order by id_loc ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_loc = $row["id_loc"];
    $name_location = $row["name_location"];
    $floor_location = $row["floor_location"];
	  $encode_id_loc = base64_encode(base64_encode("$id_loc"));
    $count_location_room = count_location_room($id_loc);
    $print[$a] = "
		<tr>
			<td>$name_location</td>
			<td>$floor_location</td>
			<td>$count_location_room</td>
			<td><A href='com_select_accounting.php?com_select_accounting=$encode_id_loc'  class=\"btn btn-info\" role=\"button\">บันทึกห้อง</A></td>
			<td><A href='add_building_room.php?edit_building_location=$encode_id_loc'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></td>
			<td><A href='#' onclick=\"ShowDelete('add_building_room.php?delete_building_location=$encode_id_loc')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></td>
		</TR>";
		//<FONT SIZE=2><A href='#' onclick=\"ShowDelete('add_building_room.php?delete_asset_edu=$id_loc')\">ลบ</FONT>
    $a++;
  }
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_building_location['count_asset'] = count($print);
  #print"$select_building_location[count_asset]<br>";
  $select_building_location['data'] = implode("\n", $print);
  return $select_building_location;
}
#################################################
function print_accounting($title_head_html,$position_active)
{
	$count_com_ledger='0';
	  $encode_accounting1 = base64_encode(base64_encode("1"));
	  $encode_accounting2 = base64_encode(base64_encode("2"));
	  $encode_accounting3 = base64_encode(base64_encode("3"));
	  $encode_accounting4 = base64_encode(base64_encode("4"));
    $encode_accounting5 = base64_encode(base64_encode("5"));
    $count_com_ledger1=count_com_ledger("1");
    $count_com_ledger2=count_com_ledger("2");
    $count_com_ledger3=count_com_ledger("3");
    $count_com_ledger4=count_com_ledger("4");
    $count_com_ledger5=count_com_ledger("5");
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
        บัญชีแยกประเภท 
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">บัญชีแยกประเภท </li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-home\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">5</span>
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
              <h3 class=\"box-title\">บัญชีแยกประเภท </h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <!--   
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_building_room.php?add_building_room=add\"><i class=\"fa fa-plus\"></i> เพิ่มอาคาร/สถานที่ </a></li>
                  </ul>
                </div> -->
                <!-- <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
 -->
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>ลำดับที่</th>
                    <th>ประเภท</th>
                    <th>จำนวนประเภท</th>
                    <th>เพิ่มประเภท</th>
                  </tr>
                </thead>
                <tbody>
		<tr>
			<td>1</td>
			<td>สินทรัพย์</td>
			<td>$count_com_ledger1</td>
			<td><A href='com_select_ledger.php?com_select_accounting=$encode_accounting1'  class=\"btn btn-info\" role=\"button\">บันทึกประเภท</A></td>
		</tr>
		<tr>
			<td>2</td>
			<td>หนี้สิน</td>
			<td>$count_com_ledger2</td>
			<td><A href='com_select_ledger.php?com_select_accounting=$encode_accounting2'  class=\"btn btn-info\" role=\"button\">บันทึกประเภท</A></td>
		</tr>
		<tr>
			<td>3</td>
			<td>ทุน</td>
			<td>$count_com_ledger3</td>
			<td><A href='com_select_ledger.php?com_select_accounting=$encode_accounting3'  class=\"btn btn-info\" role=\"button\">บันทึกประเภท</A></td>
		</tr>
		<tr>
			<td>4</td>
			<td>รายได้</td>
			<td>$count_com_ledger4</td>
			<td><A href='com_select_ledger.php?com_select_accounting=$encode_accounting4'  class=\"btn btn-info\" role=\"button\">บันทึกประเภท</A></td>
		</tr>
		<tr>
			<td>5</td>
			<td>ค่าใช้จ่าย</td>
			<td>$count_com_ledger5</td>
			<td><A href='com_select_ledger.php?com_select_accounting=$encode_accounting5'  class=\"btn btn-info\" role=\"button\">บันทึกประเภท</A></td>
		</tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>ลำดับที่</th>
                    <th>ประเภท</th>
                    <th>จำนวนประเภท</th>
                    <th>เพิ่มประเภท</th>
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
