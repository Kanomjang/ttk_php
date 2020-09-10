<?php
Session_Start();
#header('Content-Type: text/html; charset=utf-8');
include("tooltip.php");

$host='localhost';
$user='webmaster';
$passwd='object';
$database='wtk';
$homepage="http://www.wtkschool.ccs2.go.th/main/index1.php";
$www_home="http://www.wtkschool.ccs2.go.th/main/index1.php";
#$eof_home="http://www.wtk.ac.th/mcp_applications/e-office";
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
mysql_query('SET NAMES UTF8');
$title_head_html="โรงเรียนวัดท่าเกวียน(สัยอุทิศ)";
$title_thai="โรงเรียนวัดท่าเกวียน(สัยอุทิศ)";
$database_eoffice = "eof_wtk";
$icons="/html_edu/$database/icons/temp_page_1";
$red_gif="/html_edu/$database/icons/red.gif";

if($id_menu!=""){
$id_menu = base64_decode(base64_decode($id_menu));	
authentication($id_menu,$id_emp_use); 

}
 ####################limit_day######################
function authentication($id_menu,$id_emp_use){
global $host,$user,$passwd,$database,$www_home;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query ="SELECT id_fwe  FROM fix_work_emp WHERE id_emp='$id_emp_use' and type_selection='$id_menu' ";
$query = mysql_db_query($database,$sql_query);
$row = mysql_fetch_array($query);
$id_fwe = $row[id_fwe];

if($id_fwe==""){ print "<script>alert('ไม่อนุญาติให้ใช้งาน!!'); window.location='$www_home?';</script>";}


}
 ###################################################
$print_side_3_3="/html_edu/$database/icons/closed_book.gif";
 $print_side_4_3="/html_edu/$database/icons/open_book.gif";
$month_begin_limit="05";
$day_begin_limit="01";
$month_end_limit="04";
$day_end_limit="30";
$temp_emp_pic="/html_edu/$database/temp_pic/emp_pic";#ที่อยู่รูปบุคคลากร
$temp_student_pic="/html_edu/$database/temp_pic/student_pic";#ที่อยู่รูปนักเรียน
######################Joop 22/12/2547###########################
$url=$_SERVER['REQUEST_URI'];

if(session_is_registered("id_emp_use")){ 
	$name_emp=name_emp($id_emp_use);
	$print_login="[ <a href='/html_edu/cgi-bin/$database/main_php/login_emp.php'>User Management</A> ] $name_emp[name_emp] [ <a href='#' onclick=\"StartPageLogoff('/html_edu/cgi-bin/$database/main_php/logoff.php')\">ออกจากระบบ</a> ]";
	}else{
	$print_login="
	<form name='addgroup'  method='POST' action='/html_edu/cgi-bin/$database/main_php/login_page.php?url2=$url'>
User:<input type='text' name='login_tb'  style='font-size:12px;height:20;width:80px;background:#ffffff;border:1px solid #cccccc;color:;'value=''> Passwd:<input type='password'   name='passwd_tb' style='font-size:12px;height:20;width:80px;background:#ffffff;border:1px solid #cccccc;color:;' value='' >
<input type='submit' style='font-size:12px;width:80px;height:20px;' value='เข้าสู่ระบบ' style='border:1px solid #cccccc;font-size:10pt;cursor:hand;' name='login_page'>
	</form>
	
	";
		//$print_login="[ <a href='#' onclick=\"StartPageLogoff('/html_edu/cgi-bin/$database/main_php/login_page.php')\">Login</a> ]";
		}
if($id_student_use){$name_student=name_student($id_student_use);$print_login="[ <a href='#' onclick=\"StartPageFull('/html_edu/cgi-bin/$database/main_php/login_stu.php')\">Student Management</A> ] $name_student[name] [ <a href='#' onclick=\"StartPageLogoff('/html_edu/cgi-bin/$database/main_php/logoff.php')\">Logout</a> ]";}
###############Style#######################
function count_emp_cal2_noti($id_emp_use){
global $connect,$database,$icons;
mysql_select_db($database,$connect) or die("not select database");
$sql = "SELECT  id_cal,id_cal_time,id_duty FROM notifications WHERE id_emp ='$id_emp_use'  and noti_update='1' ";
$query = mysql_query($sql);
$n =0;
while($row = mysql_fetch_array($query)){
$id_cal = $row[id_cal];
$id_cal_time = $row[id_cal_time];
$id_duty = $row[id_duty];

if($id_cal!=0){ $n=$n+1;}
if($id_cal_time!=0){ $n=$n+1;}
if($id_duty!=0){ $n=$n+1;}


}
$number = strlen($n);
if($number==1){
$n1="<b><font size=4 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}
if($number==2){
$n1="<b><font size=3 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}

if($n=="0"){$n1="";}

return  $n1;
}

##########################################
function count_emp_repair_noti($id_emp_use){
global $connect,$database,$icons;
mysql_select_db($database,$connect) or die("not select database");
$sql = "SELECT  id_er FROM notifications WHERE id_emp ='$id_emp_use'  and noti_update='1' ";
$query = mysql_query($sql);
$n =0;
while($row = mysql_fetch_array($query)){
$id_er = $row[id_er];
if($id_er!=0){ $n=$n+1;}
}

$number = strlen($n);
if($number==1){
$n="<b><font size=4 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}
if($number==2){
$n="<b><font size=3 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}

if($n=="0"){$n1="";}


return  $n1;
}


##########################################
function count_emp_talk_noti($id_emp_use){
global $connect,$database,$icons;
mysql_select_db($database,$connect) or die("not select database");
$sql = "SELECT  id_position_emp FROM notifications WHERE id_emp ='$id_emp_use'  and noti_update='1' ";
$query = mysql_query($sql);
$n =0;
while($row = mysql_fetch_array($query)){
$id_position_emp = $row[id_position_emp];
if($id_position_emp!=0){ $n=$n+1;}
}


$number = strlen($n);
if($number==1){
$n="<b><font size=4 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}
if($number==2){
$n="<b><font size=3 color=#FFFF99 style=\"BACKGROUND-COLOR:#000000\">$n</font></b>";
}


if($n=="0"){$n1="";}


return  $n1;
}

##################


 //$count_emp_cal2  = count_emp_cal2_noti($id_emp_use);
 //$count_emp_repair = count_emp_repair_noti($id_emp_use);
 //$count_emp_talk = count_emp_talk_noti($id_emp_use);

/*if($count_emp_cal2!="" ||  $count_emp_repair!="" ||  $count_emp_talk!=""){
$print_noti="<table width='350' cellspacing='0' cellpadding='0'  align='left' border='0'>
<tr>
	<td align='right' background='$icons/Calendar-icon.png' width='27' height='23'><a  id='sticky1' rel='/html_edu/cgi-bin/$database/main_php/print_notifications_emp.php' title=\"<font size=2>การแจ้งเตือน</font>\" >$count_emp_cal2</a></td>	
	<td align='right' background='$icons/construc.png' width='27' height='23'><a  id='sticky2' rel='/html_edu/cgi-bin/$database/main_php/print_notifications_emp_repair.php' title=\"<font size='2'>การแจ้งเตือน</font>\" >$count_emp_repair</a></td>
		<td align='right' background='$icons/talk.png' width='27' height='23'><a  id='sticky3' rel='/html_edu/cgi-bin/$database/main_php/print_notifications_talk.php' title=\"<font size='2'>การแจ้งเตือน</font>\" >$count_emp_talk</a></td>
		<td><font size=2 color=#C0C0C0>&nbsp;<<การแจ้งเตือน</font></td>
		
</tr>
</table>";
//}else{  $print_noti="";  }

*/
$year_work=time_year($time_year);

$logo_title = "

<script language='JavaScript'>
var nav=navigator.appName;
var ns=(nav.indexOf('Netscape')!=-1);

if(ns){
if(document.layers){
document.captureEvents(Event.KEYPRESS);
document.onkeypress = cheat;
}
if(document.getElementById){
document.onkeypress = cheat;
}
}
else
{document.onkeypress = cheat;}

var SpecialWord = 'swisinfo';
var SpecialLetter = 0;
var vcheat = false
function cheat(keyStroke)
{
 var eventChooser = (ns)?keyStroke.which: event.keyCode;
 var which = String.fromCharCode(eventChooser).toLowerCase();
 if(which == SpecialWord.charAt(SpecialLetter)){
   SpecialLetter++;
   if (SpecialLetter == SpecialWord.length) alert('โปรแกรมนี้ถูกพัฒนาโดย นาย วีรศักดิ์ คันโธ')
}
else {SpecialLetter = 0;vcheat = false}

}


<!--
 function  update_noti(id_noti){
        var id_loc1 = $.ajax({     
		 url: '/html_edu/cgi-bin/$database/main_php/update_noti.php', 
		data:'id_noti='+id_noti,   
		 async: false 
				}).responseText;          
      
}
 -->
</script>

<style type='text/css'>  
.box {  
    margin: 10px;  
    position: relative;  
    background: #F3F3F3;  
    border: solid 1px #ccc;  
    padding: 10px;  
}  

.line {
border-bottom:1px solid #ccc;
}


</style>

<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td><img src='$icons/head_logo_01.gif'  ></td>
    <td><img src='$icons/head_logo_02.gif'  border='0' ></td>
    <td background='$icons/head_logo_03.gif' width='100%' align='right'>
		</td>
    <td><img src='$icons/head_logo_04.gif'  ></td>
    <td><img src='$icons/head_logo_05.gif'  ></td>
  </tr>
</table>

<table width='100%'  border='0' cellspacing='0' cellpadding='0' height='50'>
  <td  background='/html_edu/$database/icons/temp_page_1/bg_menu_head.gif' width='100%' height='50'>

<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td align='left'><FONT SIZE='2' >&nbsp;&nbsp;[ <a href='$www_home' >Home</a> ]</FONT>
<FONT SIZE='2' COLOR=''>&nbsp;[ <a href='/html_edu/cgi-bin/$database/report/today_events.php' >Today 's Event</a> ]</FONT>
<FONT SIZE='2' COLOR=''>&nbsp;[ <a href='/html_edu/cgi-bin/$database/main_php/print_faq_qa.php' target='_blank'>FAQ</a> ]</FONT>
<FONT SIZE='2' COLOR=''>&nbsp;[ <a href='/html_edu/cgi-bin/$database/main_php/add_work_record_emp.php?mode=add&time_year=$year_work' target='_blank'>บันทึกงาน</a> ]</FONT>
</td>

<td align='left' >
<FONT SIZE='2'>$print_login</FONT>
<script language='JavaScript'>
function StartPageLogoff(name_file){
myWindow = window.open (name_file,'_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=160,height=160')
}
</script>
<script language='JavaScript'>
function StartPageFull(name_file){
myWindow = window.open (name_file,'_blank','toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')
}
</script>

</td>
      </tr>
    </table></td>
	<td  background='/html_edu/$database/icons/temp_page_1/bg_menu_head.gif' width='350'>$print_noti</td>
    <td  background='/html_edu/$database/icons/temp_page_1/bg_menu_head.gif'  height='36' align='right'>
	
		<TABLE width='300' border='0' cellspacing='0' >
<FORM name='form_search_all' METHOD='POST' ACTION='/html_edu/cgi-bin/$database/main_php/search_data.php?type=' >
<TR><TD align='left' width=100%>
<FONT SIZE='2' COLOR=''>ค้นหาข้อมูล:</FONT><INPUT TYPE='text' NAME='keyword' value='' ><input type='submit' name='submit_search' value='ค้นหา'/>
</td>
</tr></FORM></table>

</td>
<td  background='/html_edu/$database/icons/temp_page_1/bg_menu_head.gif'  height='36' align='right'><div id=\"google_translate_element\"></div></td>

</tr></table>
";


$blog_top="
<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td ><img src='$icons/blog_01.gif' ></td>
    <td  background='$icons/blog_02.gif'  width='100%'></td>
    <td ><img src='$icons/blog_03.gif' ></td>
  </tr>
  <tr>
    <td background='$icons/blog_04.gif'></td>
    <td valign='top'>


";

$blog_down="
</td>
    <td background='$icons/blog_06.gif'>&nbsp;</td>
  </tr>
  <tr>
    <td><img src='$icons/blog_07.gif'></td>
    <td background='$icons/blog_08.gif'></td>
    <td><img src='$icons/blog_09.gif' ></td>
  </tr>
</table>
";
#########################################################
$left="<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='33'  background='$icons/left.gif'>&nbsp;</td>
    <td width='auto'>";

$right="</td>
    <td width='33' background='$icons/right.gif'>&nbsp;</td>
  </tr>
</table>";

###################################################


$blog_pic_top="
<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td ><img src='$icons/blog_pic_01.gif' ></td>
    <td  background='$icons/blog_pic_02.gif'  width='100%'></td>
    <td ><img src='$icons/blog_pic_03.gif' ></td>
  </tr>
  <tr>
    <td background='$icons/blog_pic_04.gif'></td>
    <td valign='top'>


";

$blog_pic_down="
</td>
    <td background='$icons/blog_pic_06.gif'>&nbsp;</td>
  </tr>
  <tr>
    <td><img src='$icons/blog_pic_07.gif'></td>
    <td background='$icons/blog_pic_08.gif'></td>
    <td><img src='$icons/blog_pic_09.gif' ></td>
  </tr>
</table>
";
#########################################################
$left="<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='33'  background='$icons/left.gif'>&nbsp;</td>
    <td width='auto'>";

$right="</td>
    <td width='33' background='$icons/right.gif'>&nbsp;</td>
  </tr>
</table>";


########################################################
$head_html="

<HTML ><HEAD><TITLE>$title_head_html</TITLE>
<META http-equiv=Content-Type content='text/html; charset=utf-8'>
<style type=\"text/css\">

a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: underline;
	color: #333333;
}
a:active {
	text-decoration: none;
	color: #000000;
}
</style>

</HEAD>
<body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
$logo_title
</body>
</html>
			";

#################################################

#$id_emp=10290;
#$name_emp=name_emp($id_emp);
#print"$name_emp <br>";
#####################Joop 22/12/2547############################
function check_group_emp($id_emp){
global $connect,$database,$host,$user,$passwd;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select type_fname from emp_group_edu,group_emp where id_emp='$id_emp' and emp_group_edu.id_gemp=group_emp.id_gemp";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$check_group_emp=$row["type_fname"];
}
return $check_group_emp;
}
#####################Joop 22/12/2547############################
function name_emp($id_emp){
global $connect,$database,$host,$user,$passwd,$icons;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select fname,name,l_name,sex,marry_status,status,active,end_day_emp,pic,email from emp_edu where id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$fname=$row["fname"];
	$name=$row["name"];
	$l_name=$row["l_name"];
	$sex=$row["sex"];
	$marry=$row["marry_status"];
	$pic=$row["pic"];
	$status=$row["status"];
	$active=$row["active"];
	$end_day_emp=$row["end_day_emp"];
	$email=$row["email"];
if ($active == "0") {
	$yyyymmdd=explode("-",$end_day_emp);
	$yyyy=$yyyymmdd[0];
	$mm=$yyyymmdd[1];
	$dd=$yyyymmdd[2];
	$t_yyyy=$yyyy+543;



	if ($yyyy == "0000") {
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(ลาออกโดยไม่ระบุวันที่)</FONT>";
		}else{
			$name_month =name_month($mm);
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(ออกเมื่อวันที่ $dd $name_month $t_yyyy)</FONT>";
		}
	}else{
		$stat_active="";
}
			$check_group_emp =check_group_emp($id_emp);
if($check_group_emp=='0'||$check_group_emp==''){$f_name=""; }
if($check_group_emp=='1'){$f_name="ภราดา"; }
if($check_group_emp=='2'){if ($sex =="f") {$f_name="นางสาว";if($marry=='1'){$f_name="นาง";}}else{$f_name="นาย";}}
if($check_group_emp=='3'){if ($sex =="f") {$f_name="มิส";}else{$f_name="มาสเตอร์";}}
if($check_group_emp=='4'){$f_name="คุณ";}
if($check_group_emp=='5'){$f_name=$fname;}
}
if($status>0){$f_name="ภราดา";}
$pic_me = pic_me_free($id_emp);
$print_pic_select2 = print_pic_select2($id_emp);
if($print_pic_select2!="" || $pic_me !=""){   $print_port_pic="<a href='/html_edu/cgi-bin/$database/main_php/print_portfolio_pic.php?id_emp=$id_emp' target='_blank'><img src='$icons/pic_fb.png' border='0' title='ดูภาพอื่นๆ'></a>";  }else{ $print_port_pic="";}
//$data_emp[name_emp]="$f_name &nbsp;$name &nbsp; &nbsp;$l_name$stat_active ";


$data_emp[name_emp]="<font class=\"cluetip\" title=\" | <table><tr><td><a href='/html_edu/cgi-bin/$database/main_php/profile_emp.php?id_emp=$id_emp'><img src='/html_edu/$database/temp_pic/emp_pic/$pic' width='55' height='70' align='bottom'></a></td><td align='left'><b><font size=2><a href='/html_edu/cgi-bin/$database/main_php/profile_emp.php?id_emp=$id_emp'>$name $l_name</a></font></b><br><a href='/update_profile/index.php?id=$id_emp' target='_blank'>Update Profile</a><br>$pic_me $print_pic_select2 <br></td></tr></table>
\">$f_name &nbsp;$name &nbsp; &nbsp;$l_name$stat_active</font>";
$select_pic_emp=select_pic_emp($id_emp);
$data_emp[pic]=$pic;
$data_emp[print_pic]="<img src=\"/html_edu/$database/temp_pic/emp_pic/$pic\" class=\"cluetip\" title=\"|$pic_me $print_pic_select2\">";
$data_emp[email]=$email;
//$data_emp[pic_portfolio]= $print_port_pic;
return $data_emp;
}
########################################################
function  pic_me_free($id_emp){
global $connect,$database,$host,$user,$passwd,$icons;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");

mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql="SELECT id_pic,topic_pic,privacy FROM my_photo WHERE id_use ='$id_emp' and privacy='0'   LIMIT 3 ";
$query=mysql_query($sql);
while($row = mysql_fetch_array($query)){
	$topic_pic = $row[topic_pic];
	$id_pic = $row[id_pic];
	$privacy = $row[privacy];
$sql2="SELECT name_pic FROM my_photo_big WHERE id_pic ='$id_pic' ORDER BY id_pb ASC  ";
$query2=mysql_query($sql2);
$count_pic = mysql_num_rows($query2);
$row2 = mysql_fetch_array($query2);
	$name_pic = $row2[name_pic];

$print[$i]= "
	<a href='/html_edu/cgi-bin/$database/main_php/print_portfolio_pic.php?id_emp=$id_emp&id_pic=$id_pic'><img src='/html_edu/cgi-bin/$database/main_php/resize_jpg.php?w=60&h=70&img=../../../../html_edu/$database/temp_photo/$name_pic' border='0'></a>
";
$i++;
}
if(count($print)==0){$print[0]='';}
$pic_me_free= implode("\n",$print);
return $pic_me_free;
}
#################################################################################
function print_pic_select2($id_emp){
global $host,$user,$passwd,$database,$icons;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database 4");

$sql = "SELECT name_pic,id_pic ,set_group_news.id_ngn FROM set_group_news,name_group_news WHERE set_group_news.id_emp='$id_emp' and
name_group_news.id_ngn = set_group_news.id_ngn and name_pic != '' LIMIT 3  ";
$query=mysql_query($sql);
$n=0;
$i=0;
while($row=mysql_fetch_array($query)){

if($n==6){$print[$i]="</tr><tr>";$n=0;$i++;}
$name_pic= $row[name_pic];
$id_ngn= $row[id_ngn];
$print[$i]= "<a href='/html_edu/cgi-bin/$database/main_php/print_portfolio_pic.php?id_emp=$id_emp'><img src='/html_edu/cgi-bin/$database/main_php/resize_jpg.php?w=60&h=70&img=../../../../html_edu/$database/temp_pic/big_picture/$name_pic' border='0'></a>";
$i++;
$n++;
}
if(count($print)==0){$print[0]='';}
$print_pic_select2 = implode("\n",$print);
return $print_pic_select2;

}
##########################################################
function name_emp_original($id_emp){
global $connect,$database,$host,$user,$passwd,$icons;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select fname,name,l_name,sex,marry,status,active,end_day_emp,pic,email from emp_edu where id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$fname=$row["fname"];
	$name=$row["name"];
	$l_name=$row["l_name"];
	$sex=$row["sex"];
	$marry=$row["marry"];
	$pic=$row["pic"];
	$status=$row["status"];
	$active=$row["active"];
	$end_day_emp=$row["end_day_emp"];
	$email=$row["email"];
if ($active == "0") {
	$yyyymmdd=explode("-",$end_day_emp);
	$yyyy=$yyyymmdd[0];
	$mm=$yyyymmdd[1];
	$dd=$yyyymmdd[2];
	$t_yyyy=$yyyy+543;
	if ($yyyy == "0000") {
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(ลาออกโดยไม่ระบุวันที่)</FONT>";
		}else{
			$name_month =name_month($mm);
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(ออกเมื่อวันที่ $dd $name_month $t_yyyy)</FONT>";
		}
	}else{
		$stat_active="";
}
			$check_group_emp =check_group_emp($id_emp);
if($check_group_emp=='0'||$check_group_emp==''){$f_name=""; }
if($check_group_emp=='1'){$f_name="ภราดา"; }
if($check_group_emp=='2'){if ($sex =="f") {$f_name="นางสาว";if($marry=='y'){$f_name="นาง";}}else{$f_name="นาย";}}
if($check_group_emp=='3'){if ($sex =="f") {$f_name="มิส";}else{$f_name="มาสเตอร์";}}
if($check_group_emp=='4'){$f_name="คุณ";}
if($check_group_emp=='5'){$f_name=$fname;}
}
if($status>0){$f_name="ภราดา";}
$check_port_pic = check_port_pic($id_emp);
if($check_port_pic!=""){   $print_port_pic="<a href='/html_edu/cgi-bin/$database/main_php/print_portfolio_pic.php?id_emp=$id_emp' target='_blank'><img src='$icons/pic_fb.png' border='0' title='ดูภาพอื่นๆ'></a>";  }else{ $print_port_pic="";}
$select_pic_emp=select_pic_emp($id_emp);
$data_emp[name_emp]="$f_name &nbsp;$name &nbsp; &nbsp;$l_name$stat_active ";
$data_emp[pic]=$select_pic_emp[name_picture];
$data_emp[email]=$email;
$data_emp[pic_portfolio]= $print_port_pic;
return $data_emp;
}


###################################################
function check_port_pic($id_emp){
global $connect,$database;
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");

$sql = "SELECT name_pic FROM set_group_news,name_group_news WHERE id_emp='$id_emp' and
name_group_news.id_ngn = set_group_news.id_ngn and name_pic != '' "; 
$query =  mysql_query($sql);
$row = mysql_fetch_array($query);
$name_pic = $row[name_pic];
return $name_pic;
}
#################################################
function check_port_pic_student($id_student){
global $connect,$database;
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql = "SELECT name_pic FROM set_group_news,name_group_news WHERE set_group_news.id_student='$id_student' and
name_group_news.id_ngn = set_group_news.id_ngn and name_pic != '' "; 
$query =  mysql_query($sql);
$row = mysql_fetch_array($query);
$name_pic = $row[name_pic];
return $name_pic;
}
#################################################
function name_emp_e($id_emp){
global $connect,$database,$host,$user,$passwd;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select fname,name,l_name,sex,marry,status,active,end_day_emp,pic,email,e_name,e_l_name from emp_edu where id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$fname=$row["fname"];
	$name=$row["name"];
	$l_name=$row["l_name"];
	$sex=$row["sex"];
	$marry=$row["marry"];
	$pic=$row["pic"];
	$status=$row["status"];
	$active=$row["active"];
		$e_name=$row["e_name"];
			$e_l_name=$row["e_l_name"];
	$end_day_emp=$row["end_day_emp"];
	$email=$row["email"];
if ($active == "0") {
	$yyyymmdd=explode("-",$end_day_emp);
	$yyyy=$yyyymmdd[0];
	$mm=$yyyymmdd[1];
	$dd=$yyyymmdd[2];
	$t_yyyy=$yyyy+543;
	if ($yyyy == "0000") {
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(ลาออกโดยไม่ระบุวันที่)</FONT>";
		}else{
			$name_month =name_month($mm);
			$stat_active="<FONT COLOR='#FF0000'>&nbsp;(Out of Date $dd $name_month $t_yyyy)</FONT>";
		}
	}else{
		$stat_active="";
}
			$check_group_emp =check_group_emp($id_emp);
if($check_group_emp=='0'||$check_group_emp==''){$f_name=""; }
if($check_group_emp=='1'){$f_name="Bro"; }
if($check_group_emp=='2'){if ($sex =="f") {$f_name="Missis";if($marry=='y'){$f_name="Missis";}}else{$f_name="Master";}}
if($check_group_emp=='3'){if ($sex =="f") {$f_name="Miss";}else{$f_name="Master";}}
if($check_group_emp=='4'){$f_name="Mister";}
if($check_group_emp=='5'){$f_name=$fname;}
}
if($status>0){$f_name="Bro";}
$select_pic_emp=select_pic_emp($id_emp);
$data_emp[name_emp]="$f_name &nbsp;$e_name &nbsp; &nbsp;$e_l_name$stat_active";
$data_emp[pic]=$select_pic_emp[name_picture];
$data_emp[email]=$email;
return $data_emp;
}
#################################################

function name_student($id_student){
global $connect,$database,$icons;
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_student,name,l_name,sex,pic,birth_day,nickname from student_edu where id_student='$id_student'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
$id_student=$row["id_student"];
$name=$row["name"];
$l_name=$row["l_name"];
$sex=$row["sex"];
$pic=$row["pic"];
$birth_day=$row["birth_day"];
$nickname=$row["nickname"];
		$year=date(Y);
		$month=date(m);
		$day=date(d);
	$yyyymmdd=explode("-",$birth_day);
	$yyyy=$yyyymmdd[0];
	$mm=$yyyymmdd[1];
	$dd=$yyyymmdd[2];



$check_port_pic_student = check_port_pic_student($id_student);
if($check_port_pic_student!=""){   $print_port_pic="<a href='/html_edu/cgi-bin/$database/main_php/print_portfolio_pic_student.php?id_student=$id_student' target='_blank'><img src='$icons/pic_fb.png' border='0' title='ดูภาพอื่นๆ'></a>";  }else{ $print_port_pic="";}
if ($sex == "f") {
	$f_name="เด็กหญิง"; 
	if (($year-$yyyy) > 15){$f_name="นางสาว";}
	if (($year-$yyyy) == 15){if (( $month-$mm) > 0) {$f_name="นางสาว";}}
	if (($year-$yyyy) == 15){if (( $month-$mm) == 0) {if (( $day-$dd) >= 0) {$f_name="นางสาว";}}}
#	if($birth_day=="0000-00-00"){$f_name="";}
	$f_name="เด็กหญิง"; 
}
if ($sex == "m") {
	$f_name="เด็กชาย"; 
	if (($year-$yyyy) > 15){$f_name="นาย";}
	if (($year-$yyyy) == 15){if (( $month-$mm) > 0) {$f_name="นาย";}}
	if (($year-$yyyy) == 15){if (( $month-$mm) == 0) {if (($day-$dd) >= 0) {$f_name="นาย";}}}
	$f_name="เด็กชาย"; 
	}
#	if($birth_day=="0000-00-00"){$f_name="";}
}
$select_pic_student = select_pic_student($id_student);
$data_student[name]="$f_name &nbsp;$name &nbsp; &nbsp;$l_name";
$data_student[pic]=$select_pic_student[name_picture];
$data_student[pic_portfolio] = $print_port_pic;
$data_student[birth_day] = $birth_day;
$data_student[nickname] = $nickname;
return $data_student;
}
#################################################
function select_pic_emp($id_emp){
global $connect,$database,$host,$user,$passwd;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_picture,date_update from person_picture where id_emp='$id_emp' order by id_person_pic DESC limit 0,1";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$select_pic_emp[name_picture]=$row["name_picture"];
	$select_pic_emp[date_update]=$row["date_update"];
}
return $select_pic_emp;
}
#################################################
function select_pic_student($id_student){
global $connect,$database,$host,$user,$passwd;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_picture,date_update from person_picture where id_student='$id_student' order by id_person_pic DESC limit 0,1";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$select_pic_student[name_picture]=$row["name_picture"];
	$select_pic_student[date_update]=$row["date_update"];
}
return $select_pic_student;
}
#################################################

function name_person($id_person){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql="SELECT id_person,p_name,name,l_name,pic FROM person_edu WHERE id_person='$id_person' ";
$query=mysql_query($sql);
$row=mysql_fetch_array($query);
$id_person=$row[id_person];
$p_name=$row[p_name];
$name=$row[name];
$l_name=$row[l_name];
$pic=$row[pic];
$name_person[name]="$p_name $name  $l_name";
$name_person[pic]="$pic";

return $name_person;
}
#####################Joop 22/12/2547############################
function select_year($time_year){
	if ($time_year == "") {
		$time_year=date(Y);
		$time_month=date(m);
		if ($time_month < 5) {$time_year=$time_year-1;}
	}
	$year[0]=$time_year-5;
	$year[1]=$time_year-4;
	$year[2]=$time_year-3;
	$year[3]=$time_year-2;
	$year[4]=$time_year-1;
	$year[5]=$time_year;
	$year[6]=$time_year+1;
	$year[7]=$time_year+2;
	$year[8]=$time_year+3;
	$year[9]=$time_year+4;
	$year[10]=$time_year+5;
		for ($i=0;$i<count($year);$i++)
		{
			$t_list_year=$year[$i]+543;
			$select_for_year_1[$i]="<option value=$year[$i]>$t_list_year</option>";
			if ($year[$i] == $time_year) {
			$select_for_year_1[$i]="<option value=$year[$i] selected>$t_list_year</option>";
		}
		}
	$time_year_t=$time_year+543;
	$t_time_year=$time_year+543;
	$select_for_year = implode("\n", $select_for_year_1);
return ($select_for_year);

}
#################################################
function time_year($time_year){
	if ($time_year == "") {
		$time_year=date(Y);
		$time_month=date(m);
		if ($time_month < 5) {$time_year=$time_year-1;}
	}
return ($time_year);

}
#################################################
function print_name_side($id_side_login){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_side,connect_side,year_edu from edu_side where id_side ='$id_side_login'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$print_name_side[name_side]=$row["name_side"];
	$print_name_side[connect_side]=$row["connect_side"];
	$print_name_side[year_edu]=$row["year_edu"];
	$print_name_side[connect_last_side]=$row["connect_last_side"];
if ($connect_side !=0){$print_name_side1=print_name_side($print_name_side[connect_side]);$print_name_side[name_side].="/$print_name_side1[name_side]";}
}
return $print_name_side;
}
#################################################
function check_name_side($id_side_login){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_side,connect_side from edu_side where id_side ='$id_side_login'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$name_side=$row["name_side"];
	$connect_side=$row["connect_side"];
if ($connect_side !=0){$name_side1=check_name_side($connect_side);$name_side.="/$name_side1";}
}
return $name_side;
}
#################################################
function check_name_dept($id_dept){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_dept,for_side,year_edu,connect_dept from login_post where id_dept ='$id_dept'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$data_dept[name_dept]=$row["name_dept"];
	$data_dept[for_side]=$row["for_side"];
	$data_dept[year_edu]=$row["year_edu"];
	$data_dept[connect_dept]=$row["connect_dept"];
}
return $data_dept;
}
#################################################
function check_name_dept_e($id_dept){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_dept_e,for_side,year_edu,connect_dept from login_post where id_dept ='$id_dept'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$data_dept[name_dept_e]=$row["name_dept_e"];
	$data_dept[for_side]=$row["for_side"];
	$data_dept[year_edu]=$row["year_edu"];
	$data_dept[connect_dept]=$row["connect_dept"];
}
return $data_dept;
}
#################################################
function check_name_plot($id_plot){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_plot from plot_edu_dept where id_plot ='$id_plot'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$name_plot=$row["name_plot"];
}
return $name_plot;
}
#################################################
function check_name_plot_e($id_plot){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_plot_e from plot_edu_dept where id_plot ='$id_plot'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$name_plot_e=$row["name_plot_e"];
}
return $name_plot_e;
}
#################################################
function position_from_emp($id_emp,$time_year){
global $host,$user,$passwd,$database;
$time_year=time_year($time_year);
$connect= mysql_connect($host,$user,$passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_side,id_position from position_emp where id_position > '3'and id_emp='$id_emp' and id_side > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$id_side=$row["id_side"];
	$id_position=$row["id_position"];
$check_name_side=check_name_side($id_side);
$check_name_position=check_name_position($id_position);
$position_from_emp.="$check_name_position &nbsp; $check_name_side \n";
}
$sql_query="select id_dept,id_position from position_emp where id_position > '3'and id_emp='$id_emp' and id_dept > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$id_dept=$row["id_dept"];
	$id_position=$row["id_position"];
$check_name_dept=check_name_dept($id_dept);
$check_name_position=check_name_position($id_position);
$position_from_emp.="$check_name_position &nbsp; $check_name_dept \n";
}

return $position_from_emp;
}
#################################################
function check_name_position($id_position){
global $host,$user,$passwd,$database;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_position from position_edu where id_position='$id_position'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$check_name_position=$row["name_position"];
}
return $check_name_position;
}
##################################################################################################
function select_side_subject($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_position from position_emp where id_position='3'and id_emp='$id_emp' and id_side > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_id_position=mysql_num_rows($shows);
return $count_id_position;
}
#################################################
function select_side_more($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_position from position_emp where id_position>'3'and id_emp='$id_emp' and id_side > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_id_position_more=mysql_num_rows($shows);
return $count_id_position_more;
}
#################################################
function select_dept_subject($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_position from position_emp where id_position='3' and id_emp='$id_emp' and id_dept > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_dept_id_position=mysql_num_rows($shows);
return $count_dept_id_position;
}
#################################################
function select_dept_more($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_position from position_emp where id_position>'3'and id_emp='$id_emp' and id_dept > 0 and year = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_dept_id_position_more=mysql_num_rows($shows);
return $count_dept_id_position_more;
}
#################################################
function select_plot_edu_duty($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select DISTINCT(plot_edu_dept.id_plot),plot_edu_dept.name_plot,plot_edu_dept.id_dept from position_emp,plot_edu_dept where position_emp.id_position ='3' and position_emp.id_plot=plot_edu_dept.id_plot and position_emp.id_emp='$id_emp' and plot_edu_dept.year_edu='$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_plot_edu_duty=mysql_num_rows($shows);
return $count_plot_edu_duty;
}
#################################################
function select_plot_edu_duty_more($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select DISTINCT(plot_edu_dept.id_plot) from plot_edu_dept,position_emp where position_emp.head ='0'and position_emp.id_emp='$id_emp' and plot_edu_dept.id_plot=position_emp.id_plot and plot_edu_dept.year_edu = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_dept_id_position_more=mysql_num_rows($shows);
return $count_dept_id_position_more;
}
#################################################
function select_calendar_title($id_emp){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
$next_year=$time_year+1;
$select_this_year="$time_year-$month_begin_limit-$day_begin_limit";
$select_next_year="$next_year-$month_end_limit-$day_end_limit";
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_cal from calendar_title where id_emp='$id_emp' and begin_id_cal = 0 and timer between '$select_this_year' and '$select_next_year'";
$shows=mysql_query($sql_query,$connect);
$count_calendar_title=mysql_num_rows($shows);
	return $count_calendar_title;
}
#################################################
function name_profile($id_profile){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select profile_comment from edu_profile_dept where id_profile='$id_profile'  ";
$shows=mysql_query($sql_query,$connect);
$row=mysql_fetch_array($shows);
$name_profile = $row[profile_comment];
	return $name_profile;
}
################################################
function name_profile_e($id_profile){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select profile_comment_e from edu_profile_dept where id_profile='$id_profile'  ";
$shows=mysql_query($sql_query,$connect);
$row=mysql_fetch_array($shows);
$name_profile_e = $row[profile_comment_e];
	return $name_profile_e;
}
#################################################
function select_type_head_news(){
global $connect,$database,$select_news,$check_list_page,$time_year,$list_from;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select select_news,name_select_news from name_select_news order by select_news ASC";
$shows=mysql_query($sql_query,$connect);
$i=0;
while($row=mysql_fetch_array($shows)){
	$select_news_data=$row["select_news"];
	$name_select_news=$row["name_select_news"];
	if ($select_news == $select_news_data){$check_page_data=$check_list_page;$image="<IMG SRC='../../../../html_edu/$database/icons/$list_from.jpg' border='0'>";}else{$check_page_data='-1';$image="";}
$select_type_news[$i]="<TD align='center' rowspan='3' width='70'><A HREF='print_stat_emp_type_post_news.php?check_page=$check_page_data&time_year=$time_year&select_news=$select_news_data'>$image$name_select_news</A></TD>";
$i++;
}
$width_table=70*$i;
if ($select_type_news > 0){
$select_type_news[0] = implode("\n", $select_type_news);
}
$select_type_news[1] = $width_table;
return $select_type_news;
}
#################################################
function select_type_news($id_emp){
global $time_year,$connect,$database,$select_news;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select select_news from name_select_news order by select_news ASC";
$shows=mysql_query($sql_query,$connect);
$i=0;
while($row=mysql_fetch_array($shows)){
	$select_news_data=$row["select_news"];
if ($select_news_data==$select_news){next;}else{
	$count_id_news=count_id_news("$select_news_data","$id_emp");
	if (($i%2) == 1) {$bg_tr="bgcolor='#EFEFEF'";}else{$bg_tr="bgcolor='#DFDFDF'";}
				if ($count_id_news==0){
			$link_count_id_news[$select_news_data]="<TD align='center'><FONT>0</FONT></TD>";

		}else{
			$link_count_id_news[$select_news_data]="<TD align='center'><A HREF='print_emp_type_post_news.php?id_emp=$id_emp&time_year=$time_year&select_news=$select_news_data' target='_blank'>$count_id_news</A></TD>";
		}
#	print"\$link_count_id_news[$select_news_data] =$link_count_id_news[$select_news_data] <br>";
$i++;
}
}
return $link_count_id_news;
}
#################################################
function count_id_news($select_news,$id_emp){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
$next_year=$time_year+1;
$select_this_year="$time_year-$month_begin_limit-$day_begin_limit";
$select_next_year="$next_year-$month_end_limit-$day_end_limit";
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select select_type_news.id_news from select_type_news,news_edu,emp_news where select_type_news.id_select_news ='$select_news' and emp_news.id_news=news_edu.id_news and emp_news.id_emp='$id_emp' and news_edu.id_dept > 0 and news_edu.id_news= select_type_news.id_news and news_edu.day_news between '$select_this_year' and '$select_next_year'";
$shows=mysql_query($sql_query,$connect);
$count_id_news=mysql_num_rows($shows);
	return $count_id_news;
	}
#################################################
function select_subject_title_emp($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_st from subject_edu,subject_teacher where year='$time_year' and subject_teacher.id_se=subject_edu.id_se and id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
$count_subject_title_emp=mysql_num_rows($shows);
	return $count_subject_title_emp;
}
#################################################
function select_el_name_subject($id_emp){
global $connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_name_subject from el_name_subject where id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
$count_el_name_subject=mysql_num_rows($shows);
	return $count_el_name_subject;
}
#################################################
function select_emp_in_post_news($id_emp){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
$next_year=$time_year+1;
$select_this_year="$time_year-$month_begin_limit-$day_begin_limit";
$select_next_year="$next_year-$month_end_limit-$day_end_limit";
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select emp_news.id_news from news_edu,emp_news where emp_news.id_emp=$id_emp and emp_news.id_news=news_edu.id_news and news_edu.id_dept >0 and news_edu.day_news between '$select_this_year' and '$select_next_year'";
$shows=mysql_query($sql_query,$connect);
$count_emp_in_post_news=mysql_num_rows($shows);
	return $count_emp_in_post_news;
}
#################################################
function select_emp_post_news($id_emp){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
$next_year=$time_year+1;
$select_this_year="$time_year-$month_begin_limit-$day_begin_limit";
$select_next_year="$next_year-$month_end_limit-$day_end_limit";
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_news from news_edu where id_emp_post='$id_emp'and id_dept >0 and day_news between '$select_this_year' and '$select_next_year'";
$shows=mysql_query($sql_query,$connect);
$count_emp_post_news=mysql_num_rows($shows);
	return $count_emp_post_news;
}
#################################################
function select_name_group_news($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_ngn from name_group_news where year ='$time_year' and id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
$count_name_group_news=mysql_num_rows($shows);
	return $count_name_group_news;
}
#################################################
function select_emp_informed($id_emp){
global $time_year,$month_begin_limit,$day_begin_limit,$month_end_limit,$day_end_limit,$connect,$database;
$next_year=$time_year+1;
$select_this_year="$time_year-$month_begin_limit-$day_begin_limit";
$select_next_year="$next_year-$month_end_limit-$day_end_limit";
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_count_inform from informed where id_emp='$id_emp'and time_inform between '$select_this_year' and '$select_next_year'";
 
$shows=mysql_query($sql_query,$connect);
$count_informed=mysql_num_rows($shows);
	return $count_informed;
}
#################################################
function select_plot_edu_tracking($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select DISTINCT(plot_edu_tracking.id_plot) from position_emp,plot_edu_dept,plot_edu_tracking where  plot_edu_tracking.id_plot =plot_edu_dept.id_plot and position_emp.id_plot=plot_edu_dept.id_plot and position_emp.id_emp='$id_emp' and plot_edu_dept.year_edu='$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_plot_edu_tracking=mysql_num_rows($shows);
return $count_plot_edu_tracking;
}
#################################################
function select_plot_edu_non_tracking($id_emp){
global $time_year,$count_pplot_edu_tracking,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select DISTINCT(position_emp.id_plot) from position_emp,plot_edu_dept where position_emp.id_plot=plot_edu_dept.id_plot and position_emp.id_emp='$id_emp' and plot_edu_dept.year_edu='$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_plot_edu_total_tracking=mysql_num_rows($shows);
return $count_plot_edu_total_tracking;
}
#################################################
function select_plot_duty($id_emp){
global $time_year,$connect,$database;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select plot_edu_duty.id_duty from plot_edu_dept,plot_edu_duty,plot_edu_duty_human where plot_edu_duty_human.id_emp='$id_emp' and plot_edu_duty.id_duty=plot_edu_duty_human.id_duty and plot_edu_dept.id_plot=plot_edu_duty.id_plot and plot_edu_dept.year_edu = '$time_year'";
$shows=mysql_query($sql_query,$connect);
$count_plot_duty=mysql_num_rows($shows);
	return $count_plot_duty;
}
#################################################
function split_emp($file_link){
global $data_page,$next_data,$time_year,$check_page,$connect,$database,$select_news;
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_emp from emp_edu where active='1'";
$shows=mysql_query($sql_query,$connect);
$count_id_emp=mysql_num_rows($shows);
	$page_total_count_id_emp =$count_id_emp / $data_page;
	$dev=explode(".",$page_total_count_id_emp);
	$g=0;
	$h=0;
	for ($j=0;$j <= $dev[0];$j++) {
	$g++;
if ($next_data==$h) {$page[$j]="<FONT color='#FF0000'>$g</FONT>";$h= $h+$data_page;}else{
$page[$j]="<A HREF= $file_link?next_data=$h&time_year=$time_year&check_page=$check_page&select_news=$select_news>$g </A>";
	$h = $h+$data_page;
}
}
		$split_emp[0] = implode("\n", $page);
		$split_emp[1]=$count_id_emp;
return $split_emp;
}
#################################################
function name_month($name_month){
if($name_month=='01'){$name_month="มกราคม";}
if($name_month=='02'){$name_month="กุมภาพันธ์";}
if($name_month=='03'){$name_month="มีนาคม";}
if($name_month=='04'){$name_month="เมษายน";}
if($name_month=='05'){$name_month="พฤษภาคม";}
if($name_month=='06'){$name_month="มิถุนายน";}
if($name_month=='07'){$name_month="กรกฎาคม";}
if($name_month=='08'){$name_month="สิงหาคม";}
if($name_month=='09'){$name_month="กันยายน";}
if($name_month=='10'){$name_month="ตุลาคม";}
if($name_month=='11'){$name_month="พฤศจิกายน";}
if($name_month=='12'){$name_month="ธันวาคม";}
return $name_month;
}
##################################################
function name_month_e($name_month){
if($name_month=='01'){$name_month="Jan";}
if($name_month=='02'){$name_month="Feb";}
if($name_month=='03'){$name_month="Mar";}
if($name_month=='04'){$name_month="April";}
if($name_month=='05'){$name_month="May";}
if($name_month=='06'){$name_month="June";}
if($name_month=='07'){$name_month="July";}
if($name_month=='08'){$name_month="Aug";}
if($name_month=='09'){$name_month="Sep";}
if($name_month=='10'){$name_month="Oct";}
if($name_month=='11'){$name_month="Nov";}
if($name_month=='12'){$name_month="Dec";}
return $name_month;
}
#################################################
function namemonth($name_month){
if($name_month=='1'){$name_month="มกราคม";}
if($name_month=='2'){$name_month="กุมภาพันธ์";}
if($name_month=='3'){$name_month="มีนาคม";}
if($name_month=='4'){$name_month="เมษายน";}
if($name_month=='5'){$name_month="พฤษภาคม";}
if($name_month=='6'){$name_month="มิถุนายน";}
if($name_month=='7'){$name_month="กรกฎาคม";}
if($name_month=='8'){$name_month="สิงหาคม";}
if($name_month=='9'){$name_month="กันยายน";}
if($name_month=='10'){$name_month="ตุลาคม";}
if($name_month=='11'){$name_month="พฤศจิกายน";}
if($name_month=='12'){$name_month="ธันวาคม";}
return $name_month;
}
#################################################
function select_month($m){

if($m==""){$m=date('m');}

	for ($i=1;$i<= 12;$i++) {

if($i=='1'){$name_month="มกราคม";}
if($i=='2'){$name_month="กุมภาพันธ์";}
if($i=='3'){$name_month="มีนาคม";}
if($i=='4'){$name_month="เมษายน";}
if($i=='5'){$name_month="พฤษภาคม";}
if($i=='6'){$name_month="มิถุนายน";}
if($i=='7'){$name_month="กรกฎาคม";}
if($i=='8'){$name_month="สิงหาคม";}
if($i=='9'){$name_month="กันยายน";}
if($i=='10'){$name_month="ตุลาคม";}
if($i=='11'){$name_month="พฤศจิกายน";}
if($i=='12'){$name_month="ธันวาคม";}

if($i==$m){$print[$a]="<option value='$i' selected>$name_month</option> ";}
else{
$print[$a]="<option value='$i'>$name_month</option> ";
}
$a++;
	}
$print_name_month=implode("\n",$print);



return $print_name_month;
}

#################################################

function name_m($name_month){
	$name_m="";
if($name_month=='01'){$name_m="ม.ค.";}
if($name_month=='02'){$name_m="ก.พ.";}
if($name_month=='03'){$name_m="มี.ค.";}
if($name_month=='04'){$name_m="เม.ย.";}
if($name_month=='05'){$name_m="พ.ค.";}
if($name_month=='06'){$name_m="มิ.ย.";}
if($name_month=='07'){$name_m="ก.ค.";}
if($name_month=='08'){$name_m="ส.ค.";}
if($name_month=='09'){$name_m="ก.ย.";}
if($name_month=='10'){$name_m="ต.ค.";}
if($name_month=='11'){$name_m="พ.ย.";}
if($name_month=='12'){$name_m="ธ.ค.";}
return $name_m;
}
#################################################
function name_day($name_day){
if($name_day=='0'){$name_day="อาทิตย์";}
if($name_day=='1'){$name_day="จันทร์";}
if($name_day=='2'){$name_day="อังคาร";}
if($name_day=='3'){$name_day="พุธ";}
if($name_day=='4'){$name_day="พฤหัสฯ";}
if($name_day=='5'){$name_day="ศุกร์";}
if($name_day=='6'){$name_day="เสาร์";}
return $name_day;
}
#################################################
function name_day_e($name_day){
if($name_day=='0'){$name_day="Sun";}
if($name_day=='1'){$name_day="Mon";}
if($name_day=='2'){$name_day="Tue";}
if($name_day=='3'){$name_day="Wed";}
if($name_day=='4'){$name_day="Thu";}
if($name_day=='5'){$name_day="Fri";}
if($name_day=='6'){$name_day="Sat";}
return $name_day;
}
#################################################
function text_id_tas($id_tas){
global $host,$user,$passwd,$database;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database 4");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="SELECT name_tas FROM type_action_student where id_tas='$id_tas'";
$shows=mysql_query($sql_query,$connect);
	while($row=mysql_fetch_array($shows)){
	$text_id_tas=$row["name_tas"];
	}
return $text_id_tas;
}
#################################################
#################################################
function logout(){
global $host,$user,$passwd,$head_html,$www_home;
session_destroy();
	print "
$head_html
<TABLE cellPadding=0 width='100%' align=center border=0>
<TR>
		<TD Valign='top'><!-- center -->
		<TABLE borderColor='#6699FF' cellPadding=0 width='100%' align=center border=5>
		<TBODY>
			<TR>
				<TD>
					<TABLE cellSpacing=0 cellPadding=3 width='100%' align=center border=0>
						<TBODY>
							<TR>
								<TD align=left>&nbsp;</TD>
								<TD noWrap align=left>
									<TABLE cellSpacing=0 cellPadding=0 width='94%' border=0>
										<TBODY>
											<TR>
												<TD vAlign=center noWrap align=center></TD>
											</TR>
										</TBODY>
									</TABLE>
								</TD>
							</TR>
							<TR>
								<TD vAlign=top></TD>
								<TD vAlign=top width='100%' Align=center height='300'><BR><BR><BR><BR><BR><BR>
<FONT COLOR='#FF0000'>ได้ออกจากระบบเรียบร้อยแล้ว</FONT>
<BR><BR><BR><FORM METHOD=POST ACTION='$www_home'>
<input type='Submit' value='หน้าแรก' style='color:blue;background:lightskyblue;font-family:verdana;font-size:12px;border-right: #0099ff 1px solid; border-top: #0099ff 1px solid; border-left: #0099ff 1px solid; border-bottom: #0099ff 1px solid'>
</FORM>
</TD>
							</TR>
							<TR>
								<TD align=left>&nbsp;</TD>
								<TD noWrap align=left>
									<TABLE cellSpacing=0 cellPadding=0 width='94%' border=0>
										<TBODY>
											<TR>
												<TD vAlign=center noWrap align=center></TD>
											</TR>
										</TBODY>
									</TABLE>
								</TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
</TD>
		</TR>
	</TABLE></TD>
</TR>
</TABLE>
<BR>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>
";
exit;
}
#################################################
function name_sara($sara){
if($sara=='0'){$name_sara="ไม่จัดอยู่ในกลุ่มสาระฯ";}
if($sara=='1'){$name_sara="สังคมศึกษาศาสนาและวัฒนธรรม";}
if($sara=='2'){$name_sara="การงานอาชีพและเทคโนโลยี";}
if($sara=='3'){$name_sara="สุขศึกษาและพลศึกษา";}
if($sara=='4'){$name_sara="ภาษาต่างประเทศ";}
if($sara=='5'){$name_sara="วิทยาศาสตร์";}
if($sara=='6'){$name_sara="คณิตศาสตร์";}
if($sara=='7'){$name_sara="ภาษาไทย";}
if($sara=='8'){$name_sara="ศิลปะ";}
if($sara=='9'){$name_sara="กิจกรรมพัฒนาผู้เรียน";}
return $name_sara;
}
#################################################
function name_class_room($id_class_room){
global $host,$user,$passwd,$database;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="SELECT name_class,room FROM name_class_room,name_class where name_class_room.class=name_class.class and id_class_room='$id_class_room'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$name_class=$row["name_class"];
	$room=$row["room"];
}
$name_class_room="$name_class/$room";
return $name_class_room;
}
#################################################
function name_class($id_class){
global $host,$user,$passwd,$database;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="SELECT name_class FROM name_class where class='$id_class'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$name_class=$row["name_class"];
}
return $name_class;
}
#################################################
function swis_version($id_sv){
global $host,$user,$passwd,$database;
$connect= mysql_connect($host,$user,$passwd) or die("not connect database 4");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select name_function,version_function,update_function from swis_version where id_sv='$id_sv'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
$name_function=$row["name_function"];
$version_function=$row["version_function"];
$update_function=$row["update_function"];
}
$swis_version="$name_function : $version_function";
return $swis_version;
}
#################################################
function check_emp_edit($id_emp,$id_menu){
global $host,$user,$passwd,$database;
$check_emp_edit=0;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select id_fwe from fix_work_emp where id_emp ='$id_emp' and type_selection='$id_menu'";
$shows=mysql_query($sql_query,$connect);
	while($row=mysql_fetch_array($shows)){
	$check_emp_edit=$row["id_fwe"];
	}
return $check_emp_edit;
}
 ####################error_login######################
print "
<style>
.addButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	-webkit-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52));
	background:-moz-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
	background:-webkit-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
	background:-o-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
	background:-ms-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
	background:linear-gradient(to bottom, #b8e356 5%, #a5cc52 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52',GradientType=0);
	background-color:#b8e356;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #83c41a;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:1px 1px;
	text-decoration:none;
	text-shadow:0px 1px 0px #86ae47;
}
.addButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a5cc52), color-stop(1, #b8e356));
	background:-moz-linear-gradient(top, #a5cc52 5%, #b8e356 100%);
	background:-webkit-linear-gradient(top, #a5cc52 5%, #b8e356 100%);
	background:-o-linear-gradient(top, #a5cc52 5%, #b8e356 100%);
	background:-ms-linear-gradient(top, #a5cc52 5%, #b8e356 100%);
	background:linear-gradient(to bottom, #a5cc52 5%, #b8e356 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a5cc52', endColorstr='#b8e356',GradientType=0);
	background-color:#a5cc52;
}
.addButton:active {
	position:relative;
	top:1px;
}




.editButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #fce2c1;
	-webkit-box-shadow:inset 0px 1px 0px 0px #fce2c1;
	box-shadow:inset 0px 1px 0px 0px #fce2c1;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffc477), color-stop(1, #fb9e25));
	background:-moz-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
	background:-webkit-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
	background:-o-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
	background:-ms-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
	background:linear-gradient(to bottom, #ffc477 5%, #fb9e25 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffc477', endColorstr='#fb9e25',GradientType=0);
	background-color:#ffc477;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #eeb44f;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	padding:1px 1px;
	text-decoration:none;
	text-shadow:0px 1px 0px #cc9f52;
}
.editButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #fb9e25), color-stop(1, #ffc477));
	background:-moz-linear-gradient(top, #fb9e25 5%, #ffc477 100%);
	background:-webkit-linear-gradient(top, #fb9e25 5%, #ffc477 100%);
	background:-o-linear-gradient(top, #fb9e25 5%, #ffc477 100%);
	background:-ms-linear-gradient(top, #fb9e25 5%, #ffc477 100%);
	background:linear-gradient(to bottom, #fb9e25 5%, #ffc477 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fb9e25', endColorstr='#ffc477',GradientType=0);
	background-color:#fb9e25;
}
.editButton:active {
	position:relative;
	top:1px;
}



.delButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #f5978e;
	-webkit-box-shadow:inset 0px 1px 0px 0px #f5978e;
	box-shadow:inset 0px 1px 0px 0px #f5978e;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f24537), color-stop(1, #c62d1f));
	background:-moz-linear-gradient(top, #f24537 5%, #c62d1f 100%);
	background:-webkit-linear-gradient(top, #f24537 5%, #c62d1f 100%);
	background:-o-linear-gradient(top, #f24537 5%, #c62d1f 100%);
	background:-ms-linear-gradient(top, #f24537 5%, #c62d1f 100%);
	background:linear-gradient(to bottom, #f24537 5%, #c62d1f 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f24537', endColorstr='#c62d1f',GradientType=0);
	background-color:#f24537;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #d02718;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	padding:1px 1px;
	text-decoration:none;
	text-shadow:0px 1px 0px #810e05;
}
.delButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c62d1f), color-stop(1, #f24537));
	background:-moz-linear-gradient(top, #c62d1f 5%, #f24537 100%);
	background:-webkit-linear-gradient(top, #c62d1f 5%, #f24537 100%);
	background:-o-linear-gradient(top, #c62d1f 5%, #f24537 100%);
	background:-ms-linear-gradient(top, #c62d1f 5%, #f24537 100%);
	background:linear-gradient(to bottom, #c62d1f 5%, #f24537 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c62d1f', endColorstr='#f24537',GradientType=0);
	background-color:#c62d1f;
}
.delButton:active {
	position:relative;
	top:1px;
}



.blueButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
	-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
	box-shadow:inset 0px 1px 0px 0px #97c4fe;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0));
	background:-moz-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
	background:-webkit-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
	background:-o-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
	background:-ms-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
	background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3d94f6', endColorstr='#1e62d0',GradientType=0);
	background-color:#3d94f6;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #337fed;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:1px 1px;
	text-decoration:none;
	text-shadow:0px 1px 0px #1570cd;
}
.blueButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #1e62d0), color-stop(1, #3d94f6));
	background:-moz-linear-gradient(top, #1e62d0 5%, #3d94f6 100%);
	background:-webkit-linear-gradient(top, #1e62d0 5%, #3d94f6 100%);
	background:-o-linear-gradient(top, #1e62d0 5%, #3d94f6 100%);
	background:-ms-linear-gradient(top, #1e62d0 5%, #3d94f6 100%);
	background:linear-gradient(to bottom, #1e62d0 5%, #3d94f6 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e62d0', endColorstr='#3d94f6',GradientType=0);
	background-color:#1e62d0;
}
.blueButton:active {
	position:relative;
	top:1px;
}

.img { solid #fff;  box-shadow: 5px 5px 5px #ccc; -moz-box-shadow: 5px 5px 5px #ccc; -webkit-box-shadow: 5px 5px 5px #ccc; -khtml-box-shadow: 5px 5px 5px #ccc; } 

</style>

";
 ####################error_login######################
# $url=$_SERVER['REQUEST_URI'];

$error_login="
<html>
<head>
<title>โปรด Login เข้าระบบ ก่อน</title>
</head>
<body>
$head_html
<CENTER><br>
<FONT SIZE=3><B>หน้านี้ไม่สามารถเข้าได้ โปรด Login เข้าระบบ ก่อน </B></FONT><BR><BR>
<TABLE width='50%' cellspacing='1' cellpadding='0' bgcolor='#000000'>
<TR bgcolor='#D6D6D6'>
	<TD align='center' width='100%'><CENTER><BR><BR>
<form name='addgroup' target='_parent' method='POST' action='../main_php/login_page.php?url2=$url'>
<FONT SIZE=2><B>เข้าสู่ระบบ</B></FONT>
<table width='300'><tr><td align='right'>
<FONT SIZE=2>User:</FONT></td><td><input type='text' name='login_tb' ></td></tr>
<tr><td align='right'>
<FONT SIZE=2> Passwd:</FONT></td><td><input type='password'   name='passwd_tb' ></td></tr>
<tr><td colspan='2' align='center'>
<input type='submit'  value='Submit'  name='login_page'>
</td></tr></table>
</form></CENTER></TD>
</TR>
</TABLE>
</CENTER>
</body>
</html>
";
#################################################
function name_emp2($id_emp){
global $connect,$database,$host,$user,$passwd,$icons;
$connect= mysql_connect($host , $user , $passwd) or die("not connect database");
	mysql_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
$sql_query="select f_name,name,l_name,sex,marry_status,status,active,end_day_emp,pic,email from emp_edu where id_emp='$id_emp'";
$shows=mysql_query($sql_query,$connect);
while($row=mysql_fetch_array($shows)){
	$f_name=$row["f_name"];
	$name=$row["name"];
	$l_name=$row["l_name"];
if($f_name=="1"){$fname_txt="นาย";}
if($f_name=="2"){$fname_txt="นาง";}
if($f_name=="3"){$fname_txt="นางสาว";}
if($f_name=="4"){$fname_txt="Mr";}
if($f_name=="5"){$fname_txt="Miss";}
}
$data_emp[name_emp]="$fname_txt  $name $l_name";
return $data_emp;
}

##############################################
?>
