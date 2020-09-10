<?php
include("../lib_org.php");
$title_head_html="จัดการสถานที่ซื้อ";
$position_active='8';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_crmd = base64_decode(base64_decode($_GET['com_raw_matreial_seller']));
if ($_POST["com_add_raw_matreial_seller"]) {
  $id_crmd = $_POST['id_crmd'];
  $id_crmd = base64_decode(base64_decode($_POST['id_crmd']));
  $id_cap = $_POST['id_cap'];
  add_com_raw_matreial_seller($id_cap, $id_crmd, $id_emp_use);
  $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));
  header("location: http://localhost/main_php/com_raw_matreial_seller.php?com_raw_matreial_seller=$encode_id_crmd");
  exit(0);
}
if ($_POST['save_com_raw_matreial_seller']) {
  $id_cap = $_POST['id_cap'];
  $id_cap = base64_decode(base64_decode($id_cap));
  $id_crmd = $_POST['id_crmd'];
  $id_crmd = base64_decode(base64_decode($id_crmd));
  $id_crmd = $_POST['id_crmd'];
  $name_accounts_payable = $_POST['name_accounts_payable'];
  save_com_raw_matreial_seller($id_cap, $id_crmd, $name_accounts_payable,$id_emp_use);
}

if ($_GET['delete_com_raw_matreial_seller']) {
  $id_crmse = $_GET['delete_com_raw_matreial_seller'];
  $id_crmse = base64_decode(base64_decode("$id_crmse"));
  delete_com_raw_matreial_seller($id_crmse);
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
$com_raw_material_details = com_raw_material_details($id_crmd);
$com_raw_matreial_seller = com_raw_matreial_seller($id_crmd);
print_asset_dept($com_raw_material_details,$com_raw_matreial_seller,$title_head_html,$id_crmd,$position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function delete_com_raw_matreial_seller($id_crmse)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query1 = "DELETE FROM com_raw_matreial_seller WHERE id_crmse=$id_crmse";
  mysqli_query($connect, $sql_query1);
  mysqli_close($connect);
}
#################################################
function save_com_raw_matreial_seller($id_cap, $id_crmd, $name_accounts_payable,$id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update com_raw_matreial_seller set name_accounts_payable='$name_accounts_payable',id_crmd='$id_crmd',id_use='$id_emp_use' where id_cap='$id_cap'";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function add_com_raw_matreial_seller($id_cap, $id_crmd, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into com_raw_matreial_seller(id_cap,id_crmd,id_use) values('$id_cap','$id_crmd','$id_emp_use')";
    mysqli_query($connect, $sql_query);
    mysqli_close($connect);
  }
#################################################
function com_raw_matreial_seller($id_crmd)
{
  global $host, $user, $passwd, $database;
  $a = 0;
    $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_raw_matreial_seller.id_crmse,com_accounts_payable.name_accounts_payable FROM com_raw_matreial_seller,com_accounts_payable WHERE com_raw_matreial_seller.id_cap = com_accounts_payable.id_cap AND id_crmd='$id_crmd' ORDER BY com_raw_matreial_seller.last_update desc";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmse = $row["id_crmse"];
    $name_accounts_payable = $row["name_accounts_payable"];
    $encode_id_crmse = base64_encode(base64_encode("$id_crmse"));
    $print[$a] = "
		<TR>
			<TD>&nbsp;&nbsp;$name_accounts_payable</TD>
			<TD><A href='#' onclick=\"ShowDelete('com_add_raw_matreial_seller.php?delete_com_raw_matreial_seller=$encode_id_crmd&id_crmse=$encode_id_crmse')\" class=\"btn btn-danger\" role=\"button\">ลบ</A></TD>
		</TR>";
    $a++;
  }
  mysqli_close($connect);
  if (count($print) == '0' || count($print) == '') {
    $print[0] = "";
  }
  $com_raw_matreial_seller['count_asset'] = count($print);
  #print"$com_raw_matreial_seller[count_asset]<br>";
  $com_raw_matreial_seller['data'] = implode("\n", $print);
  return $com_raw_matreial_seller;
}
#################################################
function com_raw_material_details($id_crmd)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_raw_material_details from com_raw_material_details where id_crmd='$id_crmd'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $com_raw_material_details['name_raw_material_details'] = $row["name_raw_material_details"];
  }
  mysqli_close($connect);
  return $com_raw_material_details;
}
#################################################
function print_asset_dept($com_raw_material_details,$com_raw_matreial_seller,$title_head_html,$id_crmd,$position_active)
{
	  $encode_id_crmd = base64_encode(base64_encode($id_crmd));

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
        จัดการสถานที่ซื้อ$com_raw_material_details[name_raw_material_details]
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"com_raw_material_details.php\">รายละเอียดวัตถุดิบ </a></li>
        <li class=\"active\">สถานที่ซื้อ</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <div class=\"row\">
      <div class=\"col-md-3 col-sm-6 col-xs-12\">
        <div class=\"info-box\">
          <span class=\"info-box-icon bg-aqua\"><i class=\"glyphicon glyphicon-home\"></i></span>

          <div class=\"info-box-content\">
            <span class=\"info-box-text\">จำนวน</span>
            <span class=\"info-box-number\">$com_raw_matreial_seller[count_asset]</span>
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
              <h3 class=\"box-title\">สถานที่ซื้อ &nbsp;  &nbsp;   $com_raw_material_details[name_raw_material_details]</h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"com_add_raw_matreial_seller.php?com_add_raw_matreial_seller=$encode_id_crmd\"><i class=\"fa fa-plus\"></i> เพิ่มสถานที่ซื้อ</a></li>
                    <li> <a href=\"com_raw_material_details.php\"><i class=\"fa fa-reply\"></i> รายละเอียดวัตถุดิบ</a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>ชื่อสถานที่ซื้อ</th>
                    <th>ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  $com_raw_matreial_seller[data]
                </tbody>
                <tfoot>
                  <tr>
                  <tr>
                    <th>ชื่อสถานที่ซื้อ</th>
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