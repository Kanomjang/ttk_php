<?php
include("../lib_org.php");
$title_head_html = "รายละเอียดวัตถุดิบ";
$position_active = '9';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_emp_use = '10405';
if ($_POST['add_com_purchase_request']) {
  $id_crm = base64_decode(base64_decode($_POST['id_crm']));
  $date_use=$_POST['date_use'];
  $yyyymmdd_use = explode("/", $date_use);
  $date_use = "$yyyymmdd_use[2]-$yyyymmdd_use[0]-$yyyymmdd_use[1]";
  $y_order=date("Y");
  $m_order=date("m");
  $d_order=date("d");
  $date_order="$y_order-$m_order-$d_order";
  $id_dept=$_POST['id_dept'];
  $unit_quantity=$_POST['unit_quantity'];
  $comment_cpr=$_POST['comment_cpr'];
  $title_cpr=$_POST['title_cpr'];
  $check_no_order= check_no_order($date_order);
  if($check_no_order=="0"){
    $thai_order=(date("Y")+543);
    $no_order="$thai_order"."0001";
  }else{
    $no_order=$check_no_order+1;
  }
  insert_com_purchase_request($no_order,$title_cpr, $id_dept, $date_order,$date_use,$id_crm,$unit_quantity,$comment_cpr);
header("location: http://$host_www/main_php/com_raw_materials.php");
}

$select_com_raw_material = select_com_raw_material();
print_asset_dept($select_com_raw_material, $title_head_html, $position_active);
//}else{echo "โปรด Login ก่อน";}
##############################
function insert_com_purchase_request($no_order,$title_cpr, $id_dept, $date_order,$date_use,$id_crm,$unit_quantity,$comment_cpr)//เพิ่มใบขอซื้อ
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into com_purchase_request(no_order,title_cpr,id_dept,date_order,date_use,id_crm,unit_quantity,comment_cpr,id_use)
   values('$no_order','$title_cpr','$id_dept','$date_order','$date_use','$id_crm','$unit_quantity','$comment_cpr','$id_emp_use')";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function check_no_order($date_order)
{
  global $host, $user, $passwd, $database;
  $check_no_order = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT no_order from com_purchase_request WHERE com_purchase_request.date_order='$date_order' ORDER BY id_cpr desc LIMIT 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $check_no_order = $row["no_order"];
  }
  mysqli_close($connect);
  return $check_no_order;
}
#################################################
function select_com_raw_material()
{
  global $host, $user, $passwd, $database;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_crm,com_raw_material.id_crmd,com_raw_material_details.name_raw_material_details,min_stock FROM com_raw_material,com_raw_material_details WHERE com_raw_material.id_crmd = com_raw_material_details.id_crmd order by id_crm ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crm = $row["id_crm"];
    $id_crmd = $row["id_crmd"];
    $name_raw_material_details = $row["name_raw_material_details"];
    $min_stock = $row["min_stock"];
    $encode_id_crm = base64_encode(base64_encode("$id_crm"));
    $check_pr = check_pr($id_crm);
    $print[$a] = "
		<TR>
            <TD>&nbsp;&nbsp;$name_raw_material_details</TD>
            <TD>&nbsp;&nbsp;9999</TD>
            <TD>$check_pr</TD>
            <TD><A href='com_add_order_raw_material.php?com_add_raw_material=$encode_id_crm'  class=\"btn btn-info\" role=\"button\">ใบขอซื้อ (PR)</A></TD>
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
function check_pr($id_crm)
{
  global $host, $user, $passwd, $database;
  $i=0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_purchase_request.id_cpr,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.approve FROM com_purchase_request WHERE  com_purchase_request.finish ='0' AND com_purchase_request.id_crm = '$id_crm'";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $id_cpr = $row["id_cpr"];
    $id_crmse = $row["id_crmse"];
    $unit_quantity = $row["unit_quantity"];
    $approve = $row["approve"];
    $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
    if($unit_quantity >'0'){
      if($id_crmse==""){
        $print_check_pr[$i]="<A href='com_purchase_request_report.php?com_purchase_request_report=$encode_id_cpr'  class=\"btn btn-warning\" role=\"button\">$unit_quantity</A>";
      }else{
        if($approve =='1'){
         $print_check_pr[$i]="<A href='com_purchase_request_report.php?com_purchase_request_report=$encode_id_cpr'  class=\"btn btn-success\" role=\"button\">$unit_quantity</A>";
        }else{
        $print_check_pr[$i]="<A href='com_purchase_request_report.php?com_purchase_request_report=$encode_id_cpr'  class=\"btn btn-warning\" role=\"button\">$unit_quantity</A>";
        }
      }
    }else{      
      $print_check_pr[$i]="";
    }
    $i++;
  }
  if (count($print_check_pr) == '0') {
    $print_check_pr[0] = "";
  }

  mysqli_close($connect);
  $check_pr = implode("\n", $print_check_pr);

  return $check_pr;
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
        คลังวัตถุดิบ
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">คลังวัตถุดิบ</li>
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
              <h3 class=\"box-title\">คลังวัตถุดิบ &nbsp;  &nbsp;   $text_ledger</h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                    <li><a href=\"com_raw_material.php\"><i class=\"fa fa-share\"></i> เพิ่มรายละเอียดวัตถุดิบ</a></li>
                </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>ชื่อวัตถุดิบ</th>
                    <th>จำนวน Stock</th>
                    <th>รอขอซื้อ</th>
                    <th>ใบขอซื้อ (PR)</th>
                  </tr>
                </thead>
                <tbody>
                  $select_com_raw_material[data]
                </tbody>
                <tfoot>
                 <tr>
                  <th>ชื่อวัตถุดิบ</th>
                  <th>จำนวน Stock</th>
                  <th>รอขอซื้อ</th>
                  <th>ใบขอซื้อ (PR)</th>
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