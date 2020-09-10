<?php
include("../lib_org.php");
//$time_year = $_GET['time_year'];
#$time_year=time_year($time_year);
#$select_year=select_year($time_year);
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='1';
if ($_GET['add_asset_group_com']) {
  $select_name_dept = select_name_dept();
  add_asset_group_com($select_name_dept,$position_active);
}
if ($_GET['edit_asset_group']) {
  $id_ag = $_GET['edit_asset_group'];
  $id_ag = base64_decode(base64_decode("$id_ag"));
  $select_asset_group = select_asset_group($id_ag);
  edit_asset_group($select_asset_group, $id_emp_use, $id_ag,$position_active);
}
if ($_GET['delete_asset_group']) {
  $id_ag = $_GET['delete_asset_group'];
  $id_ag = base64_decode(base64_decode("$id_ag"));
  $select_asset_group = select_asset_group($id_ag);
  delete_asset_group($select_asset_group, $id_ag);
}
if ($_SESSION['id_emp_use']) {
} else {
  print "$error_login";
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
function select_asset_group($id_ag)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select code_asset,name_asset_group,depreciation,id_dept,id_use,last_update from asset_group where id_ag='$id_ag'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_asset_group['code_asset'] = $row["code_asset"];
    $select_asset_group['name_asset_group'] = $row["name_asset_group"];
    $select_asset_group['id_dept'] = $row["id_dept"];
    $select_asset_group['depreciation'] = $row["depreciation"];
    $select_asset_group['id_use'] = $row["id_use"];
    $select_asset_group['last_update'] = $row["last_update"];
  }
  return $select_asset_group;
}
##############################
function select_gdept($id_ag)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_dept,name_dept from dept_edu order by id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    if ($id_ag == $id_dept) {
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
  $sql = "SELECT name_asset_group,id_ag,code_asset FROM asset_group ORDER BY id_ag ASC";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $name_asset_group = $row["name_asset_group"];
    $id_ag = $row["id_ag"];
    $code_asset = $row["code_asset"];
    $print[$i] = "<option value='$id_ag'>$name_asset_group</option>";
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
function print_edit_asset_group($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.code_asset='$code_asset' and asset_type.id_ag=asset_group.id_ag";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_group = $row["name_asset_group"];
  }
  return $print_edit_asset_group;
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
function edit_asset_group($select_asset_group, $id_emp_use, $id_ag,$position_active)
{
  $encode_id_ag = base64_encode(base64_encode("$id_ag"));
  if($select_asset_group['depreciation']=='0'){$depreciation0="selected";$depreciation1="";}else{$depreciation0="";$depreciation1="selected";}
  $select_name_dept = select_gdept($select_asset_group['id_dept']);
  $select_gdept=$select_name_dept['selecte'];
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
        ประเภททรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"print_asset_group.php\">ทะเบียนทรัพย์สิน</a></li>
        <li ><a href=\"select_asset_group_com.php\">ประเภททรัพย์สิน</a></li>
        <li class=\"active\">แก้ไขประเภททรัพย์สิน</li>
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
              <h3 class=\"box-title\">แก้ไขประเภททรัพย์สิน</h3> 
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='select_asset_group_com.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                         </div>
                        <div class=\"col-md-10\">
                    <div class=\"box-body\">
                      <!-- //รหัสประเภททรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>รหัสประเภททรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='code_asset' id='code_asset' value=\"$select_asset_group[code_asset]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //ชื่อประเภททรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชื่อประเภททรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_asset_group' id='name_asset_group' value=\"$select_asset_group[name_asset_group]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //หน่วยงานที่ดูแล -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หน่วยงานที่ดูแล :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_dept' id='id_dept'>
                              <option value='0'>เลือก</option>
                              $select_gdept
                            </select>
                          </div>
                          <!-- /.form-group -->
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //ค่าเสื่อม -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ค่าเสื่อม :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='depreciation' id='depreciation'>
                             <option $depreciation0 value='0'>ไม่คิดค่าเสื่อม</option>
                             <option $depreciation1 value='1'>เส้นตรง (Straight-Line Method)</option>
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
                    <input type='hidden' name='id_ag' value='$id_ag'>
                    <input type=\"submit\" VALUE='Submit' name='save_asset_group' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
function add_asset_group_com($select_name_dept,$position_active)
{
  //$print_location = print_location();
  $print_asset_group = print_asset_group();
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
        ประเภททรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"print_asset_group.php\">ทะเบียนทรัพย์สิน</a></li>
        <li ><a href=\"select_asset_group_com.php\">ประเภททรัพย์สิน</a></li>
        <li class=\"active\">เพิ่มประเภททรัพย์สิน</li>
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
              <h3 class=\"box-title\">ประเภททรัพย์สิน</h3> 
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='select_asset_group_com.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                         </div>
                        <div class=\"col-md-10\">

                    <div class=\"box-body\">
                      <!-- //รหัสประเภททรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>รหัสประเภททรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='code_asset' id='code_asset' placeholder=\"ระบุรหัสประเภททรัพย์สิน\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //ชื่อประเภททรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชื่อประเภททรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_asset_group' id='name_asset_group' placeholder=\"ระบุชื่อประเภททรัพย์สิน\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>

                    <div class=\"box-body\">
                      <!-- //หน่วยงานที่ดูแล -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หน่วยงานที่ดูแล :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_dept' id='id_dept'>
                              <option value='0'>เลือก</option>
                              $select_name_dept
                            </select>
                          </div>
                          <!-- /.form-group -->
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>


                    <div class=\"box-body\">
                      <!-- //ค่าเสื่อม -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ค่าเสื่อม :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='depreciation' id='depreciation'>
                             <option value='0'>ไม่คิดค่าเสื่อม</option>
                             <option value='1'>เส้นตรง (Straight-Line Method)</option>
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
                    <input type=\"submit\" VALUE='Submit' name='add_asset_group' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
function delete_asset_group($select_asset_group, $id_ag)
{
  $encode_id_ag = base64_encode(base64_encode("$id_ag"));
  print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>ลบประเภททรัพย์สิน</TITLE>
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
<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบประเภททรัพย์สิน</B></FONT><br><br>
<FONT size='3' color='#FFFFFF'><B> $select_asset_group[code_asset] : $select_asset_group[name_asset_group]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='select_asset_group_com.php?closeme=true&delete_asset_group=$encode_id_ag'\">Delete changes</button>
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