<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$id_emp_use = '1001';

$position_active = '12';


$dept_position_active = dept_position_active($position_active);
if ($_POST['send_po_suggestion']) {
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  $comment_suggestion = $_POST['comment_suggestion'];
  insert_comment_suggestion($id_cpr, $comment_suggestion, $id_emp_use);
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}

if ($_POST['check_purchase_order']) {
  $encode_id_cpr = $_POST['id_cpr'];
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  $select_id_cpo = select_id_cpo();
  update_check_purchase_order($id_cpr, $id_emp_use);
  header("location: http://localhost/main_php/com_purchase_order_approval.php?com_purchase_request_report=$encode_id_cpr");
  exit(0);
}
if ($_POST['non_approval']) {
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  update_check_purchase_order($id_cpr, '2', $id_emp_use);
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}
if ($_POST['reset_purchase_order']) {
  $id_emp_use = '';
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  update_reset_purchase_order($id_cpr);
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}
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
function insert_comment_suggestion($id_cpr, $comment_suggestion, $id_emp_use)
{
  global $host, $user, $passwd, $database, $id_emp_use;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "insert into com_suggestion(id_cpr,comment_suggestion,id_use) values('$id_cpr','$comment_suggestion','$id_emp_use')";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}

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
  $sql_query = "SELECT id_quotation,date_delivery,vat,discounts,unit,place_delivery,payment_term,com_raw_material_details.code_crmd,com_raw_material_details.name_raw_material_details,com_accounts_payable.code_cap,com_accounts_payable.name_accounts_payable,com_accounts_payable.address_accounts_payable,com_accounts_payable.contact_person_name,com_accounts_payable.tel_contact,com_accounts_payable.email_contact,id_purchasing_jobs,date_purchasing_jobs,no_order,id_use_approve,date_approve,com_purchase_request.title_cpr,com_purchase_request.date_purchasing_jobs,com_purchase_request.date_use,com_purchase_request.id_dept,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.unit_price,com_purchase_request.comment_cpr,com_purchase_request.approve,com_purchase_request.id_use FROM com_purchase_request,com_raw_matreial_seller,com_accounts_payable,com_raw_material_details WHERE com_purchase_request.id_cpr='$id_cpr' AND com_purchase_request.id_crmse = com_raw_matreial_seller.id_crmse AND com_raw_matreial_seller.id_cap = com_accounts_payable.id_cap AND com_raw_matreial_seller.id_crmd = com_raw_material_details.id_crmd";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $select_com_purchase_request_report['id_quotation'] = $row["id_quotation"];
    $select_com_purchase_request_report['date_delivery'] = $row["date_delivery"];
    $select_com_purchase_request_report['vat'] = $row["vat"];
    $select_com_purchase_request_report['discounts'] = $row["discounts"];
    $select_com_purchase_request_report['unit'] = $row["unit"];
    $select_com_purchase_request_report['place_delivery'] = $row["place_delivery"];
    $select_com_purchase_request_report['payment_term'] = $row["payment_term"];
    $select_com_purchase_request_report['code_crmd'] = $row["code_crmd"];
    $select_com_purchase_request_report['name_raw_material_details'] = $row["name_raw_material_details"];
    $select_com_purchase_request_report['code_cap'] = $row["code_cap"];
    $select_com_purchase_request_report['name_accounts_payable'] = $row["name_accounts_payable"];
    $select_com_purchase_request_report['address_accounts_payable'] = $row["address_accounts_payable"];
    $select_com_purchase_request_report['contact_person_name'] = $row["contact_person_name"];
    $select_com_purchase_request_report['tel_contact'] = $row["tel_contact"];
    $select_com_purchase_request_report['email_contact'] = $row["email_contact"];
    $select_com_purchase_request_report['no_order'] = $row["no_order"];
    $select_com_purchase_request_report['title_cpr'] = $row["title_cpr"];
    $select_com_purchase_request_report['date_purchasing_jobs'] = $row["date_purchasing_jobs"];
    $select_com_purchase_request_report['date_use'] = $row["date_use"];
    $select_com_purchase_request_report['id_dept'] = $row["id_dept"];
    $select_com_purchase_request_report['id_crmse'] = $row["id_crmse"];
    $select_com_purchase_request_report['unit_quantity'] = $row["unit_quantity"];
    $select_com_purchase_request_report['unit_price'] = $row["unit_price"];
    $select_com_purchase_request_report['comment_cpr'] = $row["comment_cpr"];
    $select_com_purchase_request_report['approve'] = $row["approve"];
    $select_com_purchase_request_report['id_use'] = $row["id_use"];
    $select_com_purchase_request_report['id_use_approve'] = $row["id_use_approve"];
    $select_com_purchase_request_report['date_approve'] = $row["date_approve"];
    $select_com_purchase_request_report['id_purchasing_jobs'] = $row["id_purchasing_jobs"];
    $select_com_purchase_request_report['date_purchasing_jobs'] = $row["date_purchasing_jobs"];
  }
  mysqli_close($connect);
  return $select_com_purchase_request_report;
}
##############################
function select_crmd($id_crmse)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_raw_material_details.name_raw_material_details,com_accounts_payable.name_accounts_payable from com_raw_matreial_seller,com_raw_material_details,com_accounts_payable WHERE com_raw_matreial_seller.id_crmse ='$id_crmse' AND 
  com_raw_material_details.id_crmd = com_raw_matreial_seller.id_crmd AND com_accounts_payable.id_cap = com_raw_matreial_seller.id_cap";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_crmd['name_raw_material_details'] = $row["name_raw_material_details"];
    $select_crmd['name_accounts_payable'] = $row["name_accounts_payable"];
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
##############################
function point_number_format($point_number)
{
  $explode_point_number = explode(".", $point_number);
  if ($explode_point_number[1] == "") {
    $point_number_format = number_format($point_number, 0, '.', ',');
  } else {
    $point_number_format = number_format($point_number, 2, '.', ',');
  }

  return $point_number_format;
}

#################################################
function select_com_purchase_order_report($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_cpo,no_cpo,date_cpo,id_user_check,date_check,id_po_approval,date_po_approval FROM com_purchase_order WHERE com_purchase_order.id_cpr ='$id_cpr'";

  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $select_com_purchase_order_report['id_cpo'] = $row["id_cpo"];
    $select_com_purchase_order_report['no_cpo'] = $row["no_cpo"];
    $select_com_purchase_order_report['date_cpo'] = $row["date_cpo"];
    $select_com_purchase_order_report['id_user_check'] = $row["id_user_check"];
    $select_com_purchase_order_report['date_check'] = $row["date_check"];
    $select_com_purchase_order_report['id_po_approval'] = $row["id_po_approval"];
    $select_com_purchase_order_report['date_po_approval'] = $row["date_po_approval"];
  }
  mysqli_close($connect);
  return $select_com_purchase_order_report;
}

#################################################
function select_com_suggestion($id_cpr)
{
  global $host, $user, $passwd, $database, $monthTH;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_suggestion.comment_suggestion,com_suggestion.id_use,date(com_suggestion.last_update) AS date_com_suggestion,time(com_suggestion.last_update) AS time_com_suggestion FROM com_suggestion WHERE com_suggestion.id_cpr='$id_cpr'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $comment_suggestion = $row["comment_suggestion"];
    $id_use = $row["id_use"];
    $date_com_suggestion = $row["date_com_suggestion"];
    $time_com_suggestion = $row["time_com_suggestion"];
    $name_user = name_user($id_use);
    $date02 = new DateTime($date_com_suggestion);
    $dd02 = $date02->format('j');
    $mm02 = $monthTH[$date02->format('n')];
    $yy02 = $date02->format('Y') + 543;
    $day_com_suggestion = "$dd02 $mm02 $yy02 : $time_com_suggestion";

    $print[$i] = "
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
    $print_count = "($i)";
  } else {
    $print_count = "";
  }

  $select_com_suggestion = "<br>
    <div class=\"row\">
    <div class=\"col-md-2\"></div>

    <div class=\"col-md-8\">
      <div class=\"box box-info box-solid collapsed-box\">
        <div class=\"box-header box-success with-border\">
          <h3 class=\"box-title\">ข้อเสนอแนะ $print_count</h3>

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


  return $select_com_suggestion;
}
#################################################
function select_id_cpo()
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT com_purchase_order.no_cpo FROM com_purchase_order WHERE YEAR(com_purchase_order.date_cpo)=YEAR(CURDATE()) order by com_purchase_order.date_cpo DESC limit 0,1";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_id_cpo = $row["no_cpo"];
  }
  mysqli_close($connect);
  if ($select_id_cpo == "") {
    $year = date("Y") + 543;
    $select_id_cpo = "$year" . "001";
  } else {
    $select_id_cpo++;
  }
  return $select_id_cpo;
}
#################################################
function update_reset_purchase_order($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "DELETE FROM com_purchase_order WHERE  id_cpr='$id_cpr';";
  mysqli_query($connect, $sql_query);
  mysqli_close($connect);
}
#################################################
function update_check_purchase_order($id_cpr, $id_emp_use)
{
  global $host, $user, $passwd, $database;
  $select_id_cpo = select_id_cpo();
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "INSERT INTO com_purchase_order (id_cpr,no_cpo,date_cpo,id_user_check,date_check) VALUES ('$id_cpr', '$select_id_cpo', NOW(), '$id_emp_use', NOW())";
  mysqli_query($connect, $sql_query);
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

######################หน้าแสดง###########################
function com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active)
{
  global $dayTH, $monthTH, $monthTH_brev, $id_emp_use;
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  $select_crmd = select_crmd($select_com_purchase_request_report['id_crmse']);
  $name_dept = name_dept($select_com_purchase_request_report['id_dept']);
  $name_user = name_user($select_com_purchase_request_report['id_use']);
  $date_purchasing_jobs = $select_com_purchase_request_report['date_purchasing_jobs'];
  $date01 = new DateTime($date_purchasing_jobs);
  $dd01 = $date01->format('j');
  $mm01 = $monthTH[$date01->format('n')];
  $yy01 = $date01->format('Y') + 543;
  $day_purchasing_jobs = "$dd01 $mm01 $yy01";
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
  if ($select_com_purchase_request_report['id_purchasing_jobs'] != "0") {
    $name_user_purchasing_jobs = name_user($select_com_purchase_request_report['id_purchasing_jobs']);
    $date02 = new DateTime($select_com_purchase_request_report['date_purchasing_jobs']);
    $dd02 = $date02->format('j');
    $mm02 = $monthTH[$date02->format('n')];
    $yy02 = $date02->format('Y') + 543;
    $day_purchasing_jobs = "$dd02 $mm02 $yy02";
  } else {
    $name_user_purchasing_jobs['name'] = ".................................................";
    $day_purchasing_jobs = "............................................";
  }

  $name_user_approve = name_user("$id_emp_use");
  $date01 = new DateTime($select_com_purchase_request_report['date_approve']);
  $dd01 = $date01->format('j');
  $mm01 = $monthTH[$date01->format('n')];
  $yy01 = $date01->format('Y') + 543;
  $day_approve = "$dd01 $mm01 $yy01";

  $discounts_number_format = point_number_format($select_com_purchase_request_report['discounts']);
  $unit_quantity_number_format = point_number_format($select_com_purchase_request_report['unit_quantity']);

  $total_price = $select_com_purchase_request_report['unit_quantity'] * $select_com_purchase_request_report['unit_price'];

  $total_price_number_format = point_number_format($total_price);

  if ($select_com_purchase_request_report['vat'] == '1') {
    $vat = ($total_price - $select_com_purchase_request_report['discounts']) * 0.07;
    $vat_number_format = point_number_format($vat);
  } else {
    $vat = '0';
  }
  $final_price = ($total_price - $select_com_purchase_request_report['discounts']) + $vat;
  $final_price_number_format = point_number_format($final_price);
  $text_final_price = convert_bath($final_price);
  $date01 = new DateTime($select_com_purchase_request_report['date_delivery']);
  $dd01 = $date01->format('j');
  $mm01 = $monthTH_brev[$date01->format('n')];
  $yy01 = $date01->format('Y') + 543;
  $date_delivery = "$dd01 $mm01 $yy01";
  if ($select_com_purchase_request_report['file_quotation'] != "") {
    $text_file_quotation = "<a href=\"file_quotation/$select_com_purchase_request_report[file_quotation]\">File Quotation.</a>";
  } else {
    $text_file_quotation = "";
  }

  $select_com_purchase_order_report = select_com_purchase_order_report($id_cpr);

  if ($select_com_purchase_order_report['no_cpo'] == '') {
    $yy01 = date_create('now')->format('Y') + 543;
    $select_com_purchase_order_report['no_cpo'] = "$yy01" . "001";
  }


  if ($select_com_purchase_order_report['date_cpo'] == '' || $select_com_purchase_order_report['date_cpo'] == null) {
    $date_cpo = date_create('now')->format('Y-m-d');
  } else {
    $date_cpo = $select_com_purchase_order_report['date_cpo'];
  }
  $date02 = new DateTime($date_cpo);
  $dd02 = $date02->format('j');
  $mm02 = $monthTH[$date02->format('n')];
  $yy02 = $date02->format('Y') + 543;
  $day_date_cpo = "$dd02 $mm02 $yy02";
  if ($select_com_purchase_order_report['id_user_check'] == '' || $select_com_purchase_order_report['id_user_check'] == null) {
    $name_user_check_purchasing['name'] = ".................................................";
    $day_check_purchasing = ".................................................";
  } else {
    $name_user_check_purchasing = name_user($select_com_purchase_order_report['id_user_check']);
    $date01 = new DateTime($select_com_purchase_order_report['date_check']);
    $dd01 = $date01->format('j');
    $mm01 = $monthTH[$date01->format('n')];
    $yy01 = $date01->format('Y') + 543;
    $day_check_purchasing = "$dd01 $mm01 $yy01";
  }

  if ($select_com_purchase_order_report['id_po_approval'] == '' || $select_com_purchase_order_report['id_po_approval'] == null) {
    $name_po_approval['name'] = ".................................................";
    $day_po_approval = ".................................................";
  } else {
    $name_po_approval = name_user($select_com_purchase_order_report['id_po_approval']);
    $date01 = new DateTime($select_com_purchase_order_report['date_po_approval']);
    $dd01 = $date01->format('j');
    $mm01 = $monthTH[$date01->format('n')];
    $yy01 = $date01->format('Y') + 543;
    $day_po_approval = "$dd01 $mm01 $yy01";
  }
  $select_com_suggestion = select_com_suggestion($id_cpr);


  $com_purchase_request_report = "
<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"60%\" align=\"center\">
          <h2>ใบสั่งซื้อ (Purchase Order)</h2>
      </td>
      <td width=\"40%\" align=\"right\">
          <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td width=\"50%\" align=\"right\"><strong>เลขที่เอกสาร PO.No &nbsp;&nbsp;</strong></td>
                  <td width=\"50%\" align=\"left\"><strong>&nbsp;&nbsp; $select_com_purchase_order_report[no_cpo]</strong></td>
              </tr>
              <tr>
                  <td align=\"right\"><strong>วันที่เอกสาร PO.No &nbsp;&nbsp;</strong></td>
                  <td align=\"left\"><strong>&nbsp;&nbsp; $day_date_cpo</strong></td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"50%\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><strong>ผู้ขาย :</strong>&nbsp; $select_com_purchase_request_report[name_accounts_payable]</td>
              </tr>
              <tr>
                  <td><strong>ที่อยู่ :</strong>&nbsp; $select_com_purchase_request_report[address_accounts_payable]</td>
              </tr>
              <tr>
                  <td><strong>โทรศัพท์ :</strong>&nbsp; $select_com_purchase_request_report[tel_contact]</td>
              </tr>
              <tr>
                  <td><strong>E-mail :</strong>&nbsp; $select_com_purchase_request_report[email_contact]</td>
              </tr>
              <tr>
                  <td><strong>ชื่อผู้ติดต่อ :</strong>&nbsp; $select_com_purchase_request_report[contact_person_name]</td>
              </tr>
              <tr>
                  <td><strong>เลขที่ใบเสนอราคา :</strong>&nbsp; $select_com_purchase_request_report[id_quotation]</td>
              </tr>
              <tr>
                  <td><strong>รหัสผู้ขาย :</strong>&nbsp; $select_com_purchase_request_report[code_cap]</td>
              </tr>

          </table>

      </td>
      <td width=\"50%\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><strong>เงื่อนไขการชำระเงิน :</strong>&nbsp; $select_com_purchase_request_report[payment_term]</td>
              </tr>
              <tr>
                  <td><strong>ชื่อผู้ขอซื้อ :</strong>&nbsp; $name_user[name]</td>
              </tr>
              <tr>
                  <td><strong>โทรศัพท์ :</strong>&nbsp; $name_user[tel]</td>
              </tr>
              <tr>
                  <td><strong>E-mail :</strong>&nbsp; $name_user[email]</td>
              </tr>
              <tr>
                  <td><strong>สถานที่ส่งสินค้า(Ship to) :</strong>&nbsp; $select_com_purchase_request_report[place_delivery]</td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<br><br>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"5%\" align=\"center\" bgcolor=\"fefefe\"><B>ลำดับ</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>รหัสสินค้า</B></td>
      <td width=\"25%\" align=\"center\" bgcolor=\"fefefe\"><B>รายละเอียด</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>ใบขอซื้อ</B></td>
      <td width=\"12%\" align=\"center\" bgcolor=\"fefefe\"><B>วันส่งมอบ</B></td>
      <td width=\"8%\" align=\"center\" bgcolor=\"fefefe\"><B>หน่วย</B></td>
      <td width=\"8%\" align=\"center\" bgcolor=\"fefefe\"><B>ปริมาณ</B></td>
      <td width=\"12%\" align=\"center\" bgcolor=\"fefefe\"><B>ราคาต่อหน่วย</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>จำนวนเงิน</B></td>
  </tr>
  <tr>
      <td align=\"center\">1.</td>
      <td align=\"center\">$select_com_purchase_request_report[code_crmd]</td>
      <td>&nbsp;$select_com_purchase_request_report[name_raw_material_details]</td>
      <td align=\"center\">$select_com_purchase_request_report[no_order]</td>
      <td align=\"center\">$date_delivery</td>
      <td align=\"center\">$select_com_purchase_request_report[unit]</td>
      <td align=\"center\">$unit_quantity_number_format</td>
      <td align=\"center\">$select_com_purchase_request_report[unit_price]</td>
      <td align=\"right\">$total_price_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"left\" colspan=\"6\" rowspan=\"4\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><b>จำนวนเงิน(ตัวอักษร)</b></td>
              </tr>
              <tr>
                  <td align=\"center\"><br><br>$text_final_price</td>
              </tr>
          </table>
      </td>
      <td align=\"right\" colspan=\"2\"><b>จำนวนเงิน</b>&nbsp;&nbsp;</td>
      <td align=\"right\">$total_price_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>ส่วนลดสินค้า(เป็นเงิน)</b>&nbsp;&nbsp;</td>
      <td align=\"right\">$discounts_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>ภาษีมูลค่าเพิ่ม</b>&nbsp;&nbsp;</td>
      <td align=\"right\">$vat_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>จำนวนเงินทั้งสิ้น</b>&nbsp;&nbsp;</td>
      <td align=\"right\">$final_price_number_format &nbsp;</td>
  </tr>
</table>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr align=\"center\">
      <td width=\"30%\"><br><br>.................................................<br>
          (&nbsp;&nbsp;$name_user_purchasing_jobs[name]&nbsp;&nbsp;)<br>
          วันที่ $day_purchasing_jobs
          <br>ผู้จัดดทำ</td>
      <td width=\"40%\"><br><br>
          .................................................<br>
          (&nbsp;&nbsp;$name_user_check_purchasing[name]&nbsp;&nbsp;)<br>
          วันที่ $day_check_purchasing <br>ผู้ตรวจสอบ
      </td>
      <td width=\"30%\"><br><br>.................................................<br>
          (&nbsp;&nbsp;$name_po_approval[name]&nbsp;&nbsp;)<br>
          วันที่ $day_po_approval <br>ผู้มีอำนาจลงนาม</td>
  </tr>
</table>
  ";

  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  //  $select_crmd=select_crmd($select_com_purchase_request_report['id_crmse']);
  //$select_gdept=select_gdept($dept_position_active);
  //$select_cpo=select_cpo($id_cpr);
  if ($select_com_purchase_order_report['id_user_check'] != '') {
    $button_summit1 = "<input type=\"submit\" VALUE='ยกเลิกการตรวจสอบ' name='reset_purchase_order' class=\"btn btn-warning\">";
  } else {
    $button_summit1 = "<input type=\"submit\" VALUE='&nbsp;&nbsp;ตรวจสอบ&nbsp;&nbsp;' name='check_purchase_order' class=\"btn btn-primary\">";
  }
  if ($select_com_purchase_order_report['id_po_approval'] != "0") {
    $name_user_po_approval = name_user($select_com_purchase_order_report['id_po_approval']);
    $date01 = new DateTime($select_com_purchase_order_report['date_po_approval']);
    $dd01 = $date01->format('j');
    $mm01 = $monthTH[$date01->format('n')];
    $yy01 = $date01->format('Y') + 543;
    $day_po_approval = "$dd01 $mm01 $yy01";
  } else {
    $name_user_po_approval['name'] = ".................................................";
    $day_po_approval = "............................................";
  }
  $discounts = "$select_com_purchase_request_report[discounts] บาท";
  $vat = "$select_com_purchase_request_report[vat] %";
  $trade_credit = "$select_com_purchase_request_report[trade_credit] วัน";
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
      <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\"> <!-- Font Awesome -->
      <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\"> <!-- Ionicons -->
      <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\"> <!-- daterange picker -->
      <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-daterangepicker/daterangepicker.css\"> <!-- bootstrap datepicker -->
      <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css\"> <!-- iCheck for checkboxes and radio inputs -->
      <link rel=\"stylesheet\" href=\"../plugins/iCheck/all.css\"> <!-- Bootstrap Color Picker -->
      <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css\"> <!-- Bootstrap time Picker -->
      <link rel=\"stylesheet\" href=\"../plugins/timepicker/bootstrap-timepicker.min.css\"> <!-- Select2 -->
      <link rel=\"stylesheet\" href=\"../bower_components/select2/dist/css/select2.min.css\"> <!-- Theme style -->
      <link rel=\"stylesheet\" href=\"../dist/css/AdminLTE.min.css\"> <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
      <link rel=\"stylesheet\" href=\"../dist/css/skins/_all-skins.min.css\"> <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
    <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
    <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
    <![endif]-->
  
      <!-- Google Font -->
      <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\"> </head> <body class=\"hold-transition skin-blue sidebar-mini\">
      ";
  include("../header.php");
  include("../sidebar.php");
  print "
  
      <div class=\"wrapper\">
          <!-- Content Wrapper. Contains page content -->
          <div class=\"content-wrapper\">
              <!-- Main content -->
              <section class=\"content\">
                  <div class=\"row\">
                      <div class=\"col-xs-12\">
                          <div class=\"box\">
                              <div class=\"box-body\">
                                  $com_purchase_request_report
                                  $select_com_suggestion
  
  
                                  <div class=\"row\">
                                      <div class=\"col-md-4\"></div>
                                      <div class=\"col-md-8\"><br>
  
                                          <form name='theForm' target='_parent' action='' method='POST'>
  
                                              <input type='hidden' name='id_cpr' value='$encode_id_cpr'>
                                              $button_summit1
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              <INPUT TYPE='reset' class=\"btn btn-info\" VALUE='ตัวอย่างใบสั่งซื้อ' ONCLICK='window.open(\"../../tcpdf/examples/com_pdf_purchase_order_report.php?com_purchase_request_report=$encode_id_cpr\");'>
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='<<Back' ONCLICK='window.location.replace(\"com_check_purchase_order.php\");'>
                                          </form>
                                      </div>
                                  </div>
                              </div><!-- /.box-body -->
                          </div><!-- /.box -->
                      </div><!-- /.col -->
                  </div><!-- /.row -->
              </section><!-- /.content -->
          </div><!-- /.content-wrapper -->
  
  
      </div>
      <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
      <div class=\"control-sidebar-bg\"></div>
      </div>
      <!-- jQuery 3 -->
      <script src=\"../bower_components/jquery/dist/jquery.min.js\"> </script> <!-- Bootstrap 3.3.7 -->
          < script src = \"../bower_components/bootstrap/dist/js/bootstrap.min.js\">
      </script>
      <!-- DataTables -->
      <script src=\"../bower_components/datatables.net/js/jquery.dataTables.min.js\"> </script> <script src=\"../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js\"> </script> <!-- SlimScroll -->
          < script src = \"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\">
      </script>
      <!-- FastClick -->
      <script src=\"../bower_components/fastclick/lib/fastclick.js\"> </script> <!-- AdminLTE App -->
          < script src = \"../dist/js/adminlte.min.js\">
      </script>
      <!-- AdminLTE for demo purposes -->
      <script src=\"../dist/js/demo.js\"> </script> ";
  include(" ../footer.php");
  print "</div>
  <!-- ./wrapper -->
  </div>
  </body>
  
  </html>
  ";
  exit;
}
#################################################