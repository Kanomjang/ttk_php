 <?php
include("../lib_org.php");
 $time_year = $_GET['time_year']-543;
 $id_at = $_GET['id_at'];
$id_at1=explode("-",$id_at);
$select_code_ad =  select_code_ad($time_year,$id_at1[0]);
##################################
function select_code_ad($time_year,$id_at){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
$sql = "SELECT max(code_ad) FROM asset_edu WHERE id_at= '$id_at' and year_asset='$time_year' ";
//print"$sql <br>";
$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
	$code_ad=$row["max(code_ad)"];

}

$select_code_ad=$code_ad+1;
return $select_code_ad;
}
##################################
echo $select_code_ad;
 ?>