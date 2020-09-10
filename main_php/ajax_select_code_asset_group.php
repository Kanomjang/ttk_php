 <?php
 include("../lib_org.php");
 $id_ag = $_GET['id_ag'];

$select_asset_type =  select_asset_type($id_ag);
##################################
function select_asset_type($id_ag){
	global $host,$user,$passwd,$database;
	$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database ");
	$sql = "SELECT code_asset FROM asset_group WHERE id_ag= '$id_ag'";
	$query = mysqli_query($connect,$sql);
	while($row=mysqli_fetch_array($query)){
	$select_asset_type = $row['code_asset'];
}
return $select_asset_type;
}
##################################

 echo "$select_asset_type";
 ?>