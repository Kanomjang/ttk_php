<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='8';
if ($_GET['com_add_raw_matreial_seller']) {
    $id_crmd = $_GET['com_add_raw_matreial_seller'];
    $id_crmd = base64_decode(base64_decode("$id_crmd"));
 
  com_add_raw_matreial_seller($id_crmd,$position_active);
}
if ($_GET['edit_com_raw_matreial_seller']) {
  $id_crmse = $_GET['edit_com_raw_matreial_seller'];
  $id_crmse = base64_decode(base64_decode("$id_crmse"));
  $select_com_raw_matreial_seller = select_com_raw_matreial_seller($id_crmse);
  edit_com_raw_matreial_seller($select_com_raw_matreial_seller, $id_emp_use, $id_crmse, $position_active);
}
if ($_GET['delete_com_raw_matreial_seller']) {
  $id_crmse = $_GET['id_crmse'];
  $id_crmse = base64_decode(base64_decode("$id_crmse"));
  $select_com_raw_matreial_seller = select_com_raw_matreial_seller($id_crmse);
  delete_com_raw_matreial_seller($select_com_raw_matreial_seller,$id_crmse);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}
#################################################
function select_com_raw_matreial_seller($id_crmse)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select com_accounts_payable.name_accounts_payable,com_raw_material_details.name_raw_material_details from com_raw_matreial_seller,com_accounts_payable,com_raw_material_details where id_crmse='$id_crmse' AND 
  com_accounts_payable.id_cap = com_raw_matreial_seller.id_cap AND 
  com_raw_material_details.id_crmd = com_raw_matreial_seller.id_crmd";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_com_raw_matreial_seller['name_accounts_payable'] = $row["name_accounts_payable"];
    $select_com_raw_matreial_seller['name_raw_material_details'] = $row["name_raw_material_details"];
  }
  mysqli_close($connect);
  return $select_com_raw_matreial_seller;
}
#################################################
function print_edit_com_raw_matreial_seller($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.code_asset='$code_asset' and asset_type.id_cap=asset_group.id_cap";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_com_raw_matreial_seller = $row["name_asset_group"];
  }
  mysqli_close($connect);
  return $print_edit_com_raw_matreial_seller;
}
##############################
function print_edit_asset_type($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT usage_life_type FROM asset_type where code_asset='$code_asset'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_type = $row["usage_life_type"];
  }
  mysqli_close($connect);
  return $print_edit_asset_type;
}
##############################
function select_cap($id_crmd)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_cap,name_accounts_payable from com_accounts_payable order by id_cap ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_cap = $row["id_cap"];
    $name_accounts_payable = $row["name_accounts_payable"];
    $check_cap=check_cap($id_crmd,$id_cap);
    if ($check_cap > '0') {
        continue;   
    } else {
        $print[$i] = "<option value='$id_cap'>$name_accounts_payable</option>";
        $i++;
   }
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_cap  = implode("\n", $print);
  return $select_cap;
}
#################################################
function check_cap($id_crmd,$id_cap)
{
  global $host, $user, $passwd, $database;
  $check_cap='0';
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT COUNT(id_crmse) from com_raw_matreial_seller WHERE com_raw_matreial_seller.id_crmd='$id_crmd' AND com_raw_matreial_seller.id_cap ='$id_cap'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_cap = $row["COUNT(id_crmse)"];
  }
  mysqli_close($connect);
  return $check_cap;
}
#################################################
function edit_com_raw_matreial_seller($select_com_raw_matreial_seller, $id_emp_use, $id_crmse, $position_active)
{
$encode_id_crmse = base64_encode(base64_encode("$id_crmse"));

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
      รายละเอียดคลังวัตถุดิบ
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"com_raw_matreial_seller.php\">รายละเอียดคลังวัตถุดิบ</a></li>
        <li class=\"active\">แก้ไขรายละเอียดคลังวัตถุดิบ</li>
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
              <h3 class=\"box-title\">แก้ไขรายละเอียดวัตถุดิบ</h3> 
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
            <form name='theForm' target='_parent' action='com_raw_matreial_seller.php' method='POST'>

            <div class=\"row\">
                    <div class=\"col-md-2\">
                    </div>
                    <div class=\"col-md-10\">
                        <div class=\"box-body\"><!-- //ชื่อวัตถุดิบ -->
                            <div class=\"row\">
                                <div class=\"col-md-6\">
                                    <div class=\"form-group\">
                                        <label>ชื่อวัตถุดิบ :</label>
                                        <input type=\"text\" class=\"form-control\" NAME='name_crm' id='name_crm' value=\"$select_com_raw_matreial_seller[name_crm]\">
                                    </div><!-- /.form-group -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                        <div class=\"box-body\"><!-- //Min Stock -->
                            <div class=\"row\">
                                <div class=\"col-md-6\">
                                    <div class=\"form-group\">
                                        <label>Min Stock :</label>
                                        <input type=\"text\" class=\"form-control\" NAME='min_stock' id='min_stock'  value=\"$select_com_raw_matreial_seller[min_stock]\">
                                    </div><!-- /.form-group -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                          </div><!-- /.col-md-10 -->
            </div><!-- End Row-->

            <div class=\"row\">
              <div class=\"col-md-4\">
              </div>
              <div class=\"col-md-8\">
                <input type='hidden' name='id_crmse' value='$encode_id_crmse'>
                <input type=\"submit\" VALUE='Submit' name='save_com_raw_matreial_seller' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
              </div>
            </div>
        </div><!-- End Row-->
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
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
function com_add_raw_matreial_seller($id_crmd,$position_active)
{
    $select_cap=select_cap($id_crmd);
    $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));

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
      เพิ่มรายละเอียดวัตถุดิบ
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"com_raw_matreial_seller_details.php\">รายละเอียดวัตถุดิบ</a></li>
        <li ><a href=\"com_raw_matreial_seller.php?com_raw_matreial_seller=$encode_id_crmd\">สถานที่ซื้อ</a></li>
        <li class=\"active\">เพิ่มสถานที่ซื้อ</li>
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
              <h3 class=\"box-title\">เพิ่มสถานที่ซื้อวัตถุดิบ</h3>
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='com_raw_matreial_seller.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                        </div>
                        <div class=\"col-md-10\">
                        <div class=\"box-body\"><!-- //สถานที่สั่งซื้อ -->
                        <div class=\"row\">
                          <div class=\"col-md-6\">
                            <div class=\"form-group\">
                              <label>สถานที่สั่งซื้อ :</label>
                              <select class=\"form-control select2\" style=\"width: 100%;\" name='id_cap' id='id_cap'>
                                <option value='0'>เลือก</option>
                                $select_cap
                              </select>
                            </div><!-- /.form-group -->
                          </div><!-- /.col -->
                        </div><!-- /.row -->
                      </div><!-- /.box-body -->
                              </div><!-- /.col-md-10 -->
                </div><!-- End Row-->

                <div class=\"row\">
                  <div class=\"col-md-4\">
                  </div>
                  <div class=\"col-md-8\">
                  <input type='hidden' name='id_crmd' value='$encode_id_crmd'>
                  <input type=\"submit\" VALUE='Submit' name='com_add_raw_matreial_seller' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
                  </div>
                </div>
            </div><!-- End Row-->
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
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
function delete_com_raw_matreial_seller($select_com_raw_matreial_seller,$id_crmse)
{
  $encode_id_crmse = base64_encode(base64_encode("$id_crmse"));
  print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>ลบคลังวัตถุดิบ</TITLE>
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
<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบ</B></FONT><br><br>
<FONT size='3' color='#FFFFFF'><B>  $select_com_raw_matreial_seller[name_accounts_payable] &nbsp;&nbsp; ออกจาก &nbsp;&nbsp; $select_com_raw_matreial_seller[name_raw_material_details]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='com_raw_matreial_seller.php?closeme=true&delete_com_raw_matreial_seller=$encode_id_crmse'\">Delete changes</button>
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