<?php
include("../lib_org.php");
$title_head_html="จัดการห้องภายในอาคาร/สถานที่";
$position_active='2';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_loc = base64_decode(base64_decode($_GET['select_building_room']));
if ($_POST["add_building_room"]) {
  $id_loc = $_POST['id_loc'];
  $id_loc = base64_decode(base64_decode($_POST['id_loc']));
  $floor_master = $_POST['floor_master'];
  $name_location_master = $_POST['name_location_master'];
  add_location_master($id_loc, $floor_master, $name_location_master, $id_emp_use);
    $encode_id_loc = base64_encode(base64_encode("$id_loc"));
  header("location: http://localhost/main_php/select_building_room.php?select_building_room=$encode_id_loc");
  exit(0);
}
if ($_POST['save_location_master']) {
  $id_loc_master = $_POST['id_loc_master'];
  $id_loc_master = base64_decode(base64_decode($id_loc_master));
  $id_loc = $_POST['id_loc'];
  $id_loc = base64_decode(base64_decode($id_loc));
  $floor_master = $_POST['floor_master'];
  $name_location_master = $_POST['name_location_master'];
  save_location_master($id_loc_master, $floor_master, $name_location_master,$id_emp_use);
}

if ($_GET['delete_building_room']) {
  $id_loc_master = $_GET['delete_building_room'];
  $id_loc_master = base64_decode(base64_decode("$id_loc_master"));
  delete_location_master($id_loc_master);
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
$select_location_group = select_location_group($id_loc);
$select_building_room = select_building_room($id_loc);
print_asset_dept($select_location_group,$select_building_room,$title_head_html,$id_loc,$position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function delete_location_master($id_loc_master)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM location_master WHERE id_loc_master=$id_loc_master";
  mysqli_query($connect, $sql_query1);
}
#################################################
function save_location_master($id_loc_master, $floor_master, $name_location_master,$id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update location_master set name_location_master='$name_location_master',floor_master='$floor_master',id_use='$id_emp_use' where id_loc_master='$id_loc_master'";
  mysqli_query($connect, $sql_query);
}
#################################################
function add_location_master($id_loc, $floor_master, $name_location_master, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into location_master(id_loc,floor_master,name_location_master,id_use) values('$id_loc','$floor_master','$name_location_master','$id_emp_use')";
    mysqli_query($connect, $sql_query);
}
#################################################
function select_building_room($id_loc)
{
  global $host, $user, $passwd, $database;
  $a = 0;
    $encode_id_loc = base64_encode(base64_encode("$id_loc"));
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_loc_master,floor_master,name_location_master,last_update FROM location_master where id_loc='$id_loc' order by floor_master ASC,id_loc_master ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_loc_master = $row["id_loc_master"];
    $name_location_master = $row["name_location_master"];
    $floor_master = $row["floor_master"];
    $encode_id_loc_master = base64_encode(base64_encode("$id_loc_master"));
    $print[$a] = "
		<TR>
			<TD>&nbsp;&nbsp;$name_location_master</TD>
			<TD>$floor_master</TD>
			<TD><A href='add_building_room.php?edit_building_room=$encode_id_loc&id_loc_master=$encode_id_loc_master'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('add_building_room.php?delete_building_room=$encode_id_loc&id_loc_master=$encode_id_loc_master')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
    $a++;
  }
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_building_room['count_asset'] = count($print);
  #print"$select_building_room[count_asset]<br>";
  $select_building_room['data'] = implode("\n", $print);
  return $select_building_room;
}
#################################################
function select_location_group($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_location from location_group where id_loc='$id_loc'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_location_group['name_location'] = $row["name_location"];
  }
  return $select_location_group;
}
##############################
function select_dept_edu($id_dept)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_dept FROM dept_edu where id_dept='$id_dept'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $select_dept_edu = $row['name_dept'];
  }
  return $select_dept_edu;
}
#################################################
function print_asset_dept($select_location_group,$select_building_room,$title_head_html,$id_loc,$position_active)
{
	  $encode_id_loc = base64_encode(base64_encode($id_loc));

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
        จัดการห้องภายในอาคาร/สถานที่
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"building_location.php\">จัดการอาคาร/สถานที่</a></li>
        <li class=\"active\">จัดการห้อง</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-home\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$select_building_room[count_asset]</span>
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
              <h3 class=\"box-title\">จัดการห้องภายในอาคาร/สถานที่ &nbsp;  &nbsp;  </h3> $select_location_group[name_location]
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_building_room.php?add_building_room=$encode_id_loc\"><i class=\"fa fa-plus\"></i> เพิ่มจัดการห้องภายในอาคาร/สถานที่</a></li>
                    <li> <a href=\"building_location.php\"><i class=\"fa fa-reply\"></i> จัดการอาคาร/สถานที่ </a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>ชื่อห้อง</th>
                    <th>ชั้น</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $select_building_room[data]
                </tbody>
                <tfoot>
                  <tr>
                  <tr>
                    <th>ชื่อห้อง</th>
                    <th>ชั้น</th>
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