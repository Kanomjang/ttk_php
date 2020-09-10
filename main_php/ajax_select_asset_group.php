 <?php
 include("../lib_org.php");
 $id_ag = $_GET['id_ag'];
$select_asset_type =  select_asset_type($id_ag);
##################################
function select_asset_type($id_ag){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
$sql = "SELECT id_at,code_at,name_asset_type FROM asset_type WHERE id_ag= '$id_ag' order by id_at ASC";
$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$id_at = $row[id_at];
$code_at = $row[code_at];
$name_asset_type = $row[name_asset_type];
//$print[$i]="<option value='$id_at'>$name_asset_type</option>";
$print[$i]="<option value='$id_at-$code_at'>$name_asset_type</option>";
$i++;
}
$select_asset_type  = implode("\n",$print);

return $select_asset_type;
}
##################################

 echo "

<option value='0'>เลือก</option>
$select_asset_type


";
 ?>