<?php
include("../lib_org.php");
$title_head_html = "จัดการบุคลากรในหน่วยงาน";
$position_active = '3';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_dept = base64_decode(base64_decode($_GET['id_dept']));
if ($_POST["add_position_emp"]) {
  $id_dept = $_POST['id_dept'];
  $id_dept = base64_decode(base64_decode($id_dept));
  $id_emp = $_POST['id_emp'];
  $id_position = $_POST['id_position'];
  add_position_emp($id_emp,$id_position,$id_emp_use);
  $encode_id_dept = base64_encode(base64_encode("$id_dept"));
  header("location: http://localhost/main_php/select_position_emp.php?id_dept=$encode_id_dept");
  exit(0);
}
if ($_POST['save_position_emp']) {
  $id_position_emp = $_POST['id_position_emp'];
  $id_position_emp = base64_decode(base64_decode($id_position_emp));
  $id_dept = $_POST['id_dept'];
  $id_dept = base64_decode(base64_decode($id_dept));
  $id_emp = $_POST['id_emp'];
  $id_position = $_POST['id_position'];
  save_position_emp($id_position_emp,$id_emp,$id_position,$id_dept,$id_emp_use);
}

if ($_GET['delete_position']) {
  $id_position_emp = $_GET['delete_position'];
  $id_position_emp = base64_decode(base64_decode("$id_position_emp"));
  delete_position_emp($id_position_emp);
  if (array_key_exists("closeme",$_GET)) {
?>
<script language="JavaScript">
window.opener.location.reload(true);
window.close();
</script>
<?php
    exit();
  }
}
//$select_asset_group = select_asset_group($id_dept);
$select_dept_emp = select_dept_emp($id_dept);
print_asset_dept($select_dept_emp,$title_head_html,$id_dept,$position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function delete_position_emp($id_position_emp)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query1 = "DELETE FROM position_emp WHERE id_position_emp=$id_position_emp";
  mysqli_query($connect,$sql_query1);
  mysqli_close($connect);
}
#################################################
function save_position_emp($id_position_emp,$id_emp,$id_position,$id_dept,$id_emp_use)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "update position_emp set id_emp='$id_emp',id_position='$id_position',id_use='$id_emp_use' where id_position_emp='$id_position_emp'";
  mysqli_query($connect,$sql_query);
  mysqli_close($connect);
}
#################################################
function add_position_emp($id_emp,$id_position,$id_emp_use)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "insert into position_emp(id_emp,id_position,id_use) values('$id_emp','$id_position','$id_emp_use')";
  mysqli_query($connect,$sql_query);
  mysqli_close($connect);
}
#################################################
function select_dept_emp($id_dept)
{
  global $host,$user,$passwd,$database;
  $a = 0;
  $encode_id_dept = base64_encode(base64_encode("$id_dept"));
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "SELECT id_emp,name_position,id_position_emp from position_emp,position_edu where id_dept='$id_dept' AND position_edu.id_position = position_emp.id_position order by position_edu.id_position ASC;";
  $shows = mysqli_query($connect,$sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_position_emp = $row["id_position_emp"];
    $name_position = $row["name_position"];
    $id_emp = $row["id_emp"];
    $name_emp = name_emp($id_emp);
    $encode_id_position_emp = base64_encode(base64_encode("$id_position_emp"));
    $encode_id_dept = base64_encode(base64_encode("$id_dept"));
    $print[$a] = "
		<TR>
			<TD>$name_position</TD>
			<TD>&nbsp;&nbsp;$name_emp[f_name]$name_emp[name] &nbsp;&nbsp;$name_emp[l_name]</TD>
			<TD><A href='add_position_emp.php?edit_position=$encode_id_position_emp&id_dept=$encode_id_dept'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('add_position_emp.php?delete_position=$encode_id_position_emp&id_dept=$encode_id_dept')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
    $a++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_dept_emp['count_asset'] = count($print);
  #print"$select_asset[count_asset]<br>";
  $select_dept_emp['data'] = implode("\n",$print);
  return $select_dept_emp;
}
#################################################
function name_emp($id_emp)
{
  global $connect,$database,$host,$user,$passwd,$icons;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "select f_name,name,l_name,active,pic,email from emp_edu where id_emp='$id_emp'";
  $shows = mysqli_query($connect,$sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $name_emp['f_name'] = $row["f_name"];
    $name_emp['name'] = $row["name"];
    $name_emp['l_name'] = $row["l_name"];
    $name_emp['pic'] = $row["pic"];
    $name_emp['active'] = $row["active"];
    $name_emp['email'] = $row["email"];
  }
  mysqli_close($connect);
  return $name_emp;
}
#################################################
function select_asset_group($id_dept)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "select code_asset,name_asset_group,id_dept from asset_group where id_dept='$id_dept'";
  $shows = mysqli_query($connect,$sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_asset_group['code_asset'] = $row["code_asset"];
    $select_asset_group['name_asset_group'] = $row["name_asset_group"];
    $select_asset_group['id_dept'] = $row["id_dept"];
  }
  mysqli_close($connect);
  return $select_asset_group;
}
##############################
function select_dept_edu($id_dept)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql = "SELECT name_dept FROM dept_edu where id_dept='$id_dept'";
  $query = mysqli_query($connect,$sql);
  while ($row = mysqli_fetch_array($query)) {
    $select_dept_edu = $row['name_dept'];
  }
  mysqli_close($connect);
  return $select_dept_edu;
}
##############################
function count_emp_dept($id_dept)
{
  global $host,$user,$passwd,$database;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "SELECT COUNT(id_position_emp) FROM position_emp,position_edu WHERE position_emp.id_position = position_edu.id_position AND position_edu.id_dept='1'";
  $query = mysqli_query($connect,$sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $count_emp_dept = $row['COUNT(id_position_emp)'];
  }
  mysqli_close($connect);
  return $count_emp_dept;
}
#################################################
function print_asset_dept($select_dept_emp,$title_head_html,$id_dept,$position_active)
{
  $encode_id_dept = base64_encode(base64_encode($id_dept));
  $select_dept_edu = select_dept_edu($id_dept);
  $count_emp_dept = count_emp_dept($id_dept);
  
  print "<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>$title_head_html</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no\" name=\"viewport\">
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

  print "
<!-- Tell the browser to be responsive to screen width -->
<!-- DataTables -->
<link rel=\"stylesheet\" href=\"../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css\">
<div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
      บันทึกบุคลากร 
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"dept_org.php\">จัดการหน่วยงาน</a></li>
        <li class=\"active\">บันทึกบุคลากร</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-user\"></i></span>
          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$count_emp_dept</span>
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
              <h3 class=\"box-title\">บันทึกบุคลากร $select_dept_edu</h3> 
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_position_emp.php?add_position=$encode_id_dept\"><i class=\"fa fa-plus\"></i> เพิ่มบุคลากร </a></li>
                    <li> <a href=\"dept_org.php\"><i class=\"fa fa-reply-all\"></i> จัดการหน่วยงาน </a></li>
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
                    <th>ชื่อตำแหน่ง</th>
                    <th>ชื่อบุคลากร</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $select_dept_emp[data]
                </tbody>
                <tfoot>
                <tr>
                <th>ชื่อตำแหน่ง</th>
                <th>ชื่อบุคลากร</th>
                <th>แก้ไข</th>
                <th>ลบ</th>
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
        [0,\"desc\"]
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
    myWindow = window.open(name_file,'_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=220,height=220')
  }

  function ShowNew(name_file) {
    myWindow = window.open(name_file,'_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=500')
  }

  function ShowDelete(name_file) {
    myWindow = window.open(name_file,'_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=250')
  }

  function ShowNewPage(name_file) {
    myWindow = window.open(name_file,'_blank','toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')
  }
</script>
";
  include("../footer.php");
  print "</div>
<!-- ./wrapper -->
</div>
</body>

</html>
";
  exit;
}
#################################################

?>