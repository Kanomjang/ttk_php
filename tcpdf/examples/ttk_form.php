<?php
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

$pdf->SetCreator('ttkForm01');
$pdf->SetAuthor('ttkForm01');
$pdf->SetTitle('ttkForm01');
$pdf->SetSubject('ttkForm01');
$pdf->SetKeywords('ttkForm01, TCPDF, PDF, example, guide');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

$html = "";
$pdf->SetFont('dejavusans', '', 14);
$pdf->writeHTMLCell(0, 0, '', 50, $html, 0, 1, 0, true, '', true);

$pdf->Output('ttkForm.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
