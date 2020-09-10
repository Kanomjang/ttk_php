<?php
include("../../lib_org.php");
$position_active='11';
$id_emp_use='1001';
$dept_position_active=dept_position_active($position_active);
if ($_GET['com_purchase_request_report']) {
  $id_cpr = $_GET['com_purchase_request_report'];
  $id_cpr = base64_decode(base64_decode("$id_cpr"));
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  $html = com_purchase_request_report($select_com_purchase_request_report,$dept_position_active,$id_cpr,$position_active,"1001");
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
  $sql_query = "SELECT id_quotation,date_delivery,vat,discounts,unit,place_delivery,payment_term,com_raw_material_details.code_crmd,com_raw_material_details.name_raw_material_details,com_accounts_payable.code_cap,com_accounts_payable.name_accounts_payable,com_accounts_payable.address_accounts_payable,com_accounts_payable.contact_person_name,com_accounts_payable.tel_contact,com_accounts_payable.email_contact,id_purchasing_jobs,date_purchasing_jobs,no_order,id_use_approve,date_approve,com_purchase_request.title_cpr,com_purchase_request.date_order,com_purchase_request.date_use,com_purchase_request.id_dept,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.unit_price,com_purchase_request.comment_cpr,com_purchase_request.approve,com_purchase_request.id_use FROM com_purchase_request,com_raw_matreial_seller,com_accounts_payable,com_raw_material_details WHERE com_purchase_request.id_cpr='$id_cpr' AND com_purchase_request.id_crmse = com_raw_matreial_seller.id_crmse AND com_raw_matreial_seller.id_cap = com_accounts_payable.id_cap AND com_raw_matreial_seller.id_crmd = com_raw_material_details.id_crmd";
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
    $select_com_purchase_request_report['date_order'] = $row["date_order"];
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
  $explode_point_number=explode(".",$point_number);
  if($explode_point_number[1]==""){
    $point_number_format=number_format($point_number, 0, '.', ',');
  }else{
    $point_number_format=number_format($point_number, 2, '.', ',');
  }
  
  return $point_number_format;

}
######################หน้าแสดง###########################
function com_purchase_request_report($select_com_purchase_request_report,$dept_position_active,$id_cpr,$position_activem,$id_emp_use)
{
  global $dayTH,$monthTH,$monthTH_brev;   
  $encode_id_cpr = base64_encode(base64_encode("$id_cpr"));
  $select_crmd=select_crmd($select_com_purchase_request_report['id_crmse']);
  $name_dept=name_dept($select_com_purchase_request_report['id_dept']);
  $name_user=name_user($select_com_purchase_request_report['id_use']);
  $date_order=$select_com_purchase_request_report['date_order'];
  $date01 = new DateTime($date_order);
  $dd01=$date01->format('j');
  $mm01=$monthTH[$date01->format('n')];
  $yy01=$date01->format('Y')+543;
  $day_order="$dd01 $mm01 $yy01";

  $date_use=$select_com_purchase_request_report['date_use'];
  $date02 = new DateTime($date_use);
  $dd02=$date02->format('j');
  $mm02=$monthTH[$date02->format('n')];
  $yy02=$date02->format('Y')+543;
  $day_use="$dd02 $mm02 $yy02";
  $select_position_emp=select_position_emp($select_com_purchase_request_report['id_use']);
  $unit_quantity=number_format($select_com_purchase_request_report['unit_quantity'], 2, '.', ',');
  $unit_price=number_format($select_com_purchase_request_report['unit_price'], 2, '.', ',');
  if($select_com_purchase_request_report['approve']=='1'){
    $approve_box01="<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02="<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  }elseif($select_com_purchase_request_report['approve']=='2'){
    $approve_box01="<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02="<img src=\"../../image/check_box_true.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  }else{
    $approve_box01="<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
    $approve_box02="<img src=\"../../image/check_box.png\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">";
  }  
  if($select_com_purchase_request_report['id_purchasing_jobs']!="0"){
    $name_user_purchasing_jobs=name_user($select_com_purchase_request_report['id_purchasing_jobs']);
    $date02 = new DateTime($select_com_purchase_request_report['date_purchasing_jobs']);
    $dd02=$date02->format('j');
    $mm02=$monthTH[$date02->format('n')];
    $yy02=$date02->format('Y')+543;
    $day_purchasing_jobs="$dd02 $mm02 $yy02";
}else{
    $name_user_purchasing_jobs['name']=".................................................";
    $day_purchasing_jobs="............................................";
}

    $name_user_approve=name_user("$id_emp_use");
    $date01 = new DateTime($select_com_purchase_request_report['date_approve']);
    $dd01=$date01->format('j');
    $mm01=$monthTH[$date01->format('n')];
    $yy01=$date01->format('Y')+543;
    $day_approve="$dd01 $mm01 $yy01";

$discounts_number_format=point_number_format($select_com_purchase_request_report['discounts']);
$unit_quantity_number_format=point_number_format($select_com_purchase_request_report['unit_quantity']);





$total_price=$select_com_purchase_request_report['unit_quantity']*$select_com_purchase_request_report['unit_price'];

$total_price_number_format=point_number_format($total_price);

if($select_com_purchase_request_report['vat'] =='1'){
  $vat=($total_price-$select_com_purchase_request_report['discounts'])*0.07;
  $vat_number_format=point_number_format($vat);
}else{
  $vat='0';
}
$final_price=($total_price-$select_com_purchase_request_report['discounts'])+$vat;
$final_price_number_format=point_number_format($final_price);
$text_final_price=convert_bath($final_price);
$date01 = new DateTime($select_com_purchase_request_report['date_delivery']);
$dd01=$date01->format('j');
$mm01=$monthTH_brev[$date01->format('n')];
$yy01=$date01->format('Y')+543;
$date_delivery="$dd01 $mm01 $yy01";

  $com_purchase_request_report="<br><br><br><br><br><br>
              <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                <tr>
                  <td width=\"60%\" align=\"center\"><h2>ใบสั่งซื้อ (Purchase Order)</h2></td>
                  <td width=\"40%\" align=\"right\">
                    <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
                      <tr>
                        <td width=\"50%\" align=\"right\"><strong>เลขที่เอกสาร PO.No &nbsp;&nbsp;</strong></td>
                        <td width=\"50%\" align=\"left\"><strong>&nbsp;&nbsp; $select_com_purchase_request_report[no_order]</strong></td>
                      </tr>
                      <tr>
                        <td align=\"right\"><strong>วันที่เอกสาร PO.No &nbsp;&nbsp;</strong></td>
                        <td align=\"left\"><strong>&nbsp;&nbsp; $day_purchasing_jobs</strong></td>
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
        <tr><td><b>จำนวนเงิน(ตัวอักษร)</b></td></tr>
        <tr><td align=\"center\"><br><br>$text_final_price</td></tr>
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
            วันที่ $day_order
            <br>ผู้จัดทำ</td>
            <td width=\"40%\"><br><br>
              .................................................<br>
              (&nbsp;&nbsp;$name_user_purchasing_jobs[name]&nbsp;&nbsp;)<br>
              วันที่ $day_order <br>ผู้ตรวจสอบ
      </td>
            <td width=\"30%\"><br><br>.................................................<br>
            (&nbsp;&nbsp;$name_user_approve[name]&nbsp;&nbsp;)<br>
            วันที่ $day_purchasing_jobs <br>ผู้มีอำนาจลงนาม</td>
            </tr>
          </table>
          หมายเหตุ <br>
&nbsp;&nbsp;1. บริษัทฯจะชำระเงินค่าสินค้าโดยระบุชื่อผู้ขายตามที่ปารกฎในใบสั่งซื้อเท่านั้น<br>
&nbsp;&nbsp;2. ผู้ขายจะต้องระบุตัวเลขที่ใบสั่งซื้อฉบับนี้ในใบแจ้งหนี้ที่เรียกเก็บเงินจากบริษัทฯ<br>
&nbsp;&nbsp;3.การวางบิลและการรับเช็ค เป็นไปตามเวลาที่บริษัทฯกำหนดไว้<br>
";
return $com_purchase_request_report;
}
#################################################



//<tr>
//<td align=\"center\">1.</td>
//<td>&nbsp;&nbsp;&nbsp;$select_crmd[name_raw_material_details] ($select_crmd[name_accounts_payable])</td>
//<td align=\"center\">$unit_quantity</td>
//<td align=\"center\">$unit_price</td>
//<td>&nbsp;&nbsp;&nbsp;$select_com_purchase_request_report[comment_cpr]</td>
//</tr>













$name_output="PO$select_com_purchase_request_report[no_order]";
$file_output="PO$select_com_purchase_request_report[no_order].pdf";


//============================================================+
// File name   : example_005.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 005 for TCPDF class
//               Multicell
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Multicell
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// สร้าง Class ใหม่ขึ้นมา กำหนดให้สืบทอดจาก Class ของ TCPDF
class MindphpTCPDF extends TCPDF
{
    // สร้าง function ชื่อ Header สำหรับปรับแต่งการแสดงผลในส่วนหัวของเอกสาร
    public function Header()
    {
        // สร้างคำสั่ง HTML ในตัวอย่างนี้ สร้างตาราง 2 คอลัมน์ 
        // คอลัมน์แรก สำหรับแสดงรูปภาพ คำสั่ง HTML แสดงรูปภาพและต้องใช้ URL แบบเต็ม
        // คอลัมน์ที่สอง สำหรับแสดงข้อความ
        $html = '
	<table>
		<tr>
			<td width="70"><img src="tcpdf_logo.jpg" width="60" /></td>
			<td width="400" align="left"><h1>บริษัท ที.ที.เค. ฟีดมิลล์ จำกัด<br>T.T.K. Feedmill Co., Ltd.</h1></td></tr></table><hr />';
        $this->writeHTMLCell('', '', '', '', $html);
    }

    // สร้าง function ชื่อ Footer สำหรับปรับแต่งการแสดงผลในส่วนท้ายของเอกสาร
    public function Footer()
    {
        // กำหนดตำแหน่งที่จะแสดงรูปภาพและข้อความ 15mm นับจากท้ายเอกสาร
        $this->SetY(-20);
        // คำสั่งสำหรับแทรกรูปภาพ กำหนดที่อยู่ไฟล์รูปภาพในเครื่องของเรา
        //$this->Image('tcpdf_logo.jpg');
        
        // สำหรับตัวอักษรที่จะใช้คือ thsarabun เป็นตัวหนา และขนาดอักษรคือ 10
        $this->SetFont('thsarabun', '', 13);
        // คำสั่งสำหรับแสดงข้อความ โดยกำหนด ความกว้าง และ ความสูงของข้อความได้ใน 2 ช่องแรก
        // ช่องที่ 3 คือข้อความที่แสดง ส่วนค่า C คือ กำหนดให้แสดงข้อความตรงกลาง
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
			        $html = '
					<hr />
					<table width=100% border=1>
			<tr>
			<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 33/1 ม.4 ต.บางตีนเป็ด อ.เมือง จ.ฉะเชิงเทรา 24000. TEL&FAX: 038-086027<br>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; E-mail: t.t.k.feedmill@gmail.com  เลขประจำตัวผู้เสียภาษี 0245531000128
				</td></tr></table>';
		$this->writeHTMLCell('', '', '', '', $html, 0, 0, 0, true, '', true);
        //$this->writeHTMLCell('', '', '', '', '<center></center>');
//        $this->Cell('', '', '', 0, false, '');
        
        // สำหรับตัวอักษรที่จะใช้คือ thsarabun เป็นตัวเอียง และขนาดอักษรคือ 8
        $this->SetFont('thsarabun', 'I', 8);
        // คำสั่งสำหรับแสดงข้อความ โดยกำหนด ความกว้าง และ ความสูงของข้อความได้ใน 2 ช่องแรก
        // ช่องที่ 3 คือข้อความที่แสดง $this->getAliasNumPage() คือ หมายเลขหน้าปัจจุบัน และ $this->getAliasNbPages() จำนวนหน้าทั้งหมด
        // ส่วนค่า R คือ กำหนดให้แสดงข้อความชิดขวา
        $this->Cell('', '', 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R');
    }
}

$pdf = new MindphpTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MindphpTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator($name_output);
$pdf->SetAuthor($name_output);
$pdf->SetTitle($name_output);
$pdf->SetSubject($name_output);
$pdf->SetKeywords('ttkForm01, TCPDF, PDF, example, guide');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

//$html = "";
$pdf->SetFont('thsarabun', '', 14);
$pdf->writeHTMLCell(0, 0, '', 0, $html, 0, 1, 0, true, '', true);

$pdf->Output($file_output, 'I');

//============================================================+
// END OF FILE
//============================================================+