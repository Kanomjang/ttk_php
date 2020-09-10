<?php
include("../lib_org.php");
$title_head_html = "ตรวจรับสินค้า";
$position_active = '14';
//$dept_position_active = dept_position_active($position_active);
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
if ($_POST['add_com_purchase_request']) {
  $id_crm = base64_decode(base64_decode($_POST['id_crm']));
  $date_use=$_POST['date_use'];
  $yyyymmdd_use = explode("/", $date_use);
  $date_use = "$yyyymmdd_use[2]-$yyyymmdd_use[0]-$yyyymmdd_use[1]";
  $y_order=date("Y");
  $m_order=date("m");
  $d_order=date("d");
  $date_delivery="$y_order-$m_order-$d_order";
  $id_dept=$_POST['id_dept'];
  $id_crmse=$_POST['id_crmse'];
  $unit_quantity=$_POST['unit_quantity'];
  $unit_price=$_POST['unit_price'];
  $comment_cpr=$_POST['comment_cpr'];
  $title_cpr=$_POST['title_cpr'];
  $check_no_order= check_no_order($date_delivery);
  if($check_no_order=="0"){
    $thai_order=(date("Y")+543);
    $no_order="$thai_order"."0001";
  }else{
    $no_order=$check_no_order+1;
  }
insert_com_purchase_request($no_order,$title_cpr, $id_dept, $date_delivery,$date_use,$id_crmse,$unit_quantity,$unit_price,$comment_cpr);
header("location: http://$host_www/main_php/com_purchase_request.php");
}
$com_purchase_request = com_purchase_request();
print_asset_dept($com_purchase_request, $title_head_html, $position_active);
//}else{echo "โปรด Login ก่อน";}
#################################################
function dept_position_active($position_active)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_dept from dept_position where position_active='$position_active'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $dept_position_active = $row["id_dept"];
  }
  mysqli_close($connect);
  return $dept_position_active;
}
##############################
function insert_com_purchase_request($no_order,$title_cpr, $id_dept, $date_delivery,$date_use,$id_crmse,$unit_quantity,$unit_price,$comment_cpr)//เพิ่มใบขอซื้อ
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into com_purchase_request(no_order,title_cpr,id_dept,date_delivery,date_use,id_crmse,unit_quantity,unit_price,comment_cpr,id_use)
   values('$no_order','$title_cpr','$id_dept','$date_delivery','$date_use','$id_crmse','$unit_quantity','$unit_price','$comment_cpr','$id_emp_use')";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function check_no_order($date_delivery)
{
  global $host, $user, $passwd, $database;
  $check_no_order = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT no_order from com_purchase_request WHERE com_purchase_request.date_delivery='$date_delivery' ORDER BY id_cpo desc LIMIT 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $check_no_order = $row["no_order"];
  }
  mysqli_close($connect);
  return $check_no_order;
}
#################################################
function com_purchase_request()
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_purchase_order.id_cpo,com_purchase_request.date_delivery,com_purchase_request.date_use,com_purchase_request.title_cpr,com_purchase_request.id_dept,com_purchase_request.id_use FROM com_purchase_request,com_purchase_order WHERE com_purchase_request.approve ='1' AND com_purchase_order.id_cpr=com_purchase_request.id_cpr";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_cpo = $row["id_cpo"];
    $date_delivery = $row["date_delivery"];
    $date_use = $row["date_use"];
    $title_cpr = $row["title_cpr"];
    $id_dept = $row["id_dept"];
    $id_use = $row["id_use"];
    $name_dept=name_dept($id_dept);
    $name_user=name_user($id_use);
    $date01 = new DateTime($date_delivery);
    $dd01=$date01->format('j');
    $mm01=$monthTH[$date01->format('n')];
    $yy01=$date01->format('Y')+543;
    $day_delivery="$dd01 $mm01 $yy01";
    $encode_id_cpo = base64_encode(base64_encode("$id_cpo"));
    $print[$a] = "
		<TR>
            <TD>&nbsp;&nbsp;$day_delivery</TD>
            <TD>&nbsp;&nbsp;$title_cpr</TD>
            <TD>&nbsp;&nbsp;$name_dept</TD>
            <TD>&nbsp;&nbsp;$name_user[name]</TD>
            <TD><A href='com_check_products.php?com_purchase_order=$encode_id_cpo'  class=\"btn btn-info\" role=\"button\">ตรวจรับสินค้า</A></TD>
		</TR>";
    $a++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $com_purchase_request['count_asset'] = count($print);
  #print"$com_purchase_request[count_asset]<br>";
  $com_purchase_request['data'] = implode("\n", $print);
  return $com_purchase_request;
}
#################################################
function print_asset_dept($com_purchase_request, $title_head_html, $position_active)
{
  print "
  <!DOCTYPE html>
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
                          ตรวจรับสินค้า
                          <small>Version 1.0</small>
                      </h1>
                      <ol class=\"breadcrumb\">
                          <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
                          <li class=\"active\">ตรวจรับสินค้า</li>
                      </ol>
                  </section>
                  <!-- Info boxes -->
                  <div class=\"row\">
                      <div class=\"col-md-3 col-sm-6 col-xs-12\">
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
                                      <h3 class=\"box-title\">ตรวจรับสินค้า</h3>
                                      <div class=\"box-tools pull-right\">
                                          <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i
                                                  class=\"fa fa-minus\"></i>
                                          </button>
                                          <div class=\"btn-group\">
                                              <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\"
                                                  data-toggle=\"dropdown\">
                                                  <i class=\"fa fa-wrench\"></i></button>
                                              <ul class=\"dropdown-menu\" role=\"menu\">
                                                  <li><a href=\"com_history_document_approval.php\"><i class=\"fa
                                                              fa-share\"></i>
                                                          ประวัติการตรวจรับสินค้าทั้งหมด</a></li>
                                              </ul>
                                          </div>
                                      </div>
  
                                  </div>
                                  <!-- /.box-header -->
                                  <div class=\"box-body\">
                                      <table id=\"example1\" class=\"table table-bordered table-striped\">
                                          <thead>
                                              <tr>
                                                  <th>วันนำส่งของ</th>
                                                  <th>เรื่อง</th>
                                                  <th>หน่วยงาน</th>
                                                  <th>ผู้ขอซื้อ</th>
                                                  <th>ตรวจรับสินค้า</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              $com_purchase_request[data]
                                          </tbody>
                                          <tfoot>
                                              <tr>
                                                  <th>วันนำส่งของ</th>
                                                  <th>เรื่อง</th>
                                                  <th>หน่วยงาน</th>
                                                  <th>ผู้ขอซื้อ</th>
                                                  <th>ตรวจรับสินค้า</th>
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
          $(function() {
              $('#example1').DataTable({
                  \"order\": [ [0, \"desc\"]]
              });
              $('#example2').DataTable({
                  'paging': true,
                  'lengthChange': false,
                  'searching': false,
                  'ordering': true,
                  'info': true,
                  'autoWidth': false
              })
          })
          </script>
  
          <script language='JavaScript'>
          function ShowQR(name_file) {
              myWindow = window.open(name_file, '_blank',
                  'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=220,height=220'
                  )
          }
  
          function ShowNew(name_file) {
              myWindow = window.open(name_file, '_blank',
                  'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=500'
                  )
          }
  
          function ShowDelete(name_file) {
              myWindow = window.open(name_file, '_blank',
                  'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=250'
                  )
          }
  
          function ShowNewPage(name_file) {
              myWindow = window.open(name_file, '_blank',
                  'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')
          }
          </script>";
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