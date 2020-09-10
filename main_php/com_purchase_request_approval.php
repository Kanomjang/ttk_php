<?php
include "../lib_org.php";
$id_emp_use = $_SESSION['id_emp_use'];
$id_emp_use = '1001';

$position_active = '11';
$dept_position_active = dept_position_active($position_active);
if ($_POST['approval']) {
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  $select_id_cpo = select_id_cpo($position_active);
  update_approval($id_cpr, '1', $id_emp_use);
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}
if ($_POST['non_approval']) {
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  update_approval($id_cpr, '2', $id_emp_use);
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active);
}
if ($_POST['reset_approval']) {
  $id_emp_use = '';
  $id_cpr = base64_decode(base64_decode($_POST['id_cpr']));
  update_reset_approval($id_cpr);
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
function select_id_cpo($position_active)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select no_cpo from com_purchase_order where date_create='$position_active'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $dept_position_active = $row["id_dept"];
  }
  mysqli_close($connect);
  return $dept_position_active;
}
#################################################
function update_reset_approval($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update com_purchase_request set approve='',date_approve='',id_use_approve='' where id_cpr='$id_cpr'";
  mysqli_query($connect, $sql_query);
}
#################################################
function update_approval($id_cpr, $approve, $id_emp_use)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "update com_purchase_request set approve='$approve',date_approve=NOW(),id_use_approve='$id_emp_use' where id_cpr='$id_cpr'";
  mysqli_query($connect, $sql_query);
  $sql_query = "update com_purchase_order set date_po_approval=NOW(),id_po_approval='$id_emp_use',id_use='$id_emp_use' where id_cpr='$id_cpr'";
  mysqli_query($connect, $sql_query);
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
  $select_gdept = implode("\n", $print);
  return $select_gdept;
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
#################################################
function select_com_purchase_order_report($id_cpr)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "SELECT id_cpo,no_cpo,date_cpo,id_user_check,date_check,id_po_approval,date_po_approval FROM com_purchase_order WHERE com_purchase_order.id_cpr ='18'";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
    $select_com_purchase_order_report['id_cpo'] = $row["id_cpo"];
    $select_com_purchase_order_report['no_cpo'] = $row["no_cpo"];
    $select_com_purchase_order_report['date_cpo'] = $row["date_cpo"];
    $select_com_purchase_order_report['discounts'] = $row["discounts"];
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
  global $host, $user, $passwd, $database, $dayTH, $monthTH;
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
                    <div class=\"box-body\">
                        <!-- //ข้อเสนอแนะ -->
                        <div class=\"row\">
                            <div class=\"col-xs-2\"><label>ข้อเสนอแนะ :</div>
                            <div class=\"col-xs-6\"><input type='hidden' name='id_cpr' value='$encode_id_cpr'>
                                <input type=\"text\" class=\"form-control\" NAME='comment_suggestion' id='comment_suggestion' value=\"\">
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
  } else {
    $select_com_suggestion = "";
  }

  return $select_com_suggestion;
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
function com_purchase_order_report($select_com_purchase_request_report, $id_cpr)
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

  $com_purchase_order_report = "
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
          <br>ผู้จัดทำ</td>
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
  return $com_purchase_order_report;
}
######################หน้าแสดง###########################

function com_purchase_request_report($select_com_purchase_request_report, $dept_position_active, $id_cpr, $position_active)
{
  global $dayTH, $monthTH;
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  $select_crmd = select_crmd($select_com_purchase_request_report['id_crmse']);
  //$select_gdept=select_gdept($dept_position_active);
  $name_dept = name_dept($select_com_purchase_request_report['id_dept']);
  $name_user = name_user($select_com_purchase_request_report['id_use']);
  $name_purchasing_jobs = name_user($select_com_purchase_request_report['id_purchasing_jobs']);

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

  $date_purchasing_jobs = $select_com_purchase_request_report['date_purchasing_jobs'];
  $date03 = new DateTime($date_purchasing_jobs);
  $dd03 = $date03->format('j');
  $mm03 = $monthTH[$date03->format('n')];
  $yy03 = $date03->format('Y') + 543;
  $day_purchasing_jobs = "$dd03 $mm03 $yy03";

  $select_position_emp = select_position_emp($select_com_purchase_request_report['id_use']);
  $unit_quantity = number_format($select_com_purchase_request_report['unit_quantity'], 0, '.', ',');
  $unit_price = number_format($select_com_purchase_request_report['unit_price'], 2, '.', ',');
  if ($select_com_purchase_request_report['approve'] == '1') {
    $approve_box01 = "<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $button_summit1 = "<input type=\"submit\" VALUE='ยกเลิกการอนุมัติ' name='reset_approval' class=\"btn btn-warning\">";
    $button_summit2 = "<input type=\"submit\" disabled VALUE='&nbsp;&nbsp;ไม่อนุมัติ&nbsp;&nbsp;' name='non_approval' class=\"btn btn-danger\">";
  } elseif ($select_com_purchase_request_report['approve'] == '2') {
    $approve_box01 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $button_summit2 = "<input type=\"submit\" VALUE='ยกเลิกการไม่อนุมัติ' name='reset_approval' class=\"btn btn-warning\">";
    $button_summit1 = "<input type=\"submit\" disabled VALUE='&nbsp;&nbsp;อนุมัติ&nbsp;&nbsp;' name='approval' class=\"btn btn-primary\">";
  } else {
    $approve_box01 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02 = "<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $button_summit1 = "<input type=\"submit\" VALUE='&nbsp;&nbsp;อนุมัติ&nbsp;&nbsp;' name='approval' class=\"btn btn-primary\">";
    $button_summit2 = "<input type=\"submit\" VALUE='&nbsp;&nbsp;ไม่อนุมัติ&nbsp;&nbsp;' name='non_approval' class=\"btn btn-danger\">";
  }
  if ($select_com_purchase_request_report['approve'] != "0") {
    $name_user_approve = name_user($select_com_purchase_request_report['id_use_approve']);
    $date01 = new DateTime($select_com_purchase_request_report['date_approve']);
    $dd01 = $date01->format('j');
    $mm01 = $monthTH[$date01->format('n')];
    $yy01 = $date01->format('Y') + 543;
    $day_approve = "$dd01 $mm01 $yy01";
  } else {
    $name_user_approve['name'] = ".................................................";
    $day_approve = "............................................";
  }
  if ($select_com_purchase_request_report['file_quotation'] != "") {
    $text_file_quotation = "<a href=\"file_quotation/$select_com_purchase_request_report[file_quotation]\">File Quotation.</a>";
  } else {
    $text_file_quotation = "";
  }
  $discounts = "$select_com_purchase_request_report[discounts] บาท";
  $vat = "$select_com_purchase_request_report[vat] %";
  $trade_credit = "$select_com_purchase_request_report[trade_credit] วัน";

  $com_purchase_order_report = com_purchase_order_report($select_com_purchase_request_report, $id_cpr);

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
  include "../header.php";
  include "../sidebar.php";
  print "

  <div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
      <!-- Content Header (Page header) -->
      <section class=\"content-header\">
          <h1>
              อนุมัติใบขอซื้อ/ใบสั่งซื้อ ( PR/PO ) &nbsp;&nbsp;&nbsp; <strong><u>เรื่อง</u> การขอสั่งซื้อ $select_crmd[name_raw_material_details]</strong>
              <small>Version 1.0</small>
          </h1>
          <ol class=\"breadcrumb\">
              <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
              <li class=\"active\">อนุมัติใบขอซื้อ/ใบสั่งซื้อ (PR)</li>
          </ol>
      </section>
      <!-- Info boxes -->
      <!-- /.row -->
      <!-- Main content -->
      <section class=\"content\">
          <div class=\"row\">
              <div class=\"col-xs-12\">
                  <div class=\"box\">

                      <div class=\"box-body\">

                          <div class=\"row\">

                              <div class=\"col-md-12\">
                                  <div class=\"box box-warning box-solid\">
                                      <div class=\"box-header with-border\">
                                          <h3 class=\"box-title\">ใบขอซื้อ (PR) </h3>

                                          <div class=\"box-tools pull-right\">
                                              <A href='../../tcpdf/examples/com_pdf_purchase_request_report.php?com_purchase_request_report=$encode_id_cpr' class=\"btn btn-box-tool\" role=\"button\"><i class=\"glyphicon glyphicon-print\"></i></A>
                                              <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                                              </button>
                                          </div>
                                          <!-- /.box-tools -->
                                      </div>
                                      <!-- /.box-header -->
                                      <div class=\"box-body\">

                                          <div class=\"box-header\">
                                              <div class=\"col-xs-10\"></div>
                                              <div class=\"col-xs-2\">
                                                  <h3 class=\"box-title\">เลขที่ PR.No $select_com_purchase_request_report[no_order]</h3>
                                              </div>
                                          </div>
                                          <!-- /.box-header -->
                                          <form name='theForm' target='_parent' action='com_purchase_request_approval.php' method='POST'>

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
                                                          <tr align='center' bgcolor='dddddd'>
                                                              <td width='5%'><B>ลำดับที่</B></td>
                                                              <td width='35%'><B>รายการ</B></td>
                                                              <td width='15%'><B>จำนวน/หน่วย</B></td>
                                                              <td width='15%'><B>ราคา/หน่วย</B></td>
                                                              <td width='30%'><B>หมายเหตุ/จุดประสงค์</B></td>
                                                          </tr>
                                                          <tr>
                                                              <td align='center'>1.</td>
                                                              <td>&nbsp;&nbsp;&nbsp;$select_crmd[name_raw_material_details] ($select_crmd[name_accounts_payable])</td>
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
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          <td align='center'></td>
                                                          <td align='center'></td>
                                                          <td>&nbsp;</td>
                                                          </tr>
                                                      </table><br>
                                                      <br><br>
                                                      <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                                                          <tr align=\"center\">
                                                              <td width=\"30%\"><br><br><br><br>.................................................<br>
                                                                  (&nbsp;&nbsp;$name_user[name]&nbsp;&nbsp;)<br>
                                                                  วันที่ $day_order
                                                                  <br>ผู้ขอซื้อ</td>
                                                              <td width=\"40%\"><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                  $approve_box01 &nbsp;อนุมัติ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                  $approve_box02 &nbsp;ไม่อนุมัติ<br><br>
                                                                  .................................................<br>
                                                                  (&nbsp;&nbsp;$name_user_approve[name]&nbsp;&nbsp;)<br>
                                                                  วันที่ $day_approve <br>ผู้อนุมัติ
                                                              </td>
                                                              <td width=\"30%\"><br><br><br><br>.................................................<br>
                                                                  (&nbsp;&nbsp;$name_purchasing_jobs[name]&nbsp;&nbsp;)<br>
                                                                  วันที่ $day_purchasing_jobs <br>ฝ่ายจัดซื้อ</td>
                                                          </tr>
                                                      </table>

                                                  </div> <!-- /.row -->
                                              </div><!-- /.box-body -->
                                      </div><!-- /.box -->
                                  </div>
                                  <!-- /.col -->
                              </div> <!-- /.row -->
                          </div><!-- /.col-md-10 -->
                      </div><!-- End Row-->
                      <div class=\"col-md-12\">
                          <div class=\"box box-info box-solid\">
                              <div class=\"box-header with-border\">
                                  <h3 class=\"box-title\">ใบสั่งซื้อ (PR)</h3>

                                  <div class=\"box-tools pull-right\">
                                      <A href='../../tcpdf/examples/com_pdf_purchase_order_report.php?com_purchase_request_report=$encode_id_cpr' class=\"btn btn-box-tool\" role=\"button\"><i class=\"glyphicon glyphicon-print\"></i></A>
                                      <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>
                                      </button>
                                  </div>
                                  <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class=\"box-body\">
                                  $com_purchase_order_report

                              </div>
                              <!-- /.box-body -->
                          </div>
                          <!-- /.box -->
                      </div>
                      <!-- /.col -->

                      <div class=\"row\">
                          <div class=\"col-md-5\"></div>
                          <div class=\"col-md-7\"><br>
                              <input type='hidden' name='id_cpr' value='$encode_id_cpr'>
                              $button_summit1
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              $button_summit2
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='<<Back' ONCLICK='window.location.replace(\"com_document_approval.php\");'>
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
<script src=\"../bower_components/jquery/dist/jquery.min.js\"> </script> <!-- Bootstrap 3.3.7 -->
  < script src = \"../bower_components/bootstrap/dist/js/bootstrap.min.js\">
</script>
<!-- Select2 -->
<script src=\"../bower_components/select2/dist/js/select2.full.min.js\"> </script> <!-- InputMask -->
  < script src = \"../plugins/input-mask/jquery.inputmask.js\">
</script>
<script src=\"../plugins/input-mask/jquery.inputmask.date.extensions.js\"> </script> <script src=\"../plugins/input-mask/jquery.inputmask.extensions.js\"> </script> <!-- date-range-picker -->
  < script src = \"../bower_components/moment/min/moment.min.js\">
</script>
<script src=\"../bower_components/bootstrap-daterangepicker/daterangepicker.js\"> </script> <!-- bootstrap datepicker -->
  < script src = \"../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\">
</script>
<!-- bootstrap color picker -->
<script src=\"../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js\"> </script> <!-- bootstrap time picker -->
  < script src = \"../plugins/timepicker/bootstrap-timepicker.min.js\">
</script>
<!-- SlimScroll -->
<script src=\"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\"> </script> <!-- iCheck 1.0.1 -->
  < script src = \"../plugins/iCheck/icheck.min.js\">
</script>
<!-- FastClick -->
<script src=\"../bower_components/fastclick/lib/fastclick.js\"> </script> <!-- AdminLTE App -->
  < script src = \"../dist/js/adminlte.min.js\">
</script>
<!-- AdminLTE for demo purposes -->
<script src=\"../dist/js/demo.js\"> </script> <!-- Page script -->
  < script >
      $(function() {
          //Initialize Select2 Elements
          $('.select2').select2()

          //Datemask dd/mm/yyyy
          $('#datemask').inputmask('dd/mm/yyyy', {
              'placeholder': 'dd/mm/yyyy'
          })
          //Datemask2 mm/dd/yyyy
          $('#datemask2').inputmask('mm/dd/yyyy', {
              'placeholder': 'mm/dd/yyyy'
          })
          //Money Euro
          $('[data-mask]').inputmask()

          //Date range picker
          $('#reservation').daterangepicker()
          //Date range picker with time picker
          $('#reservationtime').daterangepicker({
              timePicker: true,
              timePickerIncrement: 30,
              locale: {
                  format: 'MM/DD/YYYY hh:mm A'
              }
          })
          //Date range as a button
          $('#daterange-btn').daterangepicker({
                  ranges: {
                      'Today': [moment(), moment()],
                      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                      'This Month': [moment().startOf('month'), moment().endOf('month')],
                      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  },
                  startDate: moment().subtract(29, 'days'),
                  endDate: moment()
              },
              function(start, end) {
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
              radioClass: 'iradio_minimal-blue'
          })
          //Red color scheme for iCheck
          $('input[type=\"checkbox\"].minimal-red, input[type=\"radio\"].minimal-red').iCheck({
              checkboxClass: 'icheckbox_minimal-red',
              radioClass: 'iradio_minimal-red'
          })
          //Flat red color scheme for iCheck
          $('input[type=\"checkbox\"].flat-red, input[type=\"radio\"].flat-red').iCheck({
              checkboxClass: 'icheckbox_flat-green',
              radioClass: 'iradio_flat-green'
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
  include "../footer.php";
  exit;
}
#################################################