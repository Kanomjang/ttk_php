
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="style.css" />
<link type="text/css" rel="stylesheet" href="../lib/colorbox/colorbox.css" />
<link rel="stylesheet" type="text/css" href="script/ClickShowHideMenu.css">
<script type="text/javascript" src="../lib/hoverIntent.js"></script> 
<script type="text/javascript" src="../lib/jquery.bgiframe.js"></script>
<script type="text/javascript" src="../lib/colorbox/jquery.colorbox.js"></script>
<script type="text/javascript" src="script/ClickShowHideMenu.js"></script>
<script language=javascript src="script/util.js"></script>
<script type="text/javascript"> 
		MainWindowChangeUrl = function(nUrl) { window.location= nUrl; }; // สร้าง function ให้ iframe เรียกใช้สำหรับเปลี่ยน url
		MainFrameChangeUrl = function(nUrl) { 		
			$('#main_frame').hide();
			$('#main_div').show();
			document.getElementById("main_frame").src = nUrl; 
		}
	function load_page(nUrl) {
		MainFrameChangeUrl(nUrl);
	}

    $(document).ready(function(){ 
		$('#main_frame').load(function(){ // เมื่อ iframe load เสร็จ
				$("#main_div").hide();
		});		
    }); 
	
function show_dialog(d_w,d_h,d_url,d_title) {		
	function getAppPath() {
		var pathArray = location.pathname.split('/');
		var appPath = "/";
		for(var i=1; i<pathArray.length-1; i++) {
			appPath += pathArray[i] + "/";
		}
		return appPath;
	}
	function getScreenHeight() {
		var screenH = 480;
		if (parseInt(navigator.appVersion)>3) { screenH = screen.height;}
		else if (navigator.appName == "Netscape" && parseInt(navigator.appVersion)==3 && navigator.javaEnabled()) {
		 var jToolkit = java.awt.Toolkit.getDefaultToolkit();
		 var jScreenSize = jToolkit.getScreenSize();
		 screenH = jScreenSize.height;
		}	
		return screenH;
	}
	
	var url = getAppPath()+d_url;
	if (d_h.indexOf("%") != -1) { 
		d_h = d_h.replace(/%/,""); 
		d_h = (getScreenHeight() * d_h / 100) + "px";
	}
	parent.$.colorbox({width:d_w, height:d_h, href:url, title:d_title,opacity:0.7,overlayClose:false,iframe:true,scrolling:true});		
}	

function  dialog_return(rsl){
	$.colorbox.close();
	if (rsl != "") {
		$("#main_frame").contents().dialog_return_value();
	}
}

</script>
<style type="text/css">
body {
	background-color: #ebebeb;
}
</style>
</head>	
<body>
<?php 	
//	echo $head_html;
?>	
<br>    
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="frame0">
<thead>
	<tr>
	<th>รายงานสารสนเทศ ปีการศึกษา <?php echo $edu_year; ?> </th>
	</tr>
</thead>	
</table>
<br>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="frame1">
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr>
            <td width="200" align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="frame2">
			<thead>
              <tr>
                <th align="center" valign="middle" bgcolor="#84c9e6" class="sub_title_text"><?php echo $mis_tile; ?></th>
              </tr>
			 </thead>
              <tr>
                <td>
                    <?php include($main_menu); ?>
                </td>
              </tr>
            </table>
            </td>
            <td width="9" align="center" valign="top">&nbsp;</td>
          <td align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="frame3">
            <tr>
              <td align="center" valign="top"><?php 
				if ($page != "") {
					echo "<div id='main_div' name='main_div' style=' height:500px; width:300px;' ><br><br><br><img src='image/wait1.gif'></div>";
					echo "<iframe name='main_frame' id='main_frame' src='$page' width='100%' height='0' frameborder='0'  scrolling='no'></iframe>";			
				}
				?></td>
            </tr>
          </table></td>
          <td width="13" align="center" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        </table></td>
  </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
</table>
    <p>&nbsp;</p>
    <table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="frame1">
		<thead>
		<tr>
			<th><table width="100%"  border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="52%" align="left" valign="top" class="sub_title_text"><font color="#003300">ปีการศึกษา :&nbsp;&nbsp;</font>
				<a href="index.php?frst=y&yr=2554&sty=<?php echo $mis_style; ?>" class="link_style" <?php if ($edu_year=="2554") { echo 'style="color:#000000;"'; }?>> 2554 </a>::
				<a href="index.php?frst=y&yr=2555&sty=<?php echo $mis_style; ?>" class="link_style" <?php if ($edu_year=="2555") { echo 'style="color:#000000;"'; }?>> 2555 </a></td>
                <td width="48%" align="right" valign="top" class="sub_title_text"><font color="#003300">รูปแบบ :&nbsp;&nbsp;</font>
				<a href="index.php?frst=y&sty=1&yr=<?php echo $edu_year; ?>" class="link_style" <?php if ($mis_style=="1") { echo 'style="color:#000000;"'; }?>> สารสนเทศ </a>::
				<a href="index.php?frst=y&sty=2&yr=<?php echo $edu_year; ?>" class="link_style" <?php if ($mis_style=="2") { echo 'style="color:#000000;"'; }?>> สมศ. </a>::
				<a href="index.php?frst=y&sty=3&yr=<?php echo $edu_year; ?>" class="link_style" <?php if ($mis_style=="3") { echo 'style="color:#000000;"'; }?>> FSG </a>::
				<a href="index.php?frst=y&sty=4&yr=<?php echo $edu_year; ?>" class="link_style" <?php if ($mis_style=="4") { echo 'style="color:#000000;"'; }?>> SAR </a></td>		
              </tr>
            </table></th>
		</tr>
		</thead>
    </table>
    <p>&nbsp;    </p>
    <p><br>
      <script type="text/javascript">
    var clickMenu1 = new ClickShowHideMenu('click-menu1');
    clickMenu1.init();
      </script>
</p>
</body>
</html>
