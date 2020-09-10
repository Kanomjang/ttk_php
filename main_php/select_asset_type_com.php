<?php
include("../lib_org.php");
$title_head_html="หมวดหมู่ทรัพย์สิน";
$position_active='1';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_ag = base64_decode(base64_decode($_GET['asset_type']));
if ($_POST["add_asset_type"]) {
  $id_ag = $_POST['id_ag'];
  $code_at = $_POST['code_at'];
  $name_asset_type = $_POST['name_asset_type'];
  $usage_life = $_POST['usage_life'];
  add_asset_type($id_ag, $code_at, $name_asset_type, $usage_life, $id_emp_use);
    $encode_id_ag = base64_encode(base64_encode("$id_ag"));
  header("location: http://localhost/main_php/select_asset_type_com.php?asset_type=$encode_id_ag");
  exit(0);
}
if ($_POST['save_asset_type']) {
  $id_at = $_POST['id_at'];
  $id_at = base64_decode(base64_decode($id_at));
  $id_ag = $_POST['id_ag'];
  $id_ag = base64_decode(base64_decode($id_ag));
  $code_at = $_POST['code_at'];
  $name_asset_type = $_POST['name_asset_type'];
  $usage_life = $_POST['usage_life'];
  save_asset_type($id_at, $code_at, $name_asset_type, $usage_life,$id_emp_use);
}

if ($_GET['delete_asset_type']) {
  $id_at = $_GET['delete_asset_type'];
  $id_at = base64_decode(base64_decode("$id_at"));
  delete_asset_type($id_at);
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
$select_asset_group = select_asset_group($id_ag);
$select_asset_type = select_asset_type($id_ag);
print_asset_dept($select_asset_group,$select_asset_type,$title_head_html,$id_ag,$position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function delete_asset_type($id_at)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM asset_type WHERE id_at=$id_at";
  mysqli_query($connect, $sql_query1);
}
#################################################
function save_asset_type($id_at, $code_at, $name_asset_type, $usage_life,$id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update asset_type set name_asset_type='$name_asset_type',code_at='$code_at',usage_life='$usage_life',id_use='$id_emp_use' where id_at='$id_at'";
  mysqli_query($connect, $sql_query);
}
#################################################
function add_asset_type($id_ag, $code_at, $name_asset_type, $usage_life, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into asset_type(id_ag,code_at,name_asset_type,usage_life,id_use) values('$id_ag','$code_at','$name_asset_type','$usage_life','$id_emp_use')";
    mysqli_query($connect, $sql_query);
}
#################################################
function select_asset_type($id_ag)
{
  global $host, $user, $passwd, $database;
  $a = 0;
    $encode_id_ag = base64_encode(base64_encode("$id_ag"));
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_at,code_at,name_asset_type,usage_life,last_update FROM asset_type where id_ag='$id_ag' order by id_at ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $last_update = $row["last_update"];
    $id_at = $row["id_at"];
    $code_at = $row["code_at"];
    $usage_life = $row["usage_life"];
    $name_asset_type = $row["name_asset_type"];
    $encode_id_at = base64_encode(base64_encode("$id_at"));
    $print[$a] = "
		<TR>
			<TD><span style='display:none'>$last_update</span>$code_at</TD>
			<TD>&nbsp;&nbsp;$name_asset_type</TD>
			<TD>$usage_life</TD>
			<TD><A href='add_asset_type_com.php?edit_asset_type=$encode_id_ag&id_at=$encode_id_at'  class=\"btn btn-warning\" role=\"button\">แก้ไข</A></TD>
			<TD><A href='#' onclick=\"ShowDelete('add_asset_type_com.php?delete_asset_type=$encode_id_ag&id_at=$encode_id_at')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
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
function select_asset_group($id_ag)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select code_asset,name_asset_group,id_dept from asset_group where id_ag='$id_ag'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_asset_group['code_asset'] = $row["code_asset"];
    $select_asset_group['name_asset_group'] = $row["name_asset_group"];
    $select_asset_group['id_dept'] = $row["id_dept"];
  }
  return $select_asset_group;
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
  return $select_dept_edu;
}
#################################################
function print_asset_dept($select_asset_group,$select_asset,$title_head_html,$id_ag,$position_active)
{
	if($select_asset_group['id_dept']=="0"){
		$text_dept_edu="";
	}else{
		$select_dept_edu=select_dept_edu($select_asset_group['id_dept']);
		$text_dept_edu="<br><br><h3 class=\"box-title\">หน่วยงานที่รับผิดชอบ  &nbsp;  &nbsp;  $select_dept_edu</h3>";
	}
	  $encode_id_ag = base64_encode(base64_encode($id_ag));

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
        หมวดหมู่ทรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"print_asset_edu.php\">ทะเบียนทรัพย์สิน</a></li>
        <li ><a href=\"select_asset_group_com.php\">ประเภททรัพย์สิน</a></li>
        <li class=\"active\">หมวดหมู่ทรัพย์สิน</li>
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
              <h3 class=\"box-title\">หมวดหมู่ทรัพย์สิน &nbsp;  &nbsp;  </h3>$select_asset_group[code_asset]  &nbsp; &nbsp; $select_asset_group[name_asset_group]
$text_dept_edu
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"add_asset_type_com.php?add_asset_type_com=$encode_id_ag\"><i class=\"fa fa-plus\"></i> เพิ่มหมวดหมู่ทรัพย์สิน</a></li>
                    <li> <a href=\"select_asset_group_com.php\"><i class=\"fa fa-reply\"></i> ประเภททรัพย์สิน</a></li>
                    <li> <a href=\"print_asset_edu.php\"><i class=\"fa fa-reply-all\"></i> ทะเบียนทรัพย์สิน</a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>เลขประจำหมวด</th>
                    <th>หมวดของหมวดหมู่ทรัพย์สิน</th>
                    <th>อายุการใช้งาน</th>
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
                    <th>เลขประจำหมวด</th>
                    <th>หมวดของหมวดหมู่ทรัพย์สิน</th>
                    <th>อายุการใช้งาน</th>
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