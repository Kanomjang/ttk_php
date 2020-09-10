<?php include("../lib_org.php");

if($_SESSION['id_emp_use']){ # ส่วนของ บุคลากร
	  $action = $_GET['action'];
	  $edit = $_GET['edit'];
	  $add = $_GET['add'];

if($action=="edit"){
print_addons($group_id);
}
if($action=="add"){
print_addons_add();
}
if($edit){  save_edit($group_id,$group_name);}
if($add){  save_add($group_name);}
}else{print"$error_login";}
#####################################################################
function print_addons($group_id){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้1");

$sql = "SELECT group_id,group_name FROM addons_item_group WHERE group_id='$group_id' ";
$query = mysqli_query($connect,$sql);
$row=mysqli_fetch_array($query);
$group_id=$row[group_id];
$group_name=$row[group_name];

//$group_name = iconv("UTF-8","utf-8",$group_name);
echo "
<html>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<body onload = focus();>
<script>
function focus(){
document.form1.group_name.focus();
}
</script>

<br><br><br>
<center>
<form method='post' action='' name='form1'>
	<font size=2><b>หัวข้อ :</b></font> <input type='text' name='group_name' value='$group_name' size='50'>
	<br><input type='submit' name='edit' value='&nbsp;&nbsp;OK&nbsp;&nbsp;'>
</form>
</center>
</body>
</html>
";

}
#####################################################################
 function save_edit($group_id,$group_name){
 global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้1");
//$group_name = iconv("utf-8","UTF-8",$group_name);
$sql = "UPDATE  addons_item_group  SET group_name='$group_name' WHERE  group_id = '$group_id' ";
$query = mysqli_query($connect,$sql);
 if($query!=0){

echo"<script language='Javscript' type='text/javascript'>
window.opener.location.reload() //ให้หน้าหลัก Refresh
setInterval('close()', 0) //ปิดหน้านี้ภายใน 2 วินาที 1000=1 วินาที
</script>";

}
 }
#####################################################################
function print_addons_add(){

echo "
<html>
<body onload = focus();>
<script>
function focus(){
document.form1.group_name.focus();
}
</script>

<br><br><br>
<center>
<form method='post' action='' name='form1'>
	<font size=2><b>หัวข้อ :</b></font> <input type='text' name='group_name' value='' size='50'>
	<br><input type='submit' name='add' value='&nbsp;&nbsp;OK&nbsp;&nbsp;'>
</form>
</center>
</body>
</html>

";
}

#########################################################################################
function   save_add($group_name){

 global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้1");

//$group_name = iconv("utf-8","UTF-8",$group_name);
$sql = "INSERT INTO addons_item_group (group_name)VALUES('$group_name')";
$query = mysqli_query($connect,$sql);
 if($query!=0){
echo"<script language='Javscript' type='text/javascript'>
window.opener.location.reload() //ให้หน้าหลัก Refresh
setInterval('close()', 0) //ปิดหน้านี้ภายใน 2 วินาที 1000=1 วินาที
</script>";
}
}
#########################################################################################

?>
