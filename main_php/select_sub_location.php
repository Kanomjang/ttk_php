 <?php  
 include("../lib_org.php");
   $id_loc2 = $_GET['floor_location'];

   $select_loaction_master =  select_loaction_master($id_loc2);
##################################
function select_loaction_master($id_loc2){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
$sql = "SELECT name_location_master,id_loc_master FROM location_master,location_group  
WHERE location_master.id_loc= '$id_loc2' 
and location_group.id_loc = location_master.id_loc ";

$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$id_loc_master = $row[id_loc_master];
$name_location_master = $row[name_location_master];
$print[$i]="<option value='$id_loc_master'>$name_location_master</option>";
$i++;
}
$select_loaction_master  = implode("\n",$print);

return $select_loaction_master;
}
##################################

 echo "
$sql
<option value='0'>เลือก</option>
$select_loaction_master


";
 ?>
