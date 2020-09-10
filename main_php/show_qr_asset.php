<?php
include("../lib_org.php");
$id_ae = $_GET['id_ae'];
$id_asset = $_GET['id_asset'];
$name_asset = $_GET['name_asset'];
print_asset_dept($id_ae,$id_asset,$name_asset);
#################################################
function print_asset_dept($id_ae,$id_asset,$name_asset){
print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>$id_asset &nbsp; $name_asset</TITLE>
</HEAD>
<BODY leftMargin=0 topMargin=0 marginwidth='0' marginheight='0'>
<table align='center' width='220' border='0'>
<tr align='center'>
	<td><img src=\"https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=http://localhost/main_php/print_asset_pc.php?asset_edu=$id_ae&choe=UTF-8\" title=\"$id_asset &nbsp; $name_asset\" />
</td>
</tr>
</table>
</body>
</html>
";
exit;
}
#################################################

?>