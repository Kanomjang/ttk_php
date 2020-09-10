<?php
include("../lib_org.php");
if($_SESSION['id_emp_use']){ 
  $asset_edu = $_GET['asset_edu'];
$id_ae=$asset_edu;
$use_login='1';
$select_asset=select_asset($id_ae);
$select_asset_dept=select_asset_dept($id_ae);
$select_asset_location=select_asset_location($id_ae);
print_asset_dept($select_asset,$select_asset_dept,$select_asset_location,$use_login);


}else{
  $id_ae = $_GET['asset_edu'];
  $use_login='0';
$select_asset=select_asset($id_ae);
$select_asset_dept=select_asset_dept($id_ae);
$select_asset_location=select_asset_location($id_ae);
print_asset_dept($select_asset,$select_asset_dept,$select_asset_location);
}
#################################################
function select_asset($id_ae){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database 4");
$sql_query="select asset_edu.id_ae,id_asset,name_asset,name_asset_group,name_asset_type,date_order,year_asset,cost_price,asset_type.usage_life,carcass_price,((cost_price-carcass_price)/asset_type.usage_life) as straight_line from asset_edu,asset_type,asset_group where asset_edu.id_at = asset_type.id_at and asset_group.id_ag = asset_type.id_ag and asset_edu.id_ae='$id_ae'";
$shows=mysqli_query($connect,$sql_query);
while($row=mysqli_fetch_array($shows)){
	$select_asset['id_asset']=$row["id_asset"];
	$select_asset['name_asset']=$row["name_asset"];
	$select_asset['name_asset_group']=$row["name_asset_group"];
	$select_asset['name_asset_type']=$row["name_asset_type"];
	$select_asset['date_order']=$row["date_order"];
	$select_asset['year_asset']=$row["year_asset"];
	$select_asset['cost_price']=$row["cost_price"];
	$select_asset['usage_life']=$row["usage_life"];
	$select_asset['straight_line']=$row["straight_line"];
	$select_asset['carcass_price']=$row["carcass_price"];
}
return $select_asset;
}
#################################################
function select_asset_dept($id_ae){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database 4");
$sql_query="select id_ad,asset_dept.id_ae,asset_dept.id_dept,date_dept,name_dept from asset_dept,dept_edu where 
dept_edu.id_dept = asset_dept.id_dept and 
asset_dept.id_ae='$id_ae' order by id_ad DESC limit 0,1";
$shows=mysqli_query($connect,$sql_query);
while($row=mysqli_fetch_array($shows)){
	$select_asset_dept['id_dept']=$row["id_dept"];
	$select_asset_dept['date_dept']=$row["date_dept"];
	$select_asset_dept['name_dept']=$row["name_dept"];
}
return $select_asset_dept;
}
#################################################
function select_asset_location($id_ae){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database 4");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_location,floor_master,name_location_master,date_location from location_master,asset_location,location_group where 
asset_location.id_loc_master = location_master.id_loc_master and 
location_group.id_loc = location_master.id_loc and 
asset_location.id_ae='$id_ae' order by id_al DESC limit 0,1";
$shows=mysqli_query($connect,$sql_query);
while($row=mysqli_fetch_array($shows)){
	$select_asset_location['name_location']=$row["name_location"];
	$select_asset_location['floor_master']=$row["floor_master"];
	$select_asset_location['name_location_master']=$row["name_location_master"];
	$select_asset_location['date_location']=$row["date_location"];
}
return $select_asset_location;
}
#################################################
function name_month($name_month){
if($name_month=='01'){$name_month="มกราคม";}
if($name_month=='02'){$name_month="กุมภาพันธ์";}
if($name_month=='03'){$name_month="มีนาคม";}
if($name_month=='04'){$name_month="เมษายน";}
if($name_month=='05'){$name_month="พฤษภาคม";}
if($name_month=='06'){$name_month="มิถุนายน";}
if($name_month=='07'){$name_month="กรกฎาคม";}
if($name_month=='08'){$name_month="สิงหาคม";}
if($name_month=='09'){$name_month="กันยายน";}
if($name_month=='10'){$name_month="ตุลาคม";}
if($name_month=='11'){$name_month="พฤศจิกายน";}
if($name_month=='12'){$name_month="ธันวาคม";}
return $name_month;
}
#################################################
function name_emp($id_emp)
{
  global $connect,$database,$host,$user,$passwd,$icons;
  $connect = mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
  $sql_query = "select f_name,name,l_name,active,pic,email from emp_edu where id_emp='$id_emp'";
  $shows = mysqli_query($connect,$sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $name_emp['f_name'] = $row["f_name"];
    $name_emp['name'] = $row["name"];
    $name_emp['l_name'] = $row["l_name"];
    $name_emp['pic'] = $row["pic"];
    $name_emp['active'] = $row["active"];
    $name_emp['email'] = $row["email"];
  }
  mysqli_close($connect);
  return $name_emp;
}
#################################################
function print_asset_dept($select_asset,$select_asset_dept,$select_asset_location){
  global $host, $user, $passwd, $database;
$time_year=date('Y');
if($select_asset['year_asset']=="0000"){
	$tyear_asset="ไม่ระบุปีซื้อ";
	$t_year_asset="ไม่ทราบปีที่ใช้งาน";
}else{
	$tyear_asset=$select_asset['year_asset']+543;
	$t_year_asset=$time_year-$select_asset['year_asset'];

	}
if(($tyear_asset-$t_year_asset)>'0'){$straight_line=round($select_asset['cost_price']-($select_asset['straight_line']*$t_year_asset));
}else{
$straight_line=$select_asset['carcass_price'];
}
if($t_year_asset =='0'){$t_year_asset="ปีแรก";}else{$t_year_asset="$t_year_asset ปี";}
if($select_asset['date_order']=="0000-00-00"){
	$day_date_order="ไม่ระบุวัน";
}else{
	$date_order_yyyymmdd=explode("-",$select_asset['date_order']);
	$name_month=name_month($date_order_yyyymmdd[1]);
	$t_yyyy=$date_order_yyyymmdd[0]+543;
	$daydateorder=intval($date_order_yyyymmdd[2]);
	$day_date_order="$daydateorder $name_month $t_yyyy";
}

if($select_asset_dept['date_dept']=="0000-00-00"or $select_asset_dept['date_dept']==""){
	$day_date_dept="ไม่ระบุวัน";
}else{
	$date_dept_yyyymmdd=explode("-",$select_asset_dept['date_dept']);
	$name_month=name_month($date_dept_yyyymmdd[1]);
	$t_yyyy=$date_dept_yyyymmdd[0]+543;
	$daydatedept=intval($date_dept_yyyymmdd[2]);
	$day_date_dept="$daydatedept $name_month $t_yyyy";
}
if($select_asset_location['date_location']=="0000-00-00"or $select_asset_location['date_location']==""){
	$day_date_location="ไม่ระบุวัน";
}else{

	$date_location_yyyymmdd=explode("-",$select_asset_location['date_location']);
	$name_month=name_month($date_location_yyyymmdd[1]);
	$t_yyyy=$date_location_yyyymmdd[0]+543;
	$daydatelocationr=intval($date_location_yyyymmdd[2]);
	$day_date_location="$daydatelocationr $name_month $t_yyyy";
}
$url=$_SERVER['REQUEST_URI'];

if($_SESSION['id_emp_use']){ 
	$name_emp=name_emp($_SESSION['id_emp_use']);
	$print_login="[ <a href='login_emp.php'>แจ้งซ่อม</A> ] [ <a href='login_emp.php'>แจ้งย้าย</A> ] [ <a href='#' onclick=\"StartPageLogoff('logoff.php')\">ออกจากระบบ</a> ]";
	$print_history="
<center>ประวัติการดูแลรักษา/ซ่อมแซม
</center>
<TABLE width='90%' celpadding=0 cellspacing=1 bgcolor=#FFFFFF align='center'>
	<tr bgcolor=#00cc99>
		<td align='center' width='20%'>วันที่</td>
		<td align='center' width='80%'>รายการ</td>
	</tr>
	<tr bgcolor=#ccccff>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	</table><br><br>";
	}else{
	$print_login="
	<form name='addgroup'  method='POST' action='login_page.php?url2=$url'>
User:<input type='text' name='login_tb'  style='font-size:12px;height:20;width:80px;background:#ffffff;border:1px solid #cccccc;color:;'value=''> Passwd:<input type='password'   name='passwd_tb' style='font-size:12px;height:20;width:80px;background:#ffffff;border:1px solid #cccccc;color:;' value='' >
<input type='submit' style='font-size:12px;width:80px;height:20px;' value='เข้าสู่ระบบ' style='border:1px solid #cccccc;font-size:10pt;cursor:hand;' name='login_page'>
	</form>
	";
$select_asset['cost_price']="*****";
$straight_line ="*****";
$print_history="";
	}
print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE></TITLE>
<style type='text/css'>
a:link { 
color: #3366FF; 
font-size : 10pt;
text-decoration: none
}
a:visited { 
color: #3366FF;
font-size : 10pt;
text-decoration: none
}
a:active { 
color: #3366FF; 
font-size : 10pt;
text-decoration: underline
}
A:hover {
text-decoration: none;
font-size : 10pt;
color: #FF0000;
}
</style>
<script language='JavaScript'>
function StartPageNew(name_file){
    myWindow = window.open (name_file,'_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=400,height=300')
}
</script>
</HEAD>
<BODY leftMargin=0 topMargin=0 marginwidth='0' marginheight='0'>
<br>

<CENTER><FONT SIZE=2><B>หน้าทะเบียนทรัพย์สิน </B></FONT></CENTER>
<br>
<TABLE width='96%' celpadding=0 cellspacing=1 bgcolor=#FFFFFF align='center'>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B> </B></FONT></TD>
	<TD align='right'><FONT SIZE=2>$print_login &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right' width='30%'><FONT SIZE=2><B>เลขทะเบียนทรัพย์สิน : </B></FONT></TD>
	<TD align='left' width='70%'><FONT SIZE=2> $select_asset[id_asset]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>ประเภททรัพย์สิน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset[name_asset_group]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>หมวดทรัพย์สิน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset[name_asset_type]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>รายละเอียดทรัพย์สิน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset[name_asset]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>ราคาซื้อ : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset[cost_price] บาท</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>ปีสั่งซื้อ : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $tyear_asset</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>อายุการใช้งาน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset[usage_life] ปี</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>ใช้งานแล้ว : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $t_year_asset</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>มูลค่าทรัพย์สินปัจจุบัน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $straight_line บาท</FONT></TD>
</TR>

<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>วันสั่งซื้อ : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $day_date_order</FONT></TD>
</TR>

<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>สังกัดหน่วยงาน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $select_asset_dept[name_dept]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>วันที่สังกัดหน่วยงาน : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $day_date_dept</FONT></TD>
</TR>

<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>ที่ตั้ง : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2>$select_asset_location[name_location_master] ชั้น $select_asset_location[floor_master] $select_asset_location[name_location]</FONT></TD>
</TR>
<TR bgcolor='#FFFFFF'>
	<TD align='right'><FONT SIZE=2><B>วันที่ตั้ง : </B></FONT></TD>
	<TD align='left'><FONT SIZE=2> $day_date_location</FONT></TD>
</TR>

</TABLE>
<br>
$print_history
<CENTER><INPUT TYPE='button' value='Close Windows' onclick='window.close()'></CENTER>
<br>
</body>
</html>
";
exit;
}
#################################################

?>
