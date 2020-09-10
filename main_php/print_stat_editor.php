<?php
include("../lib_org.php");
$print_emp_fix_work=show_fix_work_emp();
$print_emp_fix_work1=show_fix_work_emp1();
$print_emp_fix_work2=show_fix_work_emp2();
#################################################
function select_fix_work_emp($type_selection){
global $connect,$database;
$i=0;
$fix_work="";
$fix_work_emp="";
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_emp from fix_work_emp where type_selection=$type_selection order by id_emp ASC";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
	$id_emp=$row["id_emp"];
	$data_emp=name_emp($id_emp);
		if (($i%2) == '1') {$bg_tr="bgcolor='#FFFF99'";}else{$bg_tr="bgcolor='#FFFFFF'";}
$decode_get="id_emp=$id_emp&$id_emp";
$code = unpack("C*", $decode_get);$get_decode2 = implode("%", $code);
$fix_work[$i]="
<tr $bg_tr>
<td width='20%' align=center><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>$id_emp</A></FONT></td>
<td width='70%' align=left><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>&nbsp;&nbsp;&nbsp;$data_emp[name_emp]</A></FONT></td>
</tr>
";
$i++;
}
$fix_work_emp = implode("\n", $fix_work);
return $fix_work_emp;
}
#################################################

#################################################
function show_fix_work_emp(){
$fix_work_emp = select_fix_work_emp('3');
$print_emp_fix_work="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดหน่วยงาน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('2');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>คำถามที่พบบ่อย</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('1');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>จัดการข่าว</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('4');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดฝ่าย</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('5');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดนโยบายของโรงเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('6');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดหัวข้อ ข้อมูลหน่วยงาน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('7');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดคณะกรรมการ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('8');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดตำแหน่งผู้อำนวยการ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('10');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>จัดการอัลบั้มภาพ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('11');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>จัดการปฏิทิน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#

$fix_work_emp = select_fix_work_emp('41');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>บันทึกข้อมูลศิษย์เก่า</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('42');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ดูข้อมูลศิษย์เก่า</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('43');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>บันทึกวารสาร</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('44');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>บันทึกคำสั่งแต่งตั้ง</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('45');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ประเภทแผนงาน/โครงการ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('46');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>เลือกประเภทข่าว</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('47');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>จัดกลุ่มการส่งงาน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp('48');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>เลือกประเภทปฏิทิน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
return $print_emp_fix_work;
}

#################################################


function select_fix_work_emp1($type_selection){
global $connect,$database;
$i=0;
$fix_work="";
$fix_work_emp="";
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_emp from fix_work_emp where type_selection=$type_selection order by id_emp ASC";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
	$id_emp=$row["id_emp"];
	$data_emp=name_emp($id_emp);
		if (($i%2) == '1') {$bg_tr="bgcolor='#FFFF99'";}else{$bg_tr="bgcolor='#FFFFFF'";}
$decode_get="id_emp=$id_emp&$id_emp";
$code = unpack("C*", $decode_get);$get_decode2 = implode("%", $code);
$fix_work[$i]="
<tr $bg_tr>
<td width='20%' align=center><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>$id_emp</A></FONT></td>
<td width='70%' align=left><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>&nbsp;&nbsp;&nbsp;$data_emp[name_emp]</A></FONT></td>
</tr>
";
$i++;
}
$fix_work_emp = implode("\n", $fix_work);
return $fix_work_emp;
}
#################################################

function show_fix_work_emp1(){

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#

#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('30');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดชั้นเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('31');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดนักเรียนในชั้นเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('32');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดรายวิชาที่เปิดสอน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('33');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>บันทึก ขาด ลา มาสาย นักเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('34');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดประวัตินักเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('35');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>รูปแบบตารางเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('36');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดตารางเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('37');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>พฤติกรรม นักเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('38');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ค้นหาพฤติกรรมนักเรียน</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp1('40');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#C0C0C0'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>Send SMS</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#

return $print_emp_fix_work;
}

#################################################


function select_fix_work_emp2($type_selection){
global $connect,$database;
$i=0;
$fix_work="";
$fix_work_emp="";
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_emp from fix_work_emp where type_selection=$type_selection order by id_emp ASC";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
	$id_emp=$row["id_emp"];
	$data_emp=name_emp($id_emp);
		if (($i%2) == '1') {$bg_tr="bgcolor='#FFFF99'";}else{$bg_tr="bgcolor='#FFFFFF'";}
$decode_get="id_emp=$id_emp&$id_emp";
$code = unpack("C*", $decode_get);$get_decode2 = implode("%", $code);
$fix_work[$i]="
<tr $bg_tr>
<td width='20%' align=center><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>$id_emp</A></FONT></td>
<td width='70%' align=left><FONT SIZE='2'><A HREF='/cgi-bin/cgi_edu/$database/post_news/search_work_emp.pl?$get_decode2' target='_blank'>&nbsp;&nbsp;&nbsp;$data_emp[name_emp]</A></FONT></td>
</tr>
";
$i++;
}
$fix_work_emp = implode("\n", $fix_work);
return $fix_work_emp;
}
#################################################
function show_fix_work_emp2(){

$fix_work_emp = select_fix_work_emp2('12');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>File Manager</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('13');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ส่งจดหมายข่าว</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('14');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดประวัติผู้เกี่ยวข้อง</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('20');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ควบคุมโครงการ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('21');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ย้ายโครงการ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('22');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ตัดงบประมาณ</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('23');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>ประวัติการทำงานบุคลากร</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#
$fix_work_emp = select_fix_work_emp2('24');
$print_emp_fix_work.="
<table width='95%' border='0' cellspacing = '1' cellpadding='0' bgcolor='#000000 width=50%'>
  <tr bgcolor='#13879F'>
    <td width='95%' align=left colspan='2'><FONT SIZE='2' COLOR='#006600'>&nbsp;&nbsp;&nbsp;<B>กำหนดประวัติบุคลากร</B></FONT>&nbsp;&nbsp; <FONT SIZE=2>(ดูข้อมูลสรุป)</FONT></td>
  </tr>
<tr bgcolor='#FFCC99'>
<td width='20%' align=center><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>เลขประจำตัว</B></FONT></td>
<td width='70%' align=left><FONT SIZE='2' COLOR='#0000FF'>&nbsp;&nbsp;&nbsp;<B>ชื่อ - นามสกุล</B></FONT></td>
</tr>
	$fix_work_emp
</table> <br>
";
#------------------------------------------------------------------------------------------#



return $print_emp_fix_work;
}
######################################################################
print"
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'
'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=windows-874'>
<title>หน้าที่ในระบบ SWIS</title>
<style type='text/css'>
<!--
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
	color: #CC9900;
}
a:active {
	text-decoration: none;
}
-->
</style>
</head>

<body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
$head_html
$left
<BR><BR>
<CENTER>



<table width='100%' border='1' cellspacing='0' cellpadding='0'>
<tr><td width='50%' align='center'  valign='top'>

<table width='95%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align=left  bgcolor='#996600'>
<FONT SIZE='2' COLOR='#FFFFFF'><B>&nbsp;&nbsp;:: หน้าที่ในระบบ SWIS</B></FONT>
</td></tr></table>
<hr width='95%'style='border:1px; color:#006600;'>
<FONT SIZE=2><B>งานตามโครงสร้างบริหาร</B></FONT>
$print_emp_fix_work

<hr width='95%'style='border:1px; color:#006600;'>
<FONT SIZE=2><B>ระบบงานบริการและฟังก์ชันพิเศษ</B></FONT>
$print_emp_fix_work2
<BR><BR>

</td><td width='50%' valign='top'>

<table width='95%' border='0' cellspacing='0' cellpadding='0'>
<tr><td align=left  bgcolor='#996600'>
<FONT SIZE='2' COLOR='#FFFFFF'><B>&nbsp;&nbsp;:: หน้าที่ในระบบ SWIS</B></FONT>
</td></tr></table>
<hr width='95%'style='border:1px; color:#006600;'>
<FONT SIZE=2><B>งานการเรียนการสอน</B></FONT>
$print_emp_fix_work1

<hr width='95%'style='border:1px; color:#006600;'>
<FONT SIZE=2><B>ระบบงานบริการและฟังก์ชันพิเศษ</B></FONT>


<BR><BR>

</td></tr></table>

<hr width='95%'style='border:1px; color:#006600;'>








<input type='submit' name='Submit' value='Close Windows' onClick='window.close()'>
</CENTER>
$right

</body>
</html>
";
?>
