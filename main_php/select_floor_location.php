 <?php
 include("../lib_org.php");
  $id_loc2 = $_GET['id_loc2'];
$select_loaction_master =  select_loaction_master($id_loc2);
##################################
function select_loaction_master($id_loc2){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
$sql = "SELECT floor_location FROM location_group WHERE location_group.id_loc= '$id_loc2'";
$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$floor_location = $row[floor_location];
}
for($i=0;$i<$floor_location;$i++){
	$j=$i+1;
$print[$i]="<option value='$j&id_loc=$id_loc2'>$j</option>";
}
$select_loaction_master  = implode("\n",$print);

return $select_loaction_master;
}
##################################

 echo "

<option value='0'>เลือก</option>
$select_loaction_master


";
 ?>