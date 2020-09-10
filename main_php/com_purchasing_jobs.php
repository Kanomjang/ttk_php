<?php
include("../lib_org.php");
$title_head_html = "งานจัดซื้อ";
$position_active = '10';
//if($_SESSION['id_emp_use']){ 
$id_emp_use = $_SESSION['id_emp_use'];
$id_emp_use='1002';
if ($_POST['add_com_purchase_request']) {
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  $id_crmse=$_POST['id_crmse'];
  $unit_price=$_POST['unit_price'];
  $id_quotation=$_POST['id_quotation'];
  $trade_credit=$_POST['trade_credit'];
  $payment_term=$_POST['payment_term'];
  $discounts=$_POST['discounts'];
  $date_delivery=$_POST['date_delivery'];
  $unit=$_POST['unit'];
  $vat=$_POST['vat'];
  $place_delivery=$_POST['place_delivery'];
  $comment_purchasing_jobs=$_POST['comment_purchasing_jobs'];
  $yyyymmdd_use = explode("/", $date_delivery);
  $date_delivery = "$yyyymmdd_use[2]-$yyyymmdd_use[0]-$yyyymmdd_use[1]";
if($_FILES["file_quotation"]!=""){
    // Check if file was uploaded without errors
    if(isset($_FILES["file_quotation"]) && $_FILES["file_quotation"]["error"] == 0){
      foreach (glob("$id_cpr.*") as $filename1) {
        unlink("file_quotation/".$filename1);
     }
     
      unlink("file_quotation/$id_cpr.*");

      $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "pdf" => "application/pdf");
      $filename = $_FILES["file_quotation"]["name"];
      $filetype = $_FILES["file_quotation"]["type"];
      $filesize = $_FILES["file_quotation"]["size"];
      $file_name=explode(".",$filename);
      $filename="$id_cpr.$file_name[1]";
  //print"$file_name[1],$filename,$filetype,$filesize <br>";exit;
      // Validate file extension
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
  
      // Validate file size - 10MB maximum
      $maxsize = 10 * 1024 * 1024;
      if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
  
      // Validate type of the file
      if(in_array($filetype, $allowed)){
          // Check whether file exists before uploading it
          if(file_exists("file_quotation/" . $filename)){
              echo $filename . " is already exists.";
          } else{
              if(move_uploaded_file($_FILES["file_quotation"]["tmp_name"], "file_quotation/" . $filename)){

                  echo "Your file was uploaded successfully.";
              }else{

                 echo "File is not uploaded";
              }
              
          } 
      } else{
          echo "Error: There was a problem uploading your file. Please try again."; 
      }
  } else{
      echo "Error: " . $_FILES["file_quotation"]["error"];
  }
}else{}
update_com_purchase_request($id_cpr,$id_crmse,$unit_price,$id_quotation,$filename,$date_delivery,$unit,$trade_credit,$payment_term,$discounts,$vat,$place_delivery,$comment_purchasing_jobs,$id_emp_use);
header("location: http://$host_www/main_php/com_purchasing_jobs.php");
}

$com_purchase_request = com_purchase_request();
$com_history_purchase_request = com_history_purchase_request();
print_asset_dept($com_purchase_request,$com_history_purchase_request, $title_head_html, $position_active);
//}else{echo "โปรด Login ก่อน";}
##############################
function update_com_purchase_request($id_cpr,$id_crmse,$unit_price,$id_quotation,$filename,$date_delivery,$unit,$trade_credit,$payment_term,$discounts,$vat,$place_delivery,$comment_purchasing_jobs,$id_emp_use)//เพิ่มการสำรวจราคา
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update com_purchase_request set id_quotation='$id_quotation',file_quotation='$filename',date_delivery='$date_delivery',unit='$unit',trade_credit='$trade_credit',payment_term='$payment_term',discounts='$discounts',vat='$vat',place_delivery='$place_delivery',comment_purchasing_jobs='$comment_purchasing_jobs',approve='0',id_crmse='$id_crmse', unit_price='$unit_price',id_purchasing_jobs='$id_emp_use',date_purchasing_jobs=NOW() where id_cpr='$id_cpr'";
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
function com_purchase_request()
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_purchasing_jobs,com_purchase_request.id_crm,com_purchase_request.approve,com_purchase_request.id_cpr,com_purchase_request.date_order,com_purchase_request.date_use,com_purchase_request.title_cpr,com_purchase_request.id_dept,com_purchase_request.id_use FROM com_purchase_request WHERE com_purchase_request.approve !='1'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $approve = $row["approve"];
    $id_cpr = $row["id_cpr"];
    $date_order = $row["date_order"];
    $date_use = $row["date_use"];
    $title_cpr = $row["title_cpr"];
    $id_dept = $row["id_dept"];
    $id_use = $row["id_use"];
    $id_purchasing_jobs = $row["id_purchasing_jobs"];
    
    $name_dept=name_dept($id_dept);
    $name_user=name_user($id_use);
    $date01 = new DateTime($date_order);
    $dd01=$date01->format('j');
    $mm01=$monthTH[$date01->format('n')];
    $yy01=$date01->format('Y')+543;
    $day_order="$dd01 $mm01 $yy01";
    $date02 = new DateTime($date_use);
    $dd02=$date02->format('j');
    $mm02=$monthTH[$date02->format('n')];
    $yy02=$date02->format('Y')+543;
    $day_use="$dd02 $mm02 $yy02";
    $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
    if($id_purchasing_jobs!=''){
      $text_purchasing="";
    }else{
      
    $print[$a] = "
		<TR>
            <TD>&nbsp;&nbsp;$day_order</TD>
            <TD>&nbsp;&nbsp;$day_use</TD>
            <TD>&nbsp;&nbsp;$title_cpr</TD>
            <TD>&nbsp;&nbsp;$name_dept</TD>
            <TD>&nbsp;&nbsp;$name_user[name]</TD>
            <TD><A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-warning\" role=\"button\">สำรวจราคา</A></TD>
		</TR>";

    }
    if($approve=='2'){

      $print[$a] = "
      <TR>
              <TD>&nbsp;&nbsp;$day_order</TD>
              <TD>&nbsp;&nbsp;$day_use</TD>
              <TD>&nbsp;&nbsp;$title_cpr</TD>
              <TD>&nbsp;&nbsp;$name_dept</TD>
              <TD>&nbsp;&nbsp;$name_user[name]</TD>
              <TD><A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-danger\" role=\"button\">สำรวจราคาใหม่</A></TD>
      </TR>";

    }
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
function count_suggestion($id_cpr)
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT count(id_cs) as count_suggestion FROM com_suggestion WHERE com_suggestion.id_cpr='$id_cpr'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $count_suggestion = $row["count_suggestion"];
  }
  return $count_suggestion;
}
#################################################
function com_history_purchase_request()
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_purchasing_jobs,com_purchase_request.id_crm,com_purchase_request.id_cpr,com_purchase_request.approve,com_purchase_request.date_order,com_purchase_request.date_use,com_purchase_request.title_cpr,com_purchase_request.id_dept,com_purchase_request.id_use FROM com_purchase_request WHERE  id_purchasing_jobs !='' AND com_purchase_request.approve!='2' AND com_purchase_request.date_order between CURDATE()-30 and CURDATE() ORDER BY date_order DESC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_purchasing_jobs = $row["id_purchasing_jobs"];
    $id_crm = $row["id_crm"];
    $id_cpr = $row["id_cpr"];
    $date_order = $row["date_order"];
    $date_use = $row["date_use"];
    $title_cpr = $row["title_cpr"];
    $id_dept = $row["id_dept"];
    $id_use = $row["id_use"];
    $approve = $row["approve"];
    $name_dept=name_dept($id_dept);
    $name_user=name_user($id_use);
    $date01 = new DateTime($date_order);
    $dd01=$date01->format('j');
    $mm01=$monthTH[$date01->format('n')];
    $yy01=$date01->format('Y')+543;
    $day_order="$dd01 $mm01 $yy01";
    $date02 = new DateTime($date_use);
    $dd02=$date02->format('j');
    $mm02=$monthTH[$date02->format('n')];
    $yy02=$date02->format('Y')+543;
    $day_use="$dd02 $mm02 $yy02";
    $count_suggestion=count_suggestion($id_cpr);
    $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
    if($id_purchasing_jobs !=''){
      if($approve=='1'){
        $button_approve="<A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-success\" role=\"button\">อนุมัติแล้ว</A>";
      }elseif($approve=='2'){
        $button_approve="<A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-danger\" role=\"button\">ไม่อนุมัติ</A>"; 
      }else{
        $button_approve="<A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-info\" role=\"button\">รอการอนุมัติ</A>";
      }
    }else{
      $button_approve="<A href='com_add_purchasing_jobs.php?purchasing_jobs=$encode_id_cpr'  class=\"btn btn-info\" role=\"button\">รอการอนุมัติ</A>";
    }
      $print[$a] = "
		<TR>
            <TD>&nbsp;&nbsp;$day_order</TD>
            <TD>&nbsp;&nbsp;$day_use</TD>
            <TD>&nbsp;&nbsp;$title_cpr</TD>
            <TD>&nbsp;&nbsp;$name_dept</TD>
            <TD>&nbsp;&nbsp;$name_user[name]</TD>
            <TD>&nbsp;&nbsp; $button_approve</TD>
		</TR>";
    $a++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $com_history_purchase_request['count_asset'] = count($print);
  #print"$com_purchase_request[count_asset]<br>";
  $com_history_purchase_request['data'] = implode("\n", $print);
  return $com_history_purchase_request;
}
#################################################
function print_asset_dept($com_purchase_request,$com_history_purchase_request, $title_head_html, $position_active)
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
      จัดทำข้อมูลเพื่อออกใบขอซื้อ/ใบสั่งซื้อ
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li class=\"active\">ใบขอซื้อ/ใบสั่งซื้อ</li>
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
              <h3 class=\"box-title\">สำรวจราคาสินค้าจากใบขอซื้อ</h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a href=\"com_history_document_approval.php\"><i class=\"fa fa-share\"></i> ประวัติการอนุมัติเอกสารทั้งหมด</a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>วันที่ขอ</th>
                    <th>วันที่ใช้</th>
                    <th>เรื่อง</th>
                    <th>หน่วยงาน</th>
                    <th>ผู้ขอซื้อ</th>
                    <th>สำรวจราคา</th>
                  </tr>
                </thead>
                <tbody>
                  $com_purchase_request[data]
                </tbody>
                <tfoot>
                 <tr>
                 <th>วันที่ขอ</th>
                 <th>วันที่ใช้</th>
                 <th>เรื่อง</th>
                 <th>หน่วยงาน</th>
                 <th>ผู้ขอซื้อ</th>
                 <th>สำรวจราคา</th>
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






    <!-- Main content -->
    <section class=\"content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <div class=\"box\">
            <div class=\"box-header\">
              <h3 class=\"box-title\">ประวัติย้อนหลัง 30 วัน</h3>
              <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                </button>
                <div class=\"btn-group\">
                  <button type=\"button\" class=\"btn btn-box-tool dropdown-toggle\" data-toggle=\"dropdown\">
                    <i class=\"fa fa-wrench\"></i></button>
                  <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a href=\"com_history_document_approval.php\"><i class=\"fa fa-share\"></i> ประวัติการอนุมัติเอกสาร</a></li>
                  </ul>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <table id=\"example1\" class=\"table table-bordered table-striped\">
                <thead>
                  <tr>
                    <th>วันที่ขอ</th>
                    <th>วันที่ใช้</th>
                    <th>เรื่อง</th>
                    <th>หน่วยงาน</th>
                    <th>ผู้ขอซื้อ</th>
                    <th>อนุมัติ</th>
                  </tr>
                </thead>
                <tbody>
                  $com_history_purchase_request[data]
                </tbody>
                <tfoot>
                 <tr>
                 <th>วันที่ขอ</th>
                 <th>วันที่ใช้</th>
                 <th>เรื่อง</th>
                 <th>หน่วยงาน</th>
                 <th>ผู้ขอซื้อ</th>
                 <th>อนุมัติ</th>
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