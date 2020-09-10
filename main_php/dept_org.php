<?php
include("../lib_org.php");
//if($_SESSION['id_emp_use']){ 
$position_active='3';
$id_emp_use = $_SESSION['id_emp_use'];
if ($_POST["add_dept_edu"]) {
  $name_dept = $_POST['name_dept'];
  add_dept_edu($name_dept,$id_emp_use);
  header("location: http://localhost/main_php/dept_org.php");
  exit(0);
}
if ($_POST['save_dept']) {
  $id_dept = $_POST['id_dept'];
  $name_dept = $_POST['name_dept'];
  $id_dept = base64_decode(base64_decode("$id_dept"));
  save_dept($name_dept, $id_dept, $id_emp_use);
}
if ($_GET['delete_dept_edu']) {
  $id_dept = $_GET['delete_dept_edu'];
  $id_dept = base64_decode(base64_decode("$id_dept"));
  delete_dept_edu($id_dept);
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


$select_dept_edu = select_dept_edu();

#	print_asset_dept($select_dept_edu,$id_emp_use);
#	exit;
//include("../content.php");

#-----------จบตัดเพจ-----------#
print_asset_dept($select_dept_edu,$title_head_html,$position_active);

//}else{echo "โปรด Login ก่อน";}
#################################################
function delete_dept_edu($id_dept)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM dept_edu WHERE id_dept=$id_dept";
  mysqli_query($connect, $sql_query1);
#  $sql_query2 = "DELETE FROM dept_edu WHERE id_dept=$id_dept";
 # mysqli_query($connect, $sql_query2);
    mysqli_close($connect);
}
#################################################
function save_dept($name_dept,$id_dept, $id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update dept_edu set name_dept='$name_dept',id_use='$id_emp_use' where id_dept='$id_dept'";
  mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
#################################################
function select_name_dept_edu($id_dept)
{
  global $host, $user, $passwd, $database;
  $select_name_dept_edu = "";
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_dept from dept_edu where id_dept='$id_dept'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_name_dept_edu = $row["name_dept"];
  }
    mysqli_close($connect);
  return $select_name_dept_edu;
}
##############################
function update_loc_master($name_dept, $date_location)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "update dept_edu set date_location='$date_location' where name_dept='$name_dept'";
  mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
##############################
function update_gdept($id_ad, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "update asset_dept set date_dept='$date_dept' where id_ad='$id_ad'";
  mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
##############################
function insert_loc_master($id_dept, $id_dept1, $date_location)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "insert into dept_edu(id_dept,id_dept1,date_location,id_use) values('$id_dept','$id_dept1','$date_location','$id_emp_use')";
  mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
##############################
function insert_gdept($id_dept, $id_dept1, $date_dept)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "insert into asset_dept(id_dept,id_dept1,date_dept,id_use) values('$id_dept','$id_dept1','$date_dept','$id_emp_use')";
  mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
##############################
function check_id_dept($id_dept)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT id_dept FROM dept_edu where id_dept='$id_dept' order by name_dept DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_id_dept = $row['id_dept'];
  }
    mysqli_close($connect);
  return $check_id_dept;
}
#################################################
function add_dept_edu($name_dept,$id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into dept_edu(name_dept,id_use) values('$name_dept','$id_emp_use')";
    mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
#################################################
function count_position($id_dept)
{
  global $host, $user, $passwd, $database;
  $count_position = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select count(id_position) from position_edu where id_dept='$id_dept'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_position = $row["count(id_position)"];
  }
    mysqli_close($connect);
  return $count_position;
}
#################################################
function count_position_emp($id_dept)
{
  global $host, $user, $passwd, $database;
  $count_position = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select count(id_position_emp) from position_emp,position_edu where id_dept='$id_dept' AND position_edu.id_position = position_emp.id_position";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_position = $row["count(id_position_emp)"];
  }
    mysqli_close($connect);
  return $count_position;
}
#################################################
function count_dept_edu()
{
  global $host, $user, $passwd, $database;
  $count_dept_edu = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select count(id_dept) from dept_edu";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_dept_edu = $row["count(id_dept)"];
  }
    mysqli_close($connect);
  return $count_dept_edu;
}
#################################################
function select_dept_edu()
{
  global $host, $user, $passwd, $database;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_dept,name_dept from dept_edu order by id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    $encode_id_dept = base64_encode(base64_encode("$id_dept"));
    $count_position = count_position($id_dept);
    $count_position_emp = count_position_emp($id_dept);
    $print[$a] = "
		<tr>
			<TD>$name_dept</TD>
			<TD>$count_position</TD>
			<TD>$count_position_emp</TD>
			<TD><A href='select_position_emp.php?id_dept=$encode_id_dept'  class=\"btn btn-info\" role=\"button\">บันทึกบุคลากร</A></TD>
			<TD><A href='add_dept_edu.php?edit_dept=$encode_id_dept'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('add_dept_edu.php?delete_dept_edu=$encode_id_dept')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
		//<FONT SIZE=2><A href='#' onclick=\"ShowDelete('add_dept_edu.php?delete_asset_edu=$id_dept')\">ลบ</FONT>
    $a++;
  }
    mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_dept_edu['count_asset'] = count($print);
  #print"$select_dept_edu[count_asset]<br>";
  $select_dept_edu['data'] = implode("\n", $print);
  return $select_dept_edu;
}
#################################################
function print_asset_dept($select_dept_edu,$title_head_html,$position_active)
{
$count_dept_edu=count_dept_edu();
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
        จัดการหน่วยงาน 
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">จัดการหน่วยงาน </li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-th-large\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$count_dept_edu</span>
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
              <h3 class=\"box-title\">จัดการหน่วยงาน </h3>

              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_dept_edu.php?add_dept=add\"><i class=\"fa fa-plus\"></i> เพิ่มหน่วยงาน </a></li>
                    <li><a href=\"select_position.php?manage_position=add\"><i class=\"fa fa-share\"></i> จัดการตำแหน่งงาน</a></li>
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
                    <th>ชื่อหน่วยงาน</th>
                    <th>จำนวนตำแหน่ง</th>
                    <th>จำนวนบุคลากร</th>
                    <th>เพิ่มบุคลากร</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $select_dept_edu[data]
                </tbody>
                <tfoot>
                  <tr>
                  <tr>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จำนวนตำแหน่ง</th>
                    <th>จำนวนบุคลากร</th>
                    <th>เพิ่มบุคลากร</th>
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