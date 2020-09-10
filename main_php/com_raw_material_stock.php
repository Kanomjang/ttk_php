<?php
include("../lib_org.php");
$title_head_html = "รายละเอียดวัตถุดิบ";
$position_active = '9';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
if ($_POST["add_com_raw_material"]) {
  $code_crmd = $_POST['code_crmd'];
  $id_cap = $_POST['id_cap'];
  $name_raw_material = $_POST['name_raw_material'];
  $details_raw_material = $_POST['details_raw_material'];
  add_com_raw_material($code_crmd, $id_cap, $name_raw_material, $details_raw_material, $id_emp_use);
  header("location: http://localhost/main_php/com_raw_material.php");
  exit(0);
}
if ($_POST['save_com_raw_material']) {
  $id_crmd = $_POST['id_crmd'];
  $id_crmd = base64_decode(base64_decode($id_crmd));
  $code_crmd = $_POST['code_crmd'];
  $id_cap = $_POST['id_cap'];
  $name_raw_material = $_POST['name_raw_material'];
  $details_raw_material = $_POST['details_raw_material'];
  save_com_raw_material($id_crmd, $code_crmd, $id_cap, $name_raw_material, $details_raw_material, $id_emp_use);
}

if ($_GET['delete_com_raw_material']) {
  $id_crmd = $_GET['delete_com_raw_material'];
  $id_crmd = base64_decode(base64_decode("$id_crmd"));
  delete_com_raw_material($id_crmd);
  if (array_key_exists("closeme", $_GET)) {
?>
<script language="JavaScript">
window.opener.location.reload(true);
window.close();
</script>
<?php
    exit();
  }
}
$select_com_raw_material = select_com_raw_material();
print_asset_dept($select_com_raw_material, $title_head_html, $position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function save_com_raw_material($id_crmd, $code_crmd, $id_cap, $name_raw_material, $details_raw_material, $id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update com_raw_material set code_crmd='$code_crmd', id_cap='$id_cap', name_raw_material='$name_raw_material', details_raw_material='$details_raw_material',id_use='$id_emp_use' where id_crmd='$id_crmd'";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function add_com_raw_material($code_crmd, $id_cap, $name_raw_material, $details_raw_material, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into com_raw_material(code_crmd,id_cap,name_raw_material,details_raw_material,id_use) values('$code_crmd','$id_cap','$name_raw_material','$details_raw_material','$id_emp_use')";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function delete_com_raw_material($id_crmd)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "DELETE FROM com_raw_material WHERE id_crmd=$id_crmd";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function select_com_raw_material()
{
  global $host, $user, $passwd, $database;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_crmd,code_crmd,id_cap,name_raw_material,details_raw_material FROM com_raw_material order by id_cap ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmd = $row["id_crmd"];
    $code_crmd = $row["code_crmd"];
    $id_cap = $row["id_cap"];
    $name_raw_material = $row["name_raw_material"];
    $details_raw_material = $row["details_raw_material"];
    $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));
    $name_cap=name_cap( $id_cap);
    $print[$a] = "
		<TR>
            <TD>&nbsp;&nbsp;$code_crmd</TD>
            <TD>&nbsp;&nbsp;$name_raw_material</TD>
            <TD>&nbsp;&nbsp;$details_raw_material</TD>
            <TD>&nbsp;&nbsp;$name_cap</TD>
			<TD><A href='com_add_raw_material.php?edit_com_raw_material=$encode_id_crmd'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('com_add_raw_material.php?delete_com_raw_material=$encode_id_crmd')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
    $a++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_com_raw_material['count_asset'] = count($print);
  #print"$select_com_raw_material[count_asset]<br>";
  $select_com_raw_material['data'] = implode("\n", $print);
  return $select_com_raw_material;
}
#################################################
function name_cap( $id_cap)
{
  global $host, $user, $passwd, $database;
  $name_cap="";
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_accounts_payable from com_accounts_payable where id_cap='$id_cap'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $name_cap = $row["name_accounts_payable"];
  }
  mysqli_close($connect);
  return $name_cap;
}
##############################
function select_dept_edu($id_dept)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT name_dept FROM dept_edu where id_dept='$id_dept'";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $select_dept_edu = $row['name_dept'];
  }
  mysqli_close($connect);
  return $select_dept_edu;
}
#################################################
function print_asset_dept($select_com_raw_material, $title_head_html, $position_active)
{
  print "<!DOCTYPE html>
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
        จัดการรายละเอียดวัตถุดิบ
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">รายละเอียดวัตถุดิบ</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-home\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$select_com_raw_material[count_asset]</span>
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
              <h3 class=\"box-title\">จัดการรายละเอียดวัตถุดิบ &nbsp;  &nbsp;   $text_ledger</h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"com_add_raw_material.php?com_add_raw_material=true\"><i class=\"fa fa-plus\"></i> เพิ่มรายละเอียดวัตถุดิบ</a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>รหัสวัตถุดิบ</th>
                    <th>ชื่อวัตถุดิบ</th>
                    <th>รายละเอียดวัตถุดิบ</th>
                    <th>สถานที่สั่งซื้อ</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $select_com_raw_material[data]
                </tbody>
                <tfoot>
                <tr>
                <th>รหัสวัตถุดิบ</th>
                <th>ชื่อวัตถุดิบ</th>
                <th>รายละเอียดวัตถุดิบ</th>
                <th>สถานที่สั่งซื้อ</th>
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