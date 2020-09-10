<?php include('../lib.php');
  $option = $_GET['option'];
  $floor_location = $_GET['floor_location'];
  $id_loc = $_GET['id_loc'];

if($option=='add') {

for($i=1;$i<=$floor_location;$i++){$select_floor_master.="<option value=$i>$i</option>";}


print_add($select_floor_master);}
if($option==edit){print_edit($id_loc_master,$floor_location);}
if($add){
add_location($id_loc,$name_location_master,$floor_master);
}
if($edit){
edit_location($id_loc_master,$name_location_master,$floor_master);
}

if($_SESSION['id_emp_use']){ 
}else{print"$error_login";}

###################################################################################
function add_location($id_loc,$name_location_master,$floor_master){
 global $host,$user,$passwd,$database,$id_emp_use;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql = "INSERT INTO location_master (id_loc,name_location_master,id_use,floor_master) VALUES ('$id_loc','$name_location_master','$id_emp_use','$floor_master')  ";
$query = mysqli_query($connect,$sql);
if($query!=0){ echo "<script>window.opener.location.reload();this.close();</script>";}
}
####################################################################################
function print_add($select_floor_master){
global $id_emp_use;
echo "
<html>
<head>
<title></title>
</head>
<body>
<center><br><br>
<form method='post' action='' name='form1' onsubmit='return check();'>
<table width='100%'>
<tr><td align='right'><font size=2><b>ห้อง/สถานที่ : </b></font></td><td align='left'><input type='text' name='name_location_master' size='40'></td></tr>
<tr><td align='right'><font size=2><b>ชั้น : </b></font></td><td align='left'><SELECT NAME='floor_master'>$select_floor_master</SELECT></td></tr>
<tr><td colspan='2' align='center'><input type='submit' name='add' value=' &nbsp; OK &nbsp; '>&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='Submit' value='<<Back' onClick='window.close()'></td></tr>
</table>
	</form>
</center>
<script>
function check(){
var v1 = document.form1;
if(v1.name_location_master.value==''){ alert('กรุณาป้อน ห้อง หรือสถานที่ ');v1.name_location_master.focus();return false;}
}
</script>
</body>
</html>
";
}

##############################################################################
function print_edit($id_loc_master,$floor_location){
 global $host,$user,$passwd,$database,$id_emp_use;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql = "SELECT  name_location_master,floor_master FROM  location_master WHERE  id_loc_master = '$id_loc_master'";
$query = mysqli_query($connect,$sql);
$row = mysqli_fetch_array($query);
$name_location_master = $row[name_location_master];
$floor_master = $row[floor_master];
for($i=1;$i<=$floor_location;$i++){
	if($i==$floor_master){$select_floor_master.="<option value=$i selected>$i</option>";}else{$select_floor_master.="<option value=$i>$i</option>";}
	}

echo "
<html>
<head>
<title></title>
</head>
<body>
<center><br><br>
<form method='post' action='' name='form1' onsubmit='return check();'>
<table width='100%'>
<tr><td align='right'><font size=2><b>ห้อง/สถานที่ : </b></font></td><td align='left'><input type='text' name='name_location_master' size='40' value='$name_location_master'></td></tr>
<tr><td align='right'><font size=2><b>ชั้น : </b></font></td><td align='left'><SELECT NAME='floor_master'>$select_floor_master</SELECT></td></tr>
<tr><td colspan='2' align='center'><input type='submit' name='edit' value=' &nbsp; OK &nbsp; '>&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='Submit' value='<<Back' onClick='window.close()'></td></tr>
</table>
	</form>
</center>
<script>
function check(){
var v1 = document.form1;
if(v1.name_location_master.value==''){ alert('กรุณาป้อน ห้อง หรือสถานที่ ');v1.name_location_master.focus();return false;}
}
</script>
</body>
</html>
";
}
###########################################################################
function edit_location($id_loc_master,$name_location_master,$floor_master){
 global $host,$user,$passwd,$database,$id_emp_use;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql = "UPDATE location_master SET name_location_master = '$name_location_master',floor_master = '$floor_master' WHERE id_loc_master='$id_loc_master' ";
$query = mysqli_query($connect,$sql);
if($query!=0){ echo "<script>window.opener.location.reload();this.close();</script>";}
}
#################################################################################
?>
