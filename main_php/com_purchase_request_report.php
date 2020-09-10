<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$position_active = '9';
$dept_position_active = dept_position_active($position_active);
if ($_GET['com_purchase_request_report']) {
  $id_cpr = $_GET['com_purchase_request_report'];
  $id_cpr = base64_decode(base64_decode("$id_cpr"));
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}

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
#################################################
function select_com_purchase_request_report($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_use_approve,date_approve,id_purchasing_jobs,date_purchasing_jobs,id_crm,no_order,com_purchase_request.title_cpr,com_purchase_request.date_order,com_purchase_request.date_use,com_purchase_request.id_dept,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.unit_price,com_purchase_request.comment_cpr,com_purchase_request.approve,com_purchase_request.id_use FROM com_purchase_request WHERE com_purchase_request.id_cpr='$id_cpr'";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $select_com_purchase_request_report['id_use_approve'] = $row["id_use_approve"];
    $select_com_purchase_request_report['date_approve'] = $row["date_approve"];
    $select_com_purchase_request_report['id_purchasing_jobs'] = $row["id_purchasing_jobs"];
    $select_com_purchase_request_report['date_purchasing_jobs'] = $row["date_purchasing_jobs"];
    $select_com_purchase_request_report['no_order'] = $row["no_order"];
    $select_com_purchase_request_report['title_cpr'] = $row["title_cpr"];
    $select_com_purchase_request_report['date_order'] = $row["date_order"];
    $select_com_purchase_request_report['date_use'] = $row["date_use"];
    $select_com_purchase_request_report['id_dept'] = $row["id_dept"];
    $select_com_purchase_request_report['id_crm'] = $row["id_crm"];
    $select_com_purchase_request_report['id_crmse'] = $row["id_crmse"];
    $select_com_purchase_request_report['unit_quantity'] = $row["unit_quantity"];
    $select_com_purchase_request_report['unit_price'] = $row["unit_price"];
    $select_com_purchase_request_report['comment_cpr'] = $row["comment_cpr"];
    $select_com_purchase_request_report['approve'] = $row["approve"];
    $select_com_purchase_request_report['id_use'] = $row["id_use"];
  }
  mysqli_close($connect);
  return $select_com_purchase_request_report;
}
##############################
function select_gdept($id_loc)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_dept,name_dept from dept_edu order by id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    if ($id_loc == $id_dept) {
      $print[$i] = "<option value='$id_dept' selected>$name_dept</option>";
    } else {
      $print[$i] = "<option value='$id_dept'>$name_dept</option>";
    }
    $i++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_gdept  = implode("\n", $print);
  return $select_gdept;
}
##############################
function select_crm($id_crm)
{
  global $host, $user, $passwd, $database;
  $select_crm = "";
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_raw_material_details.name_raw_material_details FROM com_raw_material,com_raw_material_details WHERE com_raw_material.id_crm='$id_crm' AND com_raw_material.id_crmd = com_raw_material_details.id_crmd";
  print "$sql_query <br>";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_crm = $row["name_raw_material_details"];
  }
  mysqli_close($connect);
  return $select_crm;
}
##############################
function select_crmd($id_crmse)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_accounts_payable.name_accounts_payable FROM com_raw_matreial_seller,com_accounts_payable WHERE com_raw_matreial_seller.id_crmse ='$id_crmse' AND com_accounts_payable.id_cap = com_raw_matreial_seller.id_cap";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_crmd = $row["name_accounts_payable"];
  }
  mysqli_close($connect);
  return $select_crmd;
}
##############################
function select_position_emp($id_emp)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT name_position FROM position_emp,position_edu WHERE position_edu.id_position = position_emp.id_position AND position_emp.id_emp='$id_emp'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_position_emp = $row["name_position"];
  }
  mysqli_close($connect);
  return $select_position_emp;
}
######################หน้าแสดง###########################
function com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active)
{
  global $dayTH, $monthTH;
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  if ($select_com_purchase_request_report['id_crmse'] > '0') {
    $select_crmd = select_crmd($select_com_purchase_request_report['id_crmse']);
    $select_crmd = "( $select_crmd )";
  } else {
    $select_crmd = "";
  }

  $select_crm = select_crm($select_com_purchase_request_report['id_crm']);
  $name_dept = name_dept($select_com_purchase_request_report['id_dept']);
  $name_user = name_user($select_com_purchase_request_report['id_use']);

  if ($select_com_purchase_request_report['id_purchasing_jobs'] != '') {
    $name_purchasing_jobs = name_user($select_com_purchase_request_report['id_purchasing_jobs']);
    $date_purchasing_jobs = $select_com_purchase_request_report['date_purchasing_jobs'];
    $date03 = new DateTime($date_purchasing_jobs);
    $dd03 = $date03->format('j');
    $mm03 = $monthTH[$date03->format('n')];
    $yy03 = $date03->format('Y') + 543;
    $day_purchasing_jobs = "$dd03 $mm03 $yy03";

    $text_name_purchasing_jobs = "(&nbsp;&nbsp;$name_purchasing_jobs[name]&nbsp;&nbsp;)<br>
    วันที่ $day_purchasing_jobs <br>";
  } else {
    $text_name_purchasing_jobs = "
    (.................................................)<br>
    วันที่ ......./........................./............<br>";

    $text_name_purchasing_jobs = "";
  }

  if ($select_com_purchase_request_report['id_use_approve'] != '') {
    $name_approve = name_user($select_com_purchase_request_report['id_use_approve']);
    $date_approve = $select_com_purchase_request_report['date_approve'];
    $date03 = new DateTime($date_approve);
    $dd03 = $date03->format('j');
    $mm03 = $monthTH[$date03->format('n')];
    $yy03 = $date03->format('Y') + 543;
    $day_approve = "$dd03 $mm03 $yy03";

    $text_name_approve = "(&nbsp;&nbsp;$name_approve[name]&nbsp;&nbsp;)<br>
    วันที่ $day_approve <br>";
  } else {
    $text_name_approve = "
    (.................................................)<br>
    วันที่ ......./........................./............<br>";

    $text_name_approve = "";
  }


  $date_order = $select_com_purchase_request_report['date_order'];
  $date01 = new DateTime($date_order);
  $dd01 = $date01->format('j');
  $mm01 = $monthTH[$date01->format('n')];
  $yy01 = $date01->format('Y') + 543;
  $day_order = "$dd01 $mm01 $yy01";

  $date_use = $select_com_purchase_request_report['date_use'];
  $date02 = new DateTime($date_use);
  $dd02 = $date02->format('j');
  $mm02 = $monthTH[$date02->format('n')];
  $yy02 = $date02->format('Y') + 543;
  $day_use = "$dd02 $mm02 $yy02";
  $select_position_emp = select_position_emp($select_com_purchase_request_report['id_use']);
  $unit_quantity = number_format($select_com_purchase_request_report['unit_quantity'], 2, '.', ',');
  $unit_price = number_format($select_com_purchase_request_report['unit_price'], 2, '.', ',');
  if ($select_com_purchase_request_report['approve'] == '1') {
    $approve_box01 = "<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  } elseif ($select_com_purchase_request_report['approve'] == '2') {
    $approve_box01 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  } else {
    $approve_box01 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  }

  print "
<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>AdminLTE 2 | Advanced form elements</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
  <!-- daterange picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-daterangepicker/daterangepicker.css\">
  <!-- bootstrap datepicker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css\">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel=\"stylesheet\" href=\"../plugins/iCheck/all.css\">
  <!-- Bootstrap Color Picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css\">
  <!-- Bootstrap time Picker -->
  <link rel=\"stylesheet\" href=\"../plugins/timepicker/bootstrap-timepicker.min.css\">
  <!-- Select2 -->
  <link rel=\"stylesheet\" href=\"../bower_components/select2/dist/css/select2.min.css\">
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
";
  include("../header.php");
  include("../sidebar.php");
  print "

 <div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
      ออกใบขอซื้อ (PR) &nbsp;&nbsp;&nbsp; <strong><u>เรื่อง</u> การขอสั่งซื้อ $select_crm $select_crmd</strong>
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"com_raw_materials.php\">คลังวัตถุดิบ</a></li>
        <li class=\"active\">ออกใบขอซื้อ (PR)</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <!-- /.row -->
    <!-- Main content -->
    <section class=\"content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <div class=\"box\">
            <div class=\"box-header\">
              <div class=\"col-xs-10\"></div>
              <div class=\"col-xs-2\">
                <h3 class=\"box-title\">เลขที่ PR.No $select_com_purchase_request_report[no_order]</h3>
              </div>
            </div>
            <!-- /.box-header -->
            
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='com_raw_materials.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-12\">
                        <table width='100%' border='1' cellpadding='0' cellspacing='1'>
                        <tr>
                         <td width='15%' align='right'><B>หน่วยงาน : &nbsp;&nbsp;&nbsp;</B></td>
                         <td width='35%'>&nbsp;&nbsp;&nbsp;$name_dept</td>
                         <td width='15%' align='right'><B>ผู้ขอซื้อ : &nbsp;&nbsp;&nbsp;</B></td>
                         <td width='35%'>&nbsp;&nbsp;&nbsp;$name_user[name]</td>
                        </tr>
                        <tr>
                         <td width='15%' align='right'><B>วันที่ต้องการใช้ : &nbsp;&nbsp;&nbsp;</B></td>
                         <td width='35%'>&nbsp;&nbsp;&nbsp;$day_use</td>
                         <td width='15%' align='right'><B>ตำแหน่ง : &nbsp;&nbsp;&nbsp;</B></td>
                         <td width='35%'>&nbsp;&nbsp;&nbsp;$select_position_emp</td>
                        </tr>
                        <tr>
                         <td width='15%' rowspan='2' align='right'><B>เรื่อง : &nbsp;&nbsp;&nbsp;</B></td>
                         <td rowspan='2' width='35%'>&nbsp;&nbsp;&nbsp;$select_com_purchase_request_report[title_cpr]</td>
                         <td width='15%' align='right'><B>วันที่ : &nbsp;&nbsp;&nbsp;</B></td>
                         <td width='35%'>&nbsp;&nbsp;&nbsp;$day_order</td>
                        </tr>
                        <tr>
                        <td width='15%' align='right'><B>โทรศัพท์ : &nbsp;&nbsp;&nbsp;</B></td>
                        <td width='35%'>&nbsp;&nbsp;&nbsp;$name_user[tel]</td>
                       </tr>
                      </table><br>
                      <table width='100%' border='1' cellpadding='0' cellspacing='1'>
                      <tr align='center'bgcolor='dddddd'>
                       <td width='5%'><B>ลำดับที่</B></td>
                       <td width='35%'><B>รายการ</B></td>
                       <td width='15%'><B>จำนวน/หน่วย</B></td>
                       <td width='15%'><B>ราคา/หน่วย</B></td>
                       <td width='30%'><B>หมายเหตุ/จุดประสงค์</B></td>
                      </tr>
                      <tr>
                       <td align='center'>1.</td>
                       <td>&nbsp;&nbsp;&nbsp;$select_crm $select_crmd</td>
                       <td align='center'>$unit_quantity</td>
                       <td align='center'>$unit_price</td>
                       <td>&nbsp;&nbsp;&nbsp;$select_com_purchase_request_report[comment_cpr]</td>
                      </tr>
                      <td align='center'></td>
                      <td>&nbsp;</td>
                      <td align='center'></td>
                      <td align='center'></td>
                      <td>&nbsp;</td>
                     </tr>
                   </table>
                   <br><br><br>
                   <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                   <tr align=\"center\">
                    <td width=\"30%\"><br><br><br><br>.................................................<br>
                    (&nbsp;&nbsp;$name_user[name]&nbsp;&nbsp;)<br>
                    วันที่ $day_order
                    <br>ผู้ขอซื้อ</td>
                    <td width=\"40%\"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     $approve_box01 &nbsp;อนุมัติ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     $approve_box02 &nbsp;ไม่อนุมัติ<br><br><br>
                     .................................................<br>
                     $text_name_approve ผู้อนุมัติ
                    </td>
                    <td width=\"30%\"><br><br><br><br>.................................................<br>
                    $text_name_purchasing_jobs ฝ่ายจัดซื้อ
                    </td>
                  </tr>
                 </table>
             
                        </div><!-- /.col-md-10 -->
                </div><!-- End Row-->

                <div class=\"row\">
                  <div class=\"col-md-5\">
                  </div>
                  <div class=\"col-md-7\"><br>
                    <A href='../../tcpdf/examples/com_pdf_purchase_request_report.php?com_purchase_request_report=$encode_id_cpr'  class=\"btn btn-success\" role=\"button\">Print to PDF</A>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='<<Back' ONCLICK='window.history.back();'>
                  </div>
                </div>
            </div><!-- End Row-->
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  </form>
  </div>

<!-- jQuery 3 -->
<script src=\"../bower_components/jquery/dist/jquery.min.js\"></script>
<!-- Bootstrap 3.3.7 -->
<script src=\"../bower_components/bootstrap/dist/js/bootstrap.min.js\"></script>
<!-- Select2 -->
<script src=\"../bower_components/select2/dist/js/select2.full.min.js\"></script>
<!-- InputMask -->
<script src=\"../plugins/input-mask/jquery.inputmask.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.date.extensions.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.extensions.js\"></script>
<!-- date-range-picker -->
<script src=\"../bower_components/moment/min/moment.min.js\"></script>
<script src=\"../bower_components/bootstrap-daterangepicker/daterangepicker.js\"></script>
<!-- bootstrap datepicker -->
<script src=\"../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\"></script>
<!-- bootstrap color picker -->
<script src=\"../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js\"></script>
<!-- bootstrap time picker -->
<script src=\"../plugins/timepicker/bootstrap-timepicker.min.js\"></script>
<!-- SlimScroll -->
<script src=\"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\"></script>
<!-- iCheck 1.0.1 -->
<script src=\"../plugins/iCheck/icheck.min.js\"></script>
<!-- FastClick -->
<script src=\"../bower_components/fastclick/lib/fastclick.js\"></script>
<!-- AdminLTE App -->
<script src=\"../dist/js/adminlte.min.js\"></script>
<!-- AdminLTE for demo purposes -->
<script src=\"../dist/js/demo.js\"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    })
    $('#datepicker2').datepicker({
      autoclose: true
    })
    $('#datepicker3').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type=\"checkbox\"].minimal, input[type=\"radio\"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type=\"checkbox\"].minimal-red, input[type=\"radio\"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type=\"checkbox\"].flat-red, input[type=\"radio\"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>

";
  include("../footer.php");
  exit;
}
#################################################