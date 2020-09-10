<?php 
include('../lib_org.php');
  $option = $_GET['option'];
  $id_loc_master = $_GET['id_loc_master'];

if($option=='del'){ del_location_master($id_loc_master,$id_loc);}
  $id_loc = $_GET['id_loc'];
$print_location = print_location($id_loc);
#if($_SESSION['id_emp_use']){ 
#}else{print"$error_login";}


##############################################################################################
function print_location($id_loc){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
$sql = "SELECT name_location,floor_location FROM location_group WHERE id_loc='$id_loc' ";
$query = mysqli_query($connect,$sql);
while($row = mysqli_fetch_array($query)){
$name_location= $row[name_location];
$floor_location= $row[floor_location];
}
$sql = "SELECT id_loc_master,name_location_master,floor_master FROM location_master WHERE id_loc='$id_loc' order by id_loc_master ASC";
$query = mysqli_query($connect,$sql);
while($row = mysqli_fetch_array($query)){
$id_loc_master= $row[id_loc_master];
$name_location_master = $row[name_location_master];
$floor_master = $row[floor_master];

$print[$i]="<tr bgcolor='#FFFFFF' onMouseOver=\"this.bgColor='FFFF66';\" onMouseOut=\"this.bgColor='#FFFFFF';\"> 
<td align='center'><font size=2>$floor_master</font></td>
<td align='left'><font size=2>&nbsp;$name_location_master</font></td>
<td align='center'><a href=\"#\" onclick=\"window.open('add_location_master.php?floor_location=$floor_location&id_loc_master=$id_loc_master&option=edit','_blank','toolbar=0,width=550,height=200')\"><font size=2>แก้ไข</font></a></td>
<td align='center'><font size=2><a href='#' onclick=\"del('$id_loc_master')\">ลบ</a></font></td>
</tr>";
$i++;
}
if(count($print)==0){$print[0]="";}
$print_location1 = implode("\n",$print);

$print_location = "
<center><strong><font size=2>$name_location</font></strong></center><br>
<table width='90%' border='0' cellpadding='0' cellspacing='1' bgcolor='#000000'>
<tr bgcolor='#cccccc'>
	<td align='center' width='10%'><b><font size=2>ชั้น</font></b></td>
		<td align='center' width='70%'><b><font size=2>ห้อง/สถานที่<a href=\"#\" onclick=\"window.open('add_location_master.php?floor_location=$floor_location&id_loc=$id_loc&option=add','_blank','toolbar=0,width=550,height=200')\">(เพิ่ม)</a></font></b></td>
	<td align='center' width='10%'><b><font size=2>แก้ไข</font></b></td>
	<td align='center' width='10%'><b><font size=2>ลบ</font></b></td>
</tr>
$print_location1
</table>";
return $print_location;
}

##############################################################################################
function del_location_master($id_loc_master,$id_loc){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql = "DELETE   FROM location_master  WHERE id_loc_master = '$id_loc_master' ";
$query = mysqli_query($connect,$sql);
if($query!=0){echo "<script>window.location='?id_loc=$id_loc';</script>";}
}
###############################################################################################
echo "

<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>หน้าจัดการ อาคาร/สถานที่</title>
<script>
function del(n){
if(confirm('ยืนยันการลบข้อมูล?')==true){
window.location='?option=del&id_loc_master='+n+'&id_loc=$id_loc';
}
}
</script>
</head>
<body>
$head_html
<center>
<br>
<b><font size=2>หน้าจัดการ ห้อง/สถานที่ </font></b>
$print_location  <br>
<input type='button' value='<<Back' onclick=\"javascript:window.location='edit_location.php';\">
</center>
</body>
</html>
";
?>
