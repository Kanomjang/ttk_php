<?php include("../lib.php");
$function_version='64';

if(!isset($_SESSION["id_emp_use"])){

if(!empty($closeme)){
if($send_questionnaire){
$id_qhe=base64_decode(base64_decode("$id_qhe"));
send_questionnaire($id_qhe,$id_emp_use);
}

if (array_key_exists("closeme",$_POST)){
?>
<script language="JavaScript">
window.opener.location.reload(true);
window.close();
</script>
<?php
exit();
}

}


$time_year=time_year($time_year);
$select_for_year=select_year($time_year);
$name_emp=name_emp($id_emp_use);
$select_fix_work_emp=select_fix_work_emp($id_emp_use);

$date = time();
$tbq = "question_".$date;

create_table($tbq);
questionnaire_($id_emp_use);
questionnaire_plot($id_emp_use);
questionnaire_profile($id_emp_use);

$questionnaire_alert = questionnaire_alert($id_emp_use);
$swis_version=swis_version($function_version);
print_emp($select_fix_work_emp,$select_for_year,$time_year,$name_emp,$id_emp_use,$swis_version);

}else{  print "<CENTER><BR><BR>
<form name='addgroup' target='_parent' method='POST' action='../main_php/login_page.php?url2=$url'>
<FONT SIZE=2><B>เข้าสู่ระบบ</B></FONT>
<table width='300'><tr><td align='right'>
<FONT SIZE=2>User:</FONT></td><td><input type='text' name='login_tb' ></td></tr>
<tr><td align='right'>
<FONT SIZE=2> Passwd:</FONT></td><td><input type='password'   name='passwd_tb' ></td></tr>
<tr><td colspan='2' align='center'>
<input type='submit'  value='Submit'  name='login_page'>
</td></tr></table>
</form></CENTER>";exit;}

#################################################
function send_questionnaire($id_qhe,$id_emp_use){
global $host,$user,$passwd,$database;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="SELECT id_gqe FROM group_questionnaire_emp,questionnaire_head_edu where 
questionnaire_head_edu.id_qhe=group_questionnaire_emp.id_qhe and 
questionnaire_head_edu.id_qhe='$id_qhe' and 
group_questionnaire_emp.id_emp='$id_emp_use'";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
		$id_gqe=$row["id_gqe"];
$sql_query="update group_questionnaire_emp set finish_questionire='1',date_end=CURDATE() where id_gqe='$id_gqe'";
mysqli_db_query($connect,$sql_query);
}
}
#################################################
function select_fix_work_emp($id_emp_use){
global $host,$user,$passwd,$database;
$i=6;
$decode_get="id_emp=$id_emp_use";$code = unpack("C*", $decode_get);$get_decode = implode("%", $code);
	$select_fix_work_emp1[0]="<LI><A HREF='/cgi-bin/cgi_edu/$database/post_news/check_login_post.pl?$get_decode' target='_blank'>แจ้งข่าว/สร้างแฟ้มผลงาน</A>";
	$select_fix_work_emp1[1]="<LI><A HREF='/cgi-bin/cgi_edu/$database/post_news/add_informed.pl?$get_decode' target='_blank'>สาระน่ารู้</A>";
	$select_fix_work_emp1[2]="<LI><A HREF='/html_edu/cgi-bin/$database/plot/emp_make_plot.php?$get_decode' target='_blank'>สร้างแผนงาน/โครงการ</A>";
	$select_fix_work_emp1[3]="<LI><A HREF='emp_research.php?$get_decode' target='_blank'>บันทึกงานวิจัย</A>";
	/*$select_fix_work_emp1[4]="<LI><A HREF='/cgi-bin/cgi_edu/$database/work_record/print_work_record.pl?$get_decode' target='_blank'>บันทึกงาน</A>";*/
	$select_fix_work_emp1[4]="<LI><A HREF=/html_edu/cgi-bin/$database/main_php/view_report_pic_main.php?' target='_blank'>จัดการอัลบั้มภาพส่วนตัว</A>";
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database 4");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_menu,text_select,link_menu,perl_file,data_link from fix_work_emp,menu_emp where id_emp='$id_emp_use' and type_selection=id_menu order by group_menu ASC";
$shows=mysqli_query($connect,$sql_query);;
$count_fix_work_emp1=mysqli_num_rows($shows);
$count_fix_work_emp=$count_fix_work_emp1+$i;
if($count_fix_work_emp > 10){
$j=ceil($count_fix_work_emp/2);
}
while($row=mysqli_fetch_array($shows)){
	$name_menu=$row["name_menu"];
	$text_select=$row["text_select"];
	$id_position=$row["id_position"];
	$link_menu=$row["link_menu"];
	$perl_file=$row["perl_file"];
	$data_link=$row["data_link"];
if($perl_file=='1'){$link_perl_file="/cgi-bin/cgi_edu/$database";$decode_get="id_emp=$id_emp_use";$code = unpack("C*", $decode_get);$get_decode = implode("%", $code);}else{$link_perl_file="";$get_decode = "";}
if($count_fix_work_emp > 10){
if($i < $j){
$select_fix_work_emp1[$i]="<LI><A HREF='$link_perl_file$link_menu?$get_decode$data_link' target='_blank'>$name_menu</A>";
}else{
$select_fix_work_emp2[$i]="<LI><A HREF='$link_perl_file$link_menu?$get_decode$data_link' target='_blank'>$name_menu</A>";
}
}else{
$select_fix_work_emp1[$i]="<LI><A HREF='$link_perl_file$link_menu?$get_decode$data_link' target='_blank'>$name_menu</A>";
}


$i++;
}
if($select_fix_work_emp1[0]==""){$select_fix_work_emp1[0]="&nbsp;<br>";}
if($select_fix_work_emp2[0]==""){$select_fix_work_emp2[0]="&nbsp;<br>";}
$select_fix_work_emp3 = implode("\n", $select_fix_work_emp1);
$select_fix_work_emp4 = implode("\n", $select_fix_work_emp2);

$count_fix2=count($select_fix_work_emp2);
if($count_fix2>=1){
$select_count_fix2="
<TD>
<UL><FONT size='3'><B>&nbsp;</B></FONT>
$select_fix_work_emp4
</UL>
</TD>";
}else{$select_count_fix2="";}

$select_fix_work_emp="
<TABLE width='80%' border='0' cellspacing='0' cellpadding='0' align='center'>
<TR Valign='top'>
	<TD align='left'>
<UL><FONT size='3'><B>งานในระบบ</B></FONT>
$select_fix_work_emp3
</UL>
</TD>
$select_count_fix2
</TR>
</TABLE>";

return $select_fix_work_emp;
}
###################################################################
function questionnaire_($id_emp_use){
global $host,$user,$passwd,$database;
$connect = mysqli_connect($host,$user,$passwd,$database) or die ("not connect");
//mysqli_select_db($database,$connect) or die("not select database");
$sql = "
SELECT questionnaire_head_edu.id_qhe,head_questionnaire,DATE_FORMAT(begin_date, '%Y-%m-%e') as begin_date,DATE_FORMAT(end_date, '%Y-%m-%e') as end_date 
FROM questionnaire_head_edu,questionnaire_edu,group_questionnaire_emp where 
questionnaire_head_edu.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.finish_questionire ='0' and 
questionnaire_head_edu.id_plot ='0' and 
questionnaire_head_edu.id_profile ='0' and 
begin_date <= CURRENT_DATE()and begin_date != '0000-00-00' and 
(CURRENT_DATE() <= end_date or end_date='0000-00-00') and 
group_questionnaire_emp.id_emp='$id_emp_use' 
group by head_questionnaire order by questionnaire_head_edu.id_qhe asc
";
$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$end_date = $row[end_date];
$id_qhe = $row[id_qhe];
$head_questionnaire = $row[head_questionnaire];

insert_list_questionnair($id_qhe,$head_questionnaire,$end_date);
$i++;
}

}
########################################################################
function create_table($tbq){
global $host,$user,$passwd,$database;
$connect = mysqli_connect($host,$user,$passwd,$database) or die ("not connect");
//mysqli_select_db($database,$connect) or die("not select database");

$sql = "
CREATE TABLE  $tbq (
  `id_alert` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_qhe` int(20) unsigned DEFAULT NULL,
  `head_questionnaire` text,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id_alert`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
";

$query = mysqli_query($connect,$sql);
}
########################################################################
function insert_list_questionnair($id_qhe,$head_questionnaire,$end_date){
global $host,$user,$passwd,$database,$tbq;
$connect = mysqli_connect($host,$user,$passwd,$database) or die ("not connect");
//mysqli_select_db($database,$connect) or die("not select database");
$sql = "INSERT INTO $tbq (id_qhe,head_questionnaire,end_date) VALUES ('$id_qhe','$head_questionnaire','$end_date')";
$query = mysqli_query($connect,$sql);
}
########################################################################
function questionnaire_alert($id_emp_use){
global $host,$user,$passwd,$database,$tbq;
$connect = mysqli_connect($host,$user,$passwd,$database) or die ("not connect");
//mysqli_select_db($database,$connect) or die("not select database");


$date1  = mktime(0, 0, 0, date("m")  , date("d"), date("Y")); 
$sql = "SELECT id_qhe,head_questionnaire,end_date FROM $tbq ORDER BY end_date ASC ";

$query = mysqli_query($connect,$sql);
while($row=mysqli_fetch_array($query)){
$end_date = $row[end_date];
$id_qhe = $row[id_qhe];
$head_questionnaire = $row[head_questionnaire];
$end_date = explode("-",$end_date);

$end_date2  = mktime(0, 0, 0, $end_date[1]  , $end_date[2], $end_date[0]); 

$end_date = $end_date2 - $date1;
$result = $end_date / (60 * 60 * 24);

$encode_id_qhe = base64_encode( base64_encode($id_qhe));




if($result <= 3 and $result >= 0 ){  
	$n=1;
if($result == 0 ){ $date_fix ="วันนี้วันสุดท้าย"; $color="#FF0000";}else{ $date_fix = "$result วัน" ; $color="";}
	$print[$i]="<tr bgcolor='#FFFFFF'><td align='left'>&nbsp; <a  href='print_questionnaire.php?id_qhe=$encode_id_qhe' onclick='window.close();'><font size=2 color='$color'>$head_questionnaire </font></a></td>
	<td align='center'><a  href='print_questionnaire.php?id_qhe=$encode_id_qhe' onclick='window.close();'><font size=2 color='$color' >$date_fix</font></a></td></tr>";   }
	
	else {$print[$i]="&nbsp;"; }
$i++;
}

$questionnaire_alert = implode("\n",$print);


$encode_message = base64_encode( base64_encode($questionnaire_alert));


drop_table($tbq);


if($n ==1){
	echo "
	<script>
            var urls = 'questionnaire_alert.php?message=$encode_message';
			var styles = 'dialogHeight:250px;dialogWidht:300px;center:yes;status:no';
			window.showModalDialog(urls, parent, styles);
</script>
";
}


}

######################################################################
function questionnaire_plot($id_emp_use){
global $host,$user,$passwd,$database;
$i=0;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="
SELECT questionnaire_head_edu.id_qhe,head_questionnaire,DATE_FORMAT(begin_date, '%Y-%m-%e') as begin_date,DATE_FORMAT(end_date, '%Y-%m-%e') as end_date 
FROM questionnaire_head_edu,questionnaire_edu,group_questionnaire_emp where 
questionnaire_head_edu.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.finish_questionire ='0' and 
questionnaire_head_edu.id_plot > '0' and 
begin_date <= CURRENT_DATE()and begin_date != '0000-00-00' and 
(CURRENT_DATE() <= end_date or end_date='0000-00-00') and 
group_questionnaire_emp.id_emp='$id_emp_use' 
group by head_questionnaire order by questionnaire_head_edu.id_qhe asc
";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
	$id_qhe=$row["id_qhe"];
	$head_questionnaire=$row["head_questionnaire"];
	$end_date=$row["end_date"];
insert_list_questionnair($id_qhe,$head_questionnaire,$end_date);
	$i++;
}

}
#######################################################################
function questionnaire_profile($id_emp_use){
global $host,$user,$passwd,$database;
$i=0;
$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="
SELECT questionnaire_head_edu.id_qhe,head_questionnaire,DATE_FORMAT(begin_date, '%Y-%m-%e') as begin_date,DATE_FORMAT(end_date, '%Y-%m-%e') as end_date 
FROM questionnaire_head_edu,questionnaire_edu,group_questionnaire_emp where 
questionnaire_head_edu.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.id_qhe=questionnaire_edu.id_qhe and 
group_questionnaire_emp.finish_questionire ='0' and 
questionnaire_head_edu.id_profile > '0' and 
begin_date <= CURRENT_DATE()and begin_date != '0000-00-00' and 
(CURRENT_DATE() <= end_date or end_date='0000-00-00') and 
group_questionnaire_emp.id_emp='$id_emp_use' 
group by head_questionnaire order by questionnaire_head_edu.id_qhe asc
";
$shows=mysqli_query($connect,$sql_query);;
while($row=mysqli_fetch_array($shows)){
	$id_qhe=$row["id_qhe"];
	$head_questionnaire=$row["head_questionnaire"];
	$end_date=$row["end_date"];
insert_list_questionnair($id_qhe,$head_questionnaire,$end_date);
	$i++;
}

}
########################################################################
function drop_table($tbq){
global $host,$user,$passwd,$database,$tbq;
$connect = mysqli_connect($host,$user,$passwd,$database) or die ("not connect");
//mysqli_select_db($database,$connect) or die("not select database");
$sql = "DROP TABLE $tbq";
$query = mysqli_query($connect,$sql);
}
#######################################################################
function print_emp($select_fix_work_emp,$select_for_year,$time_year,$name_emp,$id_emp_use,$swis_version){
global $database,$head_html,$left,$right;

$t_year=$time_year+543;
	$decode_get="id_emp=$id_emp_use";
$code = unpack("C*", $decode_get);$get_decode = implode("%", $code);

 $count_work = count_work($id_emp_use);
echo "
<html>
<head>
<TITLE>$swis_version</TITLE>
<script type='text/javascript' src='../javascript/ajaxtabs/ajaxtabs.js'></script>
<style>@import url('../javascript/ajaxtabs/ajaxtabs.css');</style>
<script type='text/javascript'>
	function selectWork(chosenoption){
		 if (chosenoption.value!='nothing'){
			  window.open(chosenoption.value, '', '') 
		}
}
</script>
<style type='text/css'>
a:link { 
color: #3366FF; 
font-size : 10pt;
text-decoration: none
}
a:visited { 
color: #3366FF;
font-size : 10pt;
text-decoration: none
}
a:active { 
color: #3366FF; 
font-size : 10pt;
text-decoration: underline
}
A:hover {
text-decoration: none;
font-size : 10pt;
color: #FF0000;
}
</style>
</head>
<body>
$head_html
$left
<br>
<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
	<TR align='center'>
		<TD>
			<FORM METHOD=POST>
				<SELECT NAME='time_year'>$select_for_year</SELECT><INPUT TYPE='submit' value='&nbsp;&nbsp;OK&nbsp;&nbsp;'>
			</FORM>
		</TD>
	</TR>
</table>

<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<TR>
<TD><FONT size='2'>&nbsp;&nbsp;&nbsp;&nbsp;<B>หน้าจัดการข้อมูลของ $name_emp[name_emp] ปีการศึกษา $t_year</B></FONT><BR>
<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href='checked_alumni.php'><b><font size=2 color=#FF0000>** เข้าร่วมตรวจสอบข้อมูลศิษย์เก่าฯ !!</font></b></a>
<BR></TD>
</TR>
</table>

<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<TR>
<TD>

<ul id='countrytabs' class='shadetabs'>
<li><a href='emp_report_personal.php?time_year=$time_year'  rel='#iframe' class='selected'>ข้อมูลส่วนตัว</a></li>
<li><a href='swis_emp_report.php?time_year=$time_year' rel='#iframe'>SWIS REPORT</a></li>
<li><a href='emp_stucture.php?time_year=$time_year' rel='#iframe'>งานตามสายงาน</a></li>
<li><a href='emp_learning.php?time_year=$time_year' rel='#iframe'>งานสอนเรียน</a></li>
<li><a href='emp_consign.php?time_year=$time_year'  rel='#iframe'>งานมอบหมาย</a></li>
<li><a href='research.php?time_year=$time_year'  rel='#iframe'>วิจัยและสำรวจ</a></li> 
<li><a href='emp_stu_data.php?time_year=$time_year'  rel='#iframe'>ฐานข้อมูลบุคลากรและนักเรียน</a></li>
<li><a href='print_work_record.php?time_year=$time_year'  rel='#iframe'>บันทึกงาน/ขอใช้บริการ/อาคารสถานที่</a></li>
<!-- <li><a href='inbox_mail.php?time_year=$time_year'  rel='#iframe'>กล่องจดหมาย (Inactive)</a></li> -->

<li><a href='print_calendar_all_chart_old_emp.php?time_year=$time_year'  rel='#iframe'>ปฏิทินกิจกรรม</a></li>
</ul>
<div id='countrydivcontainer' style='border:1px solid gray; width:96%; margin-bottom: 1em; padding: 10px'>
<TABLE width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
<TR>
<TD width='15%' Valign='top'>
<IMG SRC='/html_edu/$database/temp_pic/emp_pic/$name_emp[pic]' BORDER=0 ALT=''>
</TD>
<TD Valign='top'>
$select_fix_work_emp
</TD>
</TR>
</TABLE>
<BR>
</div>
<script type='text/javascript'>
var countries=new ddajaxtabs('countrytabs', 'countrydivcontainer')
countries.setpersist(true)
countries.setselectedClassTarget('link')
countries.init()
</script>
</TD>
</TR>
</table>


$right
</body>
</html>

";

}
#########################################################################
function  count_work($id_emp_use){
global $host,$user,$passwd,$database;

$connect= mysqli_connect($host,$user,$passwd,$database) or die("not connect database");
//mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$date_after  = mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")); // 3 วันที่แล้ว
$date_after = date( 'Y-m-d' , $date_after );
$sql ="select count(timer_work) as c from work_record where alarm = CURRENT_DATE()   and id_emp='$id_emp_use' ";
$query = mysqli_query($connect,$sql);
$row=mysqli_fetch_array($query);
$count_work=$row[c];
return $count_work;
}
##########################################################################
?>
