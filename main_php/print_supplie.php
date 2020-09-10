<?php include("../lib_org.php");

if($_SESSION['id_emp_use']){ # ส่วนของ บุคลากร

print_addons();
if($action==del){
del($group_id);
}

}else{print"$error_login";}

##############################################################################
function print_addons(){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้1");

$sql = "SELECT group_id,group_name FROM addons_item_group";
$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$group_id=$row[group_id];
$group_name=$row[group_name];

//$group_name = iconv("UTF-8","utf-8",$group_name);

$print[$i]="
<tr bgcolor='#FFFFFF'>
	<td align='left'>&nbsp;&nbsp;<font size=2><a href='print_supplie_sub1.php?group_id=$group_id'>$group_name</a></font></td>	<td align='center'><font size=2><a href=\"#\" onclick=\"window.open('edit_group_supplie.php?group_id=$group_id&action=edit','_blank','toolbar=0,status=0,width=450px,height=250px,top=200,left=400');\">แก้ไข</a></font></td>	<td align='center'><font size=2 ><a href='#' onclick='check_del($group_id)'>ลบ</a></font></td>
</tr>
";
$i++;
}
$print_addons  =  implode("\n",$print);
echo "
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset utf-8' />
<script>
function check_del(group_id){
if(confirm('ยืนยันการลบข้อมูล?')==true){
window.location='?action=del&group_id='+group_id;
}
}
</script>
</head>
<body>
<center>
<br><br><font size=3><b>หน้าจัดการวัสดุอุปกรณ์สำนักงาน</b></font>
<br><br>
<a href=\"#\" onclick=\"window.open('edit_group_supplie.php?action=add','_blank','toolbar=0,status=0,width=450px,height=250px,top=200,left=400');\">เพิ่มกลุ่มรายการ</a>
<table width='90%' cellpadding='0' cellspacing='1' border='0' bgcolor='#000000'>
<tr bgcolor='#cccccc' >
	<td align='center' width='80%'><font size=2><b>กลุ่มรายการ</b></font></td>	<td width='10%'></td><td width='10%'></td>
</tr>
$print_addons
</table>
</center>
</body>
</html>
";
}
##############################################################################
function del($group_id){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้1");
$sql = "DELETE FROM addons_item_group WHERE group_id='$group_id' ";
$query = mysqli_query($connect,$sql);
 if($query!=0){
echo"
<script language='Javscript' type='text/javascript'>
window.location='?';
</script>";
}
}
##############################################################################














?>
