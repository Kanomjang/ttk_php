<?php
include("../../lib_org.php");
$position_active='9';

$dept_position_active=dept_position_active($position_active);
if ($_GET['com_purchase_request_report']) {
  $id_cpr = $_GET['com_purchase_request_report'];
  $id_cpr = base64_decode(base64_decode("$id_cpr"));
  $select_com_purchase_request_report = select_com_purchase_request_report($id_cpr);
  $html = com_purchase_request_report($select_com_purchase_request_report,$dept_position_active,$id_cpr,$position_active);
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
  $sql_query = "SELECT id_purchasing_jobs,date_purchasing_jobs,no_order,id_use_approve,date_approve,com_purchase_request.title_cpr,com_purchase_request.date_order,com_purchase_request.date_use,com_purchase_request.id_dept,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.unit_price,com_purchase_request.comment_cpr,com_purchase_request.approve,com_purchase_request.id_use FROM com_purchase_request WHERE com_purchase_request.id_cpr='$id_cpr'";
  $query = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($query)) {
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
######################หน้าแสดง###########################
function com_purchase_request_report($select_com_purchase_request_report,$dept_position_active,$id_cpr,$position_active)
{
  global $dayTH,$monthTH;   
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

  if($select_com_purchase_request_report['approve']!="0"){
    $name_user_approve=name_user($select_com_purchase_request_report['id_use_approve']);
    $date01 = new DateTime($select_com_purchase_request_report['date_approve']);
    $dd01=$date01->format('j');
    $mm01=$monthTH[$date01->format('n')];
    $yy01=$date01->format('Y')+543;
    $day_approve="$dd01 $mm01 $yy01";
}else{
    $name_user_approve['name']=".................................................";
    $day_approve="............................................";
}

  $com_purchase_request_report="
  <div class=\"box-body\">
  <div class=\"row\">
  <div class=\"col-md-10\"></div>
  <div class=\"col-md-12\"></div>
  <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td width=\"100%\" align=\"center\"><h2>ใบขอซื้อ (Purchase Request)</h2></td></tr></table>
  <br><br>

          <div class=\"col-md-12\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
          <tr><td width=\"100%\" align=\"right\"><strong>เลขที่ PR.No $select_com_purchase_request_report[no_order]</strong>
          </td>
          </tr></table>

          <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
          <tr>
           <td width=\"15%\" align=\"right\"><B>หน่วยงาน : &nbsp;&nbsp;&nbsp;</B></td>
           <td width=\"35%\">&nbsp;&nbsp;&nbsp;$name_dept</td>
           <td width=\"15%\" align=\"right\"><B>ผู้ขอซื้อ : &nbsp;&nbsp;&nbsp;</B></td>
           <td width=\"35%\">&nbsp;&nbsp;&nbsp;$name_user[name]</td>
          </tr>
          <tr>
           <td align=\"right\"><B>วันต้องการใช้ : &nbsp;&nbsp;&nbsp;</B></td>
           <td>&nbsp;&nbsp;&nbsp;$day_use</td>
           <td align=\"right\"><B>ตำแหน่ง : &nbsp;&nbsp;&nbsp;</B></td>
           <td>&nbsp;&nbsp;&nbsp;$select_position_emp</td>
          </tr>
          <tr>
           <td rowspan=\"2\" align=\"right\"><B>เรื่อง : &nbsp;&nbsp;&nbsp;</B></td>
           <td rowspan=\"2\" width=\"35%\">&nbsp;&nbsp;&nbsp;$select_com_purchase_request_report[title_cpr]</td>
           <td align=\"right\"><B>วันที่ : &nbsp;&nbsp;&nbsp;</B></td>
           <td>&nbsp;&nbsp;&nbsp;$day_order</td>
          </tr>
          <tr>
          <td align=\"right\"><B>โทรศัพท์ : &nbsp;&nbsp;&nbsp;</B></td>
          <td>&nbsp;&nbsp;&nbsp;$name_user[tel]</td>
         </tr>
        </table><br><br><br>
        <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
         <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>ลำดับที่</B></td>
         <td width=\"30%\" align=\"center\" bgcolor=\"fefefe\"><B>รายการ</B></td>
         <td width=\"15%\" align=\"center\" bgcolor=\"fefefe\"><B>จำนวน/หน่วย</B></td>
         <td width=\"15%\" align=\"center\" bgcolor=\"fefefe\"><B>ราคา/หน่วย</B></td>
         <td width=\"30%\" align=\"center\" bgcolor=\"fefefe\"><B>หมายเหตุ/จุดประสงค์</B></td>
        </tr>
        <tr>
         <td align=\"center\">1.</td>
         <td>&nbsp;&nbsp;&nbsp;$select_crmd[name_raw_material_details] ($select_crmd[name_accounts_payable])</td>
         <td align=\"center\">$unit_quantity</td>
         <td align=\"center\">$unit_price</td>
         <td>&nbsp;&nbsp;&nbsp;$select_com_purchase_request_report[comment_cpr]</td>
        </tr>
        <tr>
        <td align=\"center\"></td>
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
      </tr>
      <tr>
      <td align=\"center\"></td>
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
    </tr>
    <tr>
    <td align=\"center\"></td>
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
  </tr>
  <tr>
  <td align=\"center\"></td>
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
</tr>
<tr>
<td align=\"center\"></td>
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
</tr>
<tr>
<td align=\"center\"></td>
<td>&nbsp;</td>
<td align=\"center\"></td>
<td align=\"center\"></td>
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
       <td width=\"40%\"><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        $approve_box01 &nbsp;อนุมัติ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        $approve_box02 &nbsp;ไม่อนุมัติ<br><br>
        .................................................<br>
        (&nbsp;&nbsp;$name_user_approve[name]&nbsp;&nbsp;)<br>
        วันที่ $day_approve <br>ผู้อนุมัติ
</td>
       <td width=\"30%\"><br><br><br><br>.................................................<br>
       (&nbsp;&nbsp;$name_user_purchasing_jobs[name]&nbsp;&nbsp;)<br>
       วันที่ $day_purchasing_jobs <br>ฝ่ายจัดซื้อ</td>
      </tr>
    </table>

  </div><!-- /.col-md-10 -->
  </div><!-- End Row-->

</div><!-- End Row-->
</div><!-- /.box-body -->

";
return $com_purchase_request_report;
}
#################################################
















$name_output="PR$select_com_purchase_request_report[no_order]";
$file_output="PR$select_com_purchase_request_report[no_order].pdf";


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