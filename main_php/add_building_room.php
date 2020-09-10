<?php
include("../lib_org.php");
//$time_year = $_GET['time_year'];
#$time_year=time_year($time_year);
#$select_year=select_year($time_year);
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='2';
if ($_GET['add_building_room']) {
  $id_loc = $_GET['add_building_room'];
  $id_loc = base64_decode(base64_decode("$id_loc"));
  $select_location_group = select_location_group($id_loc);
  add_building_room($select_location_group,$position_active,$id_loc);
}
if ($_GET['edit_building_room']) {
  $id_loc = $_GET['edit_building_room'];
  $id_loc_master = $_GET['id_loc_master'];
  $id_loc = base64_decode(base64_decode("$id_loc"));
  $id_loc_master = base64_decode(base64_decode("$id_loc_master"));
  $select_building_location = select_building_location($id_loc);
  $select_building_location_master = select_building_location_master($id_loc_master);
  edit_building_room($select_building_location, $id_emp_use, $id_loc,$id_loc_master,$select_building_location_master,$position_active);
}
if ($_GET['delete_building_room']) {
  $id_loc = $_GET['delete_building_room'];
  $id_loc = base64_decode(base64_decode("$id_loc"));
  $id_loc_master = $_GET['id_loc_master'];
  $id_loc_master = base64_decode(base64_decode("$id_loc_master"));
  $select_building_location = select_building_location($id_loc);
  $select_building_location_master = select_building_location_master($id_loc_master);
  delete_building_room($select_building_location,$select_building_location_master,$id_loc_master);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}
#################################################
function select_building_location($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_location,floor_location from location_group where id_loc='$id_loc'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_building_location['name_location'] = $row["name_location"];
    $select_building_location['floor_location'] = $row["floor_location"];
  }
  return $select_building_location;
}
#################################################
function select_building_location_master($id_loc_master)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_location_master,floor_master from location_master where id_loc_master='$id_loc_master'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_building_location_master['name_location_master'] = $row["name_location_master"];
    $select_building_location_master['floor_master'] = $row["floor_master"];
  }
  return $select_building_location_master;
}
#################################################
function select_name_dept()
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select id_dept,name_dept from dept_edu order by  id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    $print[$i] = "<option value='$id_dept'>$name_dept</option>";
    $i++;
  }
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_name_dept  = implode("\n", $print);
  return $select_name_dept;
}
#################################################
function select_location_group($id_loc)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select name_location,floor_location from location_group where id_loc='$id_loc'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_location_group['name_location'] = $row["name_location"];
    $select_location_group['floor_location'] = $row["floor_location"];
  }
  return $select_location_group;
}
##############################
function select_gdept($id_loc)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_dept,name_dept from dept_edu order by id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    if ($id_loc == $id_dept) {
      $print[$i] = "<option value='$id_dept' selected>$name_dept</option>";
    } else {
      $print[$i] = "<option value='$id_dept'>$name_dept</option>";
    }
    $i++;
  }
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_gdept['selecte']  = implode("\n", $print);
  return $select_gdept;
}
##############################
function print_asset_group()
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_asset_group,id_loc,code_asset FROM asset_group ORDER BY id_loc ASC";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $name_asset_group = $row["name_asset_group"];
    $id_loc = $row["id_loc"];
    $code_asset = $row["code_asset"];
    $print[$i] = "<option value='$id_loc'>$name_asset_group</option>";
    $i++;
  }
  $print_asset_group  = implode("\n", $print);
  return $print_asset_group;
}
##############################
function print_location_master()
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_location_master,id_loc_master FROM location_master ORDER BY id_loc_master ASC";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $name_location_master = $row["name_location_master"];
    $id_loc_master = $row["id_loc_master"];
    $print[$i] = "<option value='$id_loc_master'>$name_location_master</option>";
    $i++;
  }
  $list_group_item  = implode("\n", $print);
  return $list_group_item;
}
##############################
function print_edit_building_room($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.code_asset='$code_asset' and asset_type.id_loc=asset_group.id_loc";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_building_room = $row["name_asset_group"];
  }
  return $print_edit_building_room;
}
##############################
function print_edit_asset_type($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT usage_life_type FROM asset_type where code_asset='$code_asset'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_type = $row["usage_life_type"];
  }
  return $print_edit_asset_type;
}

#################################################
function edit_building_room($select_building_location, $id_emp_use, $id_loc,$id_loc_master,$select_building_location_master,$position_active)
{
  $encode_id_loc_master = base64_encode(base64_encode("$id_loc_master"));
  $encode_id_loc = base64_encode(base64_encode("$id_loc"));
  	$floor_location="";
	for($i=1;$i<=$select_building_location['floor_location'];$i++){
		if($select_building_location_master['floor_master']==$select_building_location['floor_location']){$floor_location.="<option value='$i' selected>$i</option>";
		}else{$floor_location.="<option value='$i'>$i</option>";}
	}

print"<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>AdminLTE 2 | Advanced form elements</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
  <!-- daterange picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-daterangepicker/daterangepicker.css\">
  <!-- bootstrap datepicker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css\">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel=\"stylesheet\" href=\"../plugins/iCheck/all.css\">
  <!-- Bootstrap Color Picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css\">
  <!-- Bootstrap time Picker -->
  <link rel=\"stylesheet\" href=\"../plugins/timepicker/bootstrap-timepicker.min.css\">
  <!-- Select2 -->
  <link rel=\"stylesheet\" href=\"../bower_components/select2/dist/css/select2.min.css\">
  <!-- Theme style -->
  <link rel=\"stylesheet\" href=\"../dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"../dist/css/skins/_all-skins.min.css\">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
  <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel=\"stylesheet\"
        href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\">
</head>
<body class=\"hold-transition skin-blue sidebar-mini\">
";
  include("../header.php");
  include("../sidebar.php");
  print"

 <div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
        แก้ไขห้องในอาคาร/สถานที่
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"building_location.php\">จัดการอาคาร/สถานที่</a></li>
        <li class=\"active\"><a href=\"select_building_room.php?select_building_room=$encode_id_loc\">จัดการห้อง</a></li>
        <li class=\"active\">แก้ไขห้อง</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <!-- /.row -->
    <!-- Main content -->
    <section class=\"content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <div class=\"box\">
            <div class=\"box-header\">
              <h3 class=\"box-title\">แก้ไขห้องในอาคาร/สถานที่</h3> 
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='select_building_room.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                         </div>
                        <div class=\"col-md-10\">
                    <div class=\"box-body\">
                      <!-- //ห้องในอาคาร/สถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชื่อห้องในอาคาร/สถานที่ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_location_master' id='name_location_master' value=\"$select_building_location_master[name_location_master]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //จำนวนชั้น -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชั้น :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='floor_master' id='floor_master'>
                              <option value='0'>เลือก</option>
                              $floor_location
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                </div><!-- End Row-->
                <div class=\"row\">
                  <div class=\"col-md-4\">
                  </div>
                  <div class=\"col-md-8\">
                    <input type='hidden' name='id_loc' value='$encode_id_loc'>
                    <input type='hidden' name='id_loc_master' value='$encode_id_loc_master'>
                    <input type=\"submit\" VALUE='Submit' name='save_location_master' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
                  </div>
                         </div>

                </div><!-- End Row-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  </form>
  </div>

<!-- jQuery 3 -->
<script src=\"../bower_components/jquery/dist/jquery.min.js\"></script>
<!-- Bootstrap 3.3.7 -->
<script src=\"../bower_components/bootstrap/dist/js/bootstrap.min.js\"></script>
<!-- Select2 -->
<script src=\"../bower_components/select2/dist/js/select2.full.min.js\"></script>
<!-- InputMask -->
<script src=\"../plugins/input-mask/jquery.inputmask.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.date.extensions.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.extensions.js\"></script>
<!-- date-range-picker -->
<script src=\"../bower_components/moment/min/moment.min.js\"></script>
<script src=\"../bower_components/bootstrap-daterangepicker/daterangepicker.js\"></script>
<!-- bootstrap datepicker -->
<script src=\"../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\"></script>
<!-- bootstrap color picker -->
<script src=\"../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js\"></script>
<!-- bootstrap time picker -->
<script src=\"../plugins/timepicker/bootstrap-timepicker.min.js\"></script>
<!-- SlimScroll -->
<script src=\"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\"></script>
<!-- iCheck 1.0.1 -->
<script src=\"../plugins/iCheck/icheck.min.js\"></script>
<!-- FastClick -->
<script src=\"../bower_components/fastclick/lib/fastclick.js\"></script>
<!-- AdminLTE App -->
<script src=\"../dist/js/adminlte.min.js\"></script>
<!-- AdminLTE for demo purposes -->
<script src=\"../dist/js/demo.js\"></script>
<!-- Page script -->
";
  include("../footer.php");
  exit;
}
######################หน้าแสดง###########################
function add_building_room($select_location_group,$position_active,$id_loc)
{
	$encode_id_loc = base64_encode(base64_encode("$id_loc"));
	$floor_location="";
	for($i=1;$i<=$select_location_group['floor_location'];$i++){
		$floor_location.="<option value='$i'>$i</option>";
	}
  print"
<!DOCTYPE html>
<html>
<head>
  <meta charset=\"utf-8\">
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
  <title>AdminLTE 2 | Advanced form elements</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
  <!-- daterange picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-daterangepicker/daterangepicker.css\">
  <!-- bootstrap datepicker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css\">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel=\"stylesheet\" href=\"../plugins/iCheck/all.css\">
  <!-- Bootstrap Color Picker -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css\">
  <!-- Bootstrap time Picker -->
  <link rel=\"stylesheet\" href=\"../plugins/timepicker/bootstrap-timepicker.min.css\">
  <!-- Select2 -->
  <link rel=\"stylesheet\" href=\"../bower_components/select2/dist/css/select2.min.css\">
  <!-- Theme style -->
  <link rel=\"stylesheet\" href=\"../dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"../dist/css/skins/_all-skins.min.css\">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
  <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel=\"stylesheet\"
        href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\">
</head>
<body class=\"hold-transition skin-blue sidebar-mini\">
";
  include("../header.php");
  include("../sidebar.php");
  print"

 <div class=\"wrapper\">
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
        เพิ่มอาคาร/สถานที่
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"building_location.php\">จัดการอาคาร/สถานที่</a></li>
        <li class=\"active\">เพิ่มอาคาร/สถานที่</li>
      </ol>
    </section>
    <!-- Info boxes -->
    <!-- /.row -->
    <!-- Main content -->
    <section class=\"content\">
      <div class=\"row\">
        <div class=\"col-xs-12\">
          <div class=\"box\">
            <div class=\"box-header\">
              <h3 class=\"box-title\">อาคาร/สถานที่</h3> &nbsp;&nbsp;&nbsp; $select_location_group[name_location]
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='select_building_room.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                         </div>
                        <div class=\"col-md-10\">

                    <div class=\"box-body\">
                      <!-- //ชื่อห้องในอาคาร/สถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชื่อห้องในอาคาร/สถานที่ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_location_master' id='name_location_master' placeholder=\"ชื่อห้องในอาคาร/สถานที่\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //จำนวนชั้น -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชั้น :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='floor_master' id='floor_master'>
                              <option value='0'>เลือก</option>
                              $floor_location
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                </div><!-- End Row-->
                <div class=\"row\">
                  <div class=\"col-md-4\">
                  </div>
                  <div class=\"col-md-8\">
                    <input type='hidden' name='id_loc' value='$encode_id_loc'>
                    <input type=\"submit\" VALUE='Submit' name='add_building_room' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
                  </div>
                         </div>

                </div><!-- End Row-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  </form>
  </div>

<!-- jQuery 3 -->
<script src=\"../bower_components/jquery/dist/jquery.min.js\"></script>
<!-- Bootstrap 3.3.7 -->
<script src=\"../bower_components/bootstrap/dist/js/bootstrap.min.js\"></script>
<!-- Select2 -->
<script src=\"../bower_components/select2/dist/js/select2.full.min.js\"></script>
<!-- InputMask -->
<script src=\"../plugins/input-mask/jquery.inputmask.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.date.extensions.js\"></script>
<script src=\"../plugins/input-mask/jquery.inputmask.extensions.js\"></script>
<!-- date-range-picker -->
<script src=\"../bower_components/moment/min/moment.min.js\"></script>
<script src=\"../bower_components/bootstrap-daterangepicker/daterangepicker.js\"></script>
<!-- bootstrap datepicker -->
<script src=\"../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\"></script>
<!-- bootstrap color picker -->
<script src=\"../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js\"></script>
<!-- bootstrap time picker -->
<script src=\"../plugins/timepicker/bootstrap-timepicker.min.js\"></script>
<!-- SlimScroll -->
<script src=\"../bower_components/jquery-slimscroll/jquery.slimscroll.min.js\"></script>
<!-- iCheck 1.0.1 -->
<script src=\"../plugins/iCheck/icheck.min.js\"></script>
<!-- FastClick -->
<script src=\"../bower_components/fastclick/lib/fastclick.js\"></script>
<!-- AdminLTE App -->
<script src=\"../dist/js/adminlte.min.js\"></script>
<!-- AdminLTE for demo purposes -->
<script src=\"../dist/js/demo.js\"></script>
<!-- Page script -->
";
  include("../footer.php");
  exit;
}
#################################################
function delete_building_room($select_building_location,$select_building_location_master,$id_loc_master)
{
  $encode_id_loc_master = base64_encode(base64_encode("$id_loc_master"));
  print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>ลบห้องภายในอาคาร/สถานที่</TITLE>
  <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">
  <!-- Bootstrap 3.3.7 -->
  <link rel=\"stylesheet\" href=\"../bower_components/bootstrap/dist/css/bootstrap.min.css\">
  <!-- Font Awesome -->
  <link rel=\"stylesheet\" href=\"../bower_components/font-awesome/css/font-awesome.min.css\">
  <!-- Ionicons -->
  <link rel=\"stylesheet\" href=\"../bower_components/Ionicons/css/ionicons.min.css\">
  <!-- Theme style -->
  <link rel=\"stylesheet\" href=\"../dist/css/AdminLTE.min.css\">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel=\"stylesheet\" href=\"../dist/css/skins/_all-skins.min.css\">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src=\"https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js\"></script>
  <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic\">
<style>
.myDiv {
  background-color: #fc7b6d;    
  text-align: center;
}
</style>
</HEAD>

<BODY leftMargin=0 topMargin=0 marginwidth='0' marginheight='0'>
<div class=\"myDiv wrapper\">
          <div class=\"modal-dialog\">
      <div class=\"col-xs-12\">

<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบห้องภายใน $select_building_location[name_location]</B></FONT><br><br>
<FONT size='3' color='#FFFFFF'><B> $select_building_location_master[name_location_master]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='select_building_room.php?closeme=true&delete_building_room=$encode_id_loc_master'\">Delete changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
<BR>
</BODY>
</HTML>
";
  exit;
}
#################################################
?>