<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='10';
#$dept_position_active=dept_position_active($position_active);
if ($_GET['purchasing_jobs']) {
  $id_cpr = $_GET['purchasing_jobs'];
  $id_cpr = base64_decode(base64_decode("$id_cpr"));
  $select_data_cpr = select_data_cpr($id_cpr);
  purchasing_jobs($select_data_cpr,$id_cpr,$position_active);
}
if ($_GET['edit_com_raw_material']) {
  $id_crm = $_GET['edit_com_raw_material'];
  $id_crm = base64_decode(base64_decode("$id_crm"));
  $select_com_raw_material = select_com_raw_material($id_crm);
  edit_com_raw_material($select_com_raw_material, $id_emp_use, $id_crm, $position_active);
}
if ($_GET['delete_com_raw_material']) {
  $id_crmd = $_GET['delete_com_raw_material'];
  $id_crmd = base64_decode(base64_decode("$id_crmd"));
  $select_com_raw_material = select_com_raw_material($id_crmd);
  delete_com_raw_material($select_com_raw_material,$id_crmd);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}

#################################################
function select_data_cpr($id_cpr)
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;
  $a = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_quotation,file_quotation,date_delivery,unit,trade_credit,payment_term,discounts,vat,comment_purchasing_jobs,unit_price,id_crmse,comment_cpr,no_order,com_purchase_request.id_crm,com_purchase_request.approve,com_purchase_request.date_order,com_purchase_request.date_use,unit_quantity,com_purchase_request.title_cpr,com_purchase_request.id_dept,com_purchase_request.id_use FROM com_purchase_request WHERE com_purchase_request.id_cpr ='$id_cpr'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_data_cpr['comment_cpr'] = $row["comment_cpr"];
    $select_data_cpr['no_order'] = $row["no_order"];
    $select_data_cpr['id_crm'] = $row["id_crm"];
    $select_data_cpr['approve'] = $row["approve"];
    $select_data_cpr['date_order'] = $row["date_order"];
    $select_data_cpr['date_use'] = $row["date_use"];
    $select_data_cpr['title_cpr'] = $row["title_cpr"];
    $select_data_cpr['id_dept'] = $row["id_dept"];
    $select_data_cpr['id_use'] = $row["id_use"];
    $select_data_cpr['unit_quantity'] = $row["unit_quantity"];
    $select_data_cpr['unit_price'] = $row["unit_price"];
    $select_data_cpr['id_crmse'] = $row["id_crmse"];
    $select_data_cpr['date_delivery'] = $row["date_delivery"];
    $select_data_cpr['unit'] = $row["unit"];
    
    $select_data_cpr['id_quotation'] = $row["id_quotation"];
    $select_data_cpr['file_quotation'] = $row["file_quotation"];
    $select_data_cpr['trade_credit'] = $row["trade_credit"];
    $select_data_cpr['payment_term'] = $row["payment_term"];
    $select_data_cpr['discounts'] = $row["discounts"];
    $select_data_cpr['vat'] = $row["vat"];
    $select_data_cpr['comment_purchasing_jobs'] = $row["comment_purchasing_jobs"];



  }
  if($select_data_cpr['date_delivery']!=''){
    $yyyymmdd_use = explode("-", $select_data_cpr['date_delivery']);
    $select_data_cpr['date_delivery'] = "$yyyymmdd_use[1]/$yyyymmdd_use[2]/$yyyymmdd_use[0]";
  }else{$select_data_cpr['date_delivery'] ="";}

  $select_data_cpr['name_dept']=name_dept($select_data_cpr['id_dept']);
  $name_user=name_user($select_data_cpr['id_use']);
  $select_data_cpr['name_user']=$name_user['name'];
  $select_data_cpr['tel']=$name_user['tel'];
  $date01 = new DateTime($select_data_cpr['date_order']);
  $dd01=$date01->format('j');
  $mm01=$monthTH[$date01->format('n')];
  $yy01=$date01->format('Y')+543;
  $select_data_cpr['day_order']="$dd01 $mm01 $yy01";
  $date02 = new DateTime($select_data_cpr['date_use']);
  $dd02=$date02->format('j');
  $mm02=$monthTH[$date02->format('n')];
  $yy02=$date02->format('Y')+543;
  $select_data_cpr['day_use']="$dd02 $mm02 $yy02";
  $select_data_cpr['position_emp']=select_position_emp($select_data_cpr['id_use']);


  mysqli_close($connect);
  return $select_data_cpr;
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
#################################################
function select_com_raw_material($id_crm)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_crmd,min_stock from com_raw_material where id_crm='$id_crm'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_com_raw_material['id_crmd'] = $row["id_crmd"];
    $select_com_raw_material['min_stock'] = $row["min_stock"];
  }
  mysqli_close($connect);
return $select_com_raw_material;
}
#################################################
function print_edit_com_raw_material($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.code_asset='$code_asset' and asset_type.id_crmd=asset_group.id_crmd";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_com_raw_material = $row["name_asset_group"];
  }
  mysqli_close($connect);
  return $print_edit_com_raw_material;
}
##############################
function print_edit_asset_type($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT usage_life_type FROM asset_type where code_asset='$code_asset'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_type = $row["usage_life_type"];
  }
  mysqli_close($connect);
  return $print_edit_asset_type;
}
##############################
function select_input_crm($id_crm,$id_crmse_old)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_crmse,com_accounts_payable.id_cap,com_accounts_payable.name_accounts_payable FROM com_raw_material,com_raw_material_details,com_raw_matreial_seller,com_accounts_payable WHERE com_raw_material.id_crm = '$id_crm' AND 
  com_raw_matreial_seller.id_crmd = com_raw_material_details.id_crmd AND 
  com_accounts_payable.id_cap = com_raw_matreial_seller.id_cap AND 
  com_raw_material_details.id_crmd = com_raw_material.id_crmd";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmse = $row["id_crmse"];
    $name_accounts_payable = $row["name_accounts_payable"];
    if($id_crmse==$id_crmse_old){
      $print[$i] = "<option value='$id_crmse' selected>$name_accounts_payable</option>";
    }else{
      $print[$i] = "<option value='$id_crmse'>$name_accounts_payable</option>";
    }
        $i++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_input_crm  = implode("\n", $print);
  return $select_input_crm;
}
##############################
function select_crm($id_crm)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "SELECT com_raw_material_details.name_raw_material_details FROM com_raw_material,com_raw_material_details WHERE 
  com_raw_material.id_crm = '$id_crm' AND 
  com_raw_material_details.id_crmd = com_raw_material.id_crmd";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_crm = $row["name_raw_material_details"];

  }
  mysqli_close($connect);
  return $select_crm;
}
#################################################
function check_crmd($id_crmd)
{
  global $host, $user, $passwd, $database;
  $check_crmd='0';
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT COUNT(id_crm) from com_raw_material WHERE com_raw_material.id_crmd='$id_crmd'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_crmd = $row["COUNT(id_crm)"];
  }
  mysqli_close($connect);
  return $check_crmd;
}
##############################
function select_edit_crmd($id_crmd_old)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_crmd,name_raw_material_details from com_raw_material_details order by id_crmd ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmd = $row["id_crmd"];
    $name_raw_material_details = $row["name_raw_material_details"];
    if ($id_crmd_old == $id_crmd) {
      $print[$i] = "<option value='$id_crmd' selected>$name_raw_material_details</option>";
    } else {
        $print[$i] = "<option value='$id_crmd'>$name_raw_material_details</option>";
   }
   $i++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_crmd  = implode("\n", $print);
  return $select_crmd;
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
#################################################
function select_com_suggestion($id_cpr)
{
  global $host, $user, $passwd, $database,$dayTH,$monthTH;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_suggestion.comment_suggestion,com_suggestion.id_use,date(com_suggestion.last_update) AS date_com_suggestion,time(com_suggestion.last_update) AS time_com_suggestion FROM com_suggestion WHERE com_suggestion.id_cpr='$id_cpr'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $comment_suggestion = $row["comment_suggestion"];
    $id_use = $row["id_use"];
    $date_com_suggestion = $row["date_com_suggestion"];
    $time_com_suggestion = $row["time_com_suggestion"];
    $name_user=name_user($id_use);
    $date02 = new DateTime($date_com_suggestion);
    $dd02=$date02->format('j');
    $mm02=$monthTH[$date02->format('n')];
    $yy02=$date02->format('Y')+543;
    $day_com_suggestion="$dd02 $mm02 $yy02 : $time_com_suggestion";

    $print[$i]="
    <div class=\"row\">
      <div class=\"col-md-12\">
        <div class=\"box box-success box-solid\">
          <div class=\"box-header with-border\">
            <div class=\"col-md-6 text-left\">
            &nbsp;&nbsp; จาก $name_user[name]</i>
            </div><!-- /.col-md-6 -->
            <div class=\"col-md-6 text-right\">
              <i>$day_com_suggestion</i> &nbsp;&nbsp; &nbsp;&nbsp;
            </div><!-- /.col-md-6 -->
            <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
        <div class=\"box-body\">
          $comment_suggestion
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  ";
    $i++;
  }
  mysqli_close($connect);
  $select_com_suggestion1 = implode("\n", $print);
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  if (count($print) > '0') {
    $select_com_suggestion = "<br>
    <div class=\"row\">
    <div class=\"col-md-2\"></div>

    <div class=\"col-md-8\">
      <div class=\"box box-info box-solid collapsed-box\">
        <div class=\"box-header box-success with-border\">
          <h3 class=\"box-title\">ข้อเสนอแนะ ($i)</h3>

          <div class=\"box-tools pull-right\">
            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-plus\"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class=\"box-body\">
        $select_com_suggestion1

        <form name='theForm' target='_parent' action='com_purchase_order_approval.php' method='POST'>
        <div class=\"box-body\"><!-- //ข้อเสนอแนะ -->
        <div class=\"row\">        <div class=\"col-xs-2\"><label>ข้อเสนอแนะ :</div>
        <div class=\"col-xs-6\"><input type='hidden' name='id_cpr' value='$encode_id_cpr'>
        <input type=\"text\" class=\"form-control\" NAME='comment_suggestion' id='comment_suggestion'  value=\"\">
        </div>
              </label>
              <div class=\"col-xs-4\"><input type=\"submit\" VALUE='ส่งข้อเสนอแนะ' name='send_po_suggestion' class=\"btn btn-primary\"></div>                  
        </div><!-- /.row -->
      </div><!-- /.box-body -->
      </form>


        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

";
  }else{$select_com_suggestion = "";}


  return $select_com_suggestion;
}
#################################################
function select_cpo($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_purchase_order.no_cpo FROM com_purchase_order WHERE id_cpr='$id_cpr'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_cpo = $row["no_cpo"];
  }
  mysqli_close($connect);
  return $select_cpo;
}
######################หน้าแสดง###########################
function purchasing_jobs($select_data_cpr,$id_cpr,$position_active)
{
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  $select_input_crm=select_input_crm($select_data_cpr['id_crm'],$select_data_cpr['id_crmse']);
  $select_crm=select_crm($select_data_cpr['id_crm']);
  $select_cpo=select_cpo($id_cpr);
  if($select_cpo!=''){
    $type_disabled="disabled";
    $text_disabled="<font color=\"#FF0000\"><i>( ได้ทำการตรวจสอบแล้วไม่สามารถแก้ไขข้อมูลได้ )</i></font>";
    $type_button="";
    
  }else{
    $type_disabled="";
    $text_disabled="";
    $type_button="<input type=\"submit\" VALUE='Submit' name='add_com_purchase_request' class=\"btn btn-primary\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

  }
  if($select_data_cpr['file_quotation']!=""){
    $text_file_quotation="<a href=\"file_quotation/$select_data_cpr[file_quotation]\">File Quotation.</a>";
  }else{
    $text_file_quotation="";
  }
  if($select_data_cpr['place_delivery']==""){$select_data_cpr['place_delivery']="บริษัท ที.ที.เค. ฟีดมิลล์ จำกัด เลขที่ 33/1 ม.4 ต.บางตีนเป็ด อ.เมือง จ.ฉะเชิงเทรา 24000.";}
  $select_com_suggestion=select_com_suggestion($id_cpr);
  //  $time=time();
//  $thai_date_fullmonth=thai_date_fullmonth($time);
  print"
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
  print"

  <div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
      <!-- Content Header (Page header) -->
      <section class=\"content-header\">
          <h1>
              งานจัดซื้อ
              <small>Version 1.0</small>
          </h1>
          <ol class=\"breadcrumb\">
              <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
              <li class=\"active\">สำรวจราคา</li>
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
                          <h3 class=\"box-title\">สำรวจราคา &nbsp;&nbsp;&nbsp; <strong> การขอสั่งซื้อ
                                  $select_crm</strong> </h3>&nbsp;&nbsp;&nbsp; $text_disabled
                      </div>
                      <!-- /.box-header -->
                      <div class=\"box-header\">
                          <div class=\"col-xs-10\"></div>
                          <div class=\"col-xs-2\">
                              <h3 class=\"box-title\">เลขที่ PR.No $select_data_cpr[no_order]</h3>
                          </div>
                      </div>
                      <!-- /.box-header -->

                      <div class=\"box-body\">
                          <form name='theForm' target='_parent' action='com_purchasing_jobs.php' method='POST'
                              enctype=\"multipart/form-data\">

                              <div class=\"row\">
                                  <div class=\"col-md-12\">
                                      <table width='100%' border='1' cellpadding='0' cellspacing='1'>
                                          <tr>
                                              <td width='15%' align='right'><B>หน่วยงาน : &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[name_dept]</td>
                                              <td width='15%' align='right'><B>ผู้ขอซื้อ : &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[name_user]</td>
                                          </tr>
                                          <tr>
                                              <td width='15%' align='right'><B>วันที่ต้องการใช้ :
                                                      &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[day_use]</td>
                                              <td width='15%' align='right'><B>ตำแหน่ง : &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[position_emp]</td>
                                          </tr>
                                          <tr>
                                              <td width='15%' rowspan='2' align='right'><B>เรื่อง :
                                                      &nbsp;&nbsp;&nbsp;</B></td>
                                              <td rowspan='2' width='35%'>
                                                  &nbsp;&nbsp;&nbsp;$select_data_cpr[title_cpr]</td>
                                              <td width='15%' align='right'><B>วันที่ : &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[day_order]</td>
                                          </tr>
                                          <tr>
                                              <td width='15%' align='right'><B>โทรศัพท์ : &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[tel]</td>
                                          </tr>

                                          <tr>
                                              <td width='15%' align='right'><B>จำนวนที่สั่ง : &nbsp;&nbsp;&nbsp;</B>
                                              </td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[unit_quantity]</td>
                                              <td width='15%' align='right'><B>หมายเหตุ/จุดประสงค์ :
                                                      &nbsp;&nbsp;&nbsp;</B></td>
                                              <td width='35%'>&nbsp;&nbsp;&nbsp;$select_data_cpr[comment_cpr]</td>
                                          </tr>

                                      </table>
                                      $select_com_suggestion
                                  </div>
                                  <div class=\"col-md-2\">
                                  </div>
                                  <div class=\"col-md-10\">
                                      <div class=\"box-body\">

                                          <div class=\"box-body\">
                                              <!-- //เลขที่ใบเสนอราคา -->
                                              <div class=\"row\">
                                                  <div class=\"col-md-6\">
                                                      <div class=\"form-group\">
                                                          <label>เลขที่ใบเสนอราคา :</label>
                                                          <input type=\"text\" class=\"form-control\"
                                                              NAME='id_quotation' id='id_quotation'
                                                              value='$select_data_cpr[id_quotation]'
                                                              placeholder=\"เลขที่ใบเสนอราคา\" $type_disabled>
                                                      </div><!-- /.form-group -->
                                                  </div><!-- /.col -->
                                              </div><!-- /.row -->
                                          </div><!-- /.box-body -->

                                          <div class=\"box-body\">
                                              <!-- //ใบเสนอราคา -->
                                              <div class=\"row\">
                                                  <div class=\"col-md-6\">
                                                      <div class=\"form-group\">
                                                          <label>ใบเสนอราคา :</label> $text_file_quotation
                                                          <input type=\"file\" class=\"form-control\"
                                                              NAME='file_quotation' id='file_quotation' value=''
                                                              placeholder=\"ไฟล์ใบเสนอราคา\" $type_disabled>
                                                      </div><!-- /.form-group -->
                                                  </div><!-- /.col -->
                                              </div><!-- /.row -->
                                          </div><!-- /.box-body -->

                                          <div class=\"box-body\">
                                              <!-- //สถานที่จำหน่ายวัตถุดิบ -->
                                              <div class=\"row\">
                                                  <div class=\"col-md-6\">
                                                      <div class=\"form-group\">
                                                          <label>สถานที่จำหน่ายวัตถุดิบ :</label>
                                                          <select class=\"form-control select2\" style=\"width:
                                                              100%;\" name='id_crmse' id='id_crmse' $type_disabled>
                                                              <option value='0'>เลือก</option>
                                                              $select_input_crm
                                                          </select>
                                                      </div><!-- /.form-group -->
                                                  </div><!-- /.col -->
                                              </div><!-- /.row -->

                                          </div><!-- /.box-body -->
                                          <div class=\"box-body\">
                                              <!-- //หน่วย -->
                                              <div class=\"row\">
                                                  <div class=\"col-md-6\">
                                                      <div class=\"form-group\">
                                                          <label>หน่วย :</label>
                                                          <input type=\"text\" class=\"form-control\" NAME='unit'
                                                              id='unit' value='$select_data_cpr[unit]'
                                                              placeholder=\"หน่วย\" $type_disabled>
                                                      </div><!-- /.form-group -->
                                                  </div><!-- /.col -->
                                              </div><!-- /.row -->
                                          </div><!-- /.box-body -->

                                      </div><!-- /.box-body -->
                                      <div class=\"box-body\">
                                          <!-- //ราคาต่อหน่วย/กิโลกรัม -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>ราคาต่อหน่วย/กิโลกรัม :</label>
                                                      <input type=\"text\" class=\"form-control\" NAME='unit_price'
                                                          id='unit_price' value='$select_data_cpr[unit_price]'
                                                          placeholder=\"ระบุราคา(บาท)\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //วันที่ส่งมอบ -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>วันที่ส่งมอบ :</label>
                                                      <div class=\"input-group date\">
                                                          <div class=\"input-group-addon\">
                                                              <i class=\"fa fa-calendar\"></i>
                                                          </div>
                                                          <input type=\"text\" class=\"form-control pull-right\"
                                                              id=\"datepicker1\" name='date_delivery'
                                                              value='$select_data_cpr[date_delivery]'>
                                                      </div>
                                                      <!-- /.input group -->
                                                  </div>
                                                  <!-- /.form-group -->
                                              </div>
                                              <!-- /.col -->
                                          </div>
                                          <!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //จำนวนวันเครดิต -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>จำนวนวันเครดิต :</label>
                                                      <input type=\"text\" class=\"form-control\" NAME='trade_credit'
                                                          id='trade_credit' value='$select_data_cpr[trade_credit]'
                                                          placeholder=\"ระบุวันเครดิต(วัน)\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //เงื่อนไขการชำระ -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>เงื่อนไขการชำระ :</label>
                                                      <input type=\"text\" class=\"form-control\" NAME='payment_term'
                                                          id='payment_term' value='$select_data_cpr[payment_term]'
                                                          placeholder=\"ระบุเงื่อนไขการชำระ\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->

                                      </div><!-- /.box-body -->
                                      <div class=\"box-body\">
                                          <!-- //ส่วนลดสินค้า(คิดเป็นเงิน) -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>ส่วนลดสินค้า(คิดเป็นเงิน) :</label>
                                                      <input type=\"text\" class=\"form-control\" NAME='discounts'
                                                          id='discounts' value='$select_data_cpr[discounts]'
                                                          placeholder=\"ระบุส่วนลดสินค้า(บาท)\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //ภาษีมูลค่าเพิ่ม -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>ภาษีมูลค่าเพิ่ม :</label>
                                                      <input type=\"text\" class=\"form-control\" NAME='vat' id='vat'
                                                          value='$select_data_cpr[vat]'
                                                          placeholder=\"ระบุภาษีมูลค่าเพิ่ม(เปอร์เซนต์)\"
                                                          $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //สถานที่ส่งสินค้า -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>สถานที่ส่งสินค้า :</label>
                                                      <input type=\"text\" class=\"form-control\"
                                                          NAME='place_delivery' id='place_delivery'
                                                          value='$select_data_cpr[place_delivery]'
                                                          placeholder=\"สถานที่ส่งสินค้า\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->

                                      <div class=\"box-body\">
                                          <!-- //หมายเหตุ -->
                                          <div class=\"row\">
                                              <div class=\"col-md-6\">
                                                  <div class=\"form-group\">
                                                      <label>หมายเหตุ :</label>
                                                      <input type=\"text\" class=\"form-control\"
                                                          NAME='comment_purchasing_jobs' id='comment_purchasing_jobs'
                                                          value='$select_data_cpr[comment_purchasing_jobs]'
                                                          placeholder=\"หมายเหตุ\" $type_disabled>
                                                  </div><!-- /.form-group -->
                                              </div><!-- /.col -->
                                          </div><!-- /.row -->
                                      </div><!-- /.box-body -->
                                  </div><!-- /.col-md-10 -->
                              </div><!-- End Row-->

                              <div class=\"row\">
                                  <div class=\"col-md-4\">
                                  </div>
                                  <div class=\"col-md-8\">
                                      <input type='hidden' name='id_cpr' value='$encode_id_cpr'>
                                      $type_button
                                      <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='<<Back'
                                          ONCLICK='window.history.back();'>
                                  </div>
                              </div>
                      </div><!-- End Row-->
                      </form>

                  </div><!-- /.box-body -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div><!-- /.row -->

</div><!-- /.content-wrapper -->

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
function delete_com_raw_material($select_com_raw_material,$id_crmd)
{
  $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));
  print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>ลบคลังวัตถุดิบ</TITLE>
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
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
  <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\">
<style>
.myDiv {
  background-color: #fc7b6d;    
  text-align: center;
}
</style>
</HEAD>

<BODY leftMargin=0 topMargin=0 marginwidth='0' marginheight='0'>
<div class=\"myDiv wrapper\">
          <div class=\"modal-dialog\">
      <div class=\"col-xs-12\">  
<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบรายละเอียดวัตถุดิบ</B></FONT><br><br>
<FONT size='3' color='#FFFFFF'><B> $select_com_raw_material[name_crm]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='com_raw_material.php?closeme=true&delete_com_raw_material=$encode_id_crmd'\">Delete changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
<BR>
</BODY>
</HTML>
";
  exit;
}
#################################################