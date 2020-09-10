<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='9';
$dept_position_active=dept_position_active($position_active);
if ($_GET['com_add_raw_material']) {
  $id_crm = $_GET['com_add_raw_material'];
  $id_crm = base64_decode(base64_decode("$id_crm"));
  com_add_raw_material($dept_position_active,$id_crm,$position_active);
}
if ($_GET['edit_com_raw_material']) {
  $id_crm = $_GET['edit_com_raw_material'];
  $id_crm = base64_decode(base64_decode("$id_crm"));
  $select_com_raw_material = select_com_raw_material($id_crm);
  edit_com_raw_material($select_com_raw_material, $id_emp_use, $id_crm, $position_active);
}
if ($_GET['delete_com_raw_material']) {
  $id_crmd = $_GET['delete_com_raw_material'];
  $id_crmd = base64_decode(base64_decode("$id_crmd"));
  $select_com_raw_material = select_com_raw_material($id_crmd);
  delete_com_raw_material($select_com_raw_material,$id_crmd);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}

#################################################
function dept_position_active($position_active)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_dept from dept_position where position_active='$position_active'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $dept_position_active = $row["id_dept"];
  }
  return $dept_position_active;
}
#################################################
function select_com_raw_material($id_crm)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
  $sql_query = "select id_crmd,min_stock from com_raw_material where id_crm='$id_crm'";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_com_raw_material['id_crmd'] = $row["id_crmd"];
    $select_com_raw_material['min_stock'] = $row["min_stock"];
  }
  return $select_com_raw_material;
}
#################################################
function print_edit_com_raw_material($code_asset)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.code_asset='$code_asset' and asset_type.id_crmd=asset_group.id_crmd";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_com_raw_material = $row["name_asset_group"];
  }
  return $print_edit_com_raw_material;
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
  return $print_edit_asset_type;
}
##############################
function select_input_crm($id_crm)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_crmse,com_accounts_payable.id_cap,com_accounts_payable.name_accounts_payable FROM com_raw_material,com_raw_material_details,com_raw_matreial_seller,com_accounts_payable WHERE com_raw_material.id_crm = '$id_crm' AND 
  com_raw_matreial_seller.id_crmd = com_raw_material_details.id_crmd AND 
  com_accounts_payable.id_cap = com_raw_matreial_seller.id_cap AND 
  com_raw_material_details.id_crmd = com_raw_material.id_crmd";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmse = $row["id_crmse"];
    $name_accounts_payable = $row["name_accounts_payable"];
       $print[$i] = "<option value='$id_crmse'>$name_accounts_payable</option>";
        $i++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_input_crm  = implode("\n", $print);
  return $select_input_crm;
}
##############################
function select_crm($id_crm)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "SELECT com_raw_material_details.name_raw_material_details FROM com_raw_material,com_raw_material_details WHERE 
  com_raw_material.id_crm = '$id_crm' AND 
  com_raw_material_details.id_crmd = com_raw_material.id_crmd";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $select_crm = $row["name_raw_material_details"];

  }
  mysqli_close($connect);
  return $select_crm;
}
#################################################
function check_crmd($id_crmd)
{
  global $host, $user, $passwd, $database;
  $check_crmd='0';
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql = "SELECT COUNT(id_crm) from com_raw_material WHERE com_raw_material.id_crmd='$id_crmd'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $check_crmd = $row["COUNT(id_crm)"];
  }
  mysqli_close($connect);
  return $check_crmd;
}
##############################
function select_edit_crmd($id_crmd_old)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_crmd,name_raw_material_details from com_raw_material_details order by id_crmd ASC";
  $shows = mysqli_query($connect, $sql_query);
  while ($row = mysqli_fetch_array($shows)) {
    $id_crmd = $row["id_crmd"];
    $name_raw_material_details = $row["name_raw_material_details"];
    if ($id_crmd_old == $id_crmd) {
      $print[$i] = "<option value='$id_crmd' selected>$name_raw_material_details</option>";
    } else {
        $print[$i] = "<option value='$id_crmd'>$name_raw_material_details</option>";
   }
   $i++;
  }
  mysqli_close($connect);
  if (count($print) == '0') {
    $print[0] = "";
  }
  $select_crmd  = implode("\n", $print);
  return $select_crmd;
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
  $select_gdept  = implode("\n", $print);
  return $select_gdept;
}
#################################################
function edit_com_raw_material($select_com_raw_material, $id_emp_use, $id_crm, $position_active)
{
$encode_id_crm = base64_encode(base64_encode("$id_crm"));
$select_edit_crmd = select_edit_crmd($select_com_raw_material['id_crmd']);
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
        <li ><a href=\"com_raw_materials.php\">คลังวัตถุดิบ</a></li>
        <li ><a href=\"com_raw_material.php\">รายละเอียดคลังวัตถุดิบ</a></li>
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
            <form name='theForm' target='_parent' action='com_raw_material.php' method='POST'>

            <div class=\"row\">
                    <div class=\"col-md-2\">
                    </div>
                    <div class=\"col-md-10\">
                    <div class=\"box-body\"><!-- //วัตถุดิบ -->
                    <div class=\"row\">
                      <div class=\"col-md-6\">
                        <div class=\"form-group\">
                          <label>วัตถุดิบ :</label>
                          <select class=\"form-control select2\" style=\"width: 100%;\" name='id_crmd' id='id_crmd'>
                            <option value='0'>เลือก</option>
                            $select_edit_crmd
                          </select>
                        </div><!-- /.form-group -->
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                  </div><!-- /.box-body -->
                  <div class=\"box-body\"><!-- //Min Stock -->
                            <div class=\"row\">
                                <div class=\"col-md-6\">
                                    <div class=\"form-group\">
                                        <label>Min Stock :</label>
                                        <input type=\"text\" class=\"form-control\" NAME='min_stock' id='min_stock'  value=\"$select_com_raw_material[min_stock]\">
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
                <input type='hidden' name='id_crm' value='$encode_id_crm'>
                <input type=\"submit\" VALUE='Submit' name='save_com_raw_material' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
function com_add_raw_material($dept_position_active,$id_crm,$position_active)
{
  $encode_id_crm = base64_encode(base64_encode("$id_crm"));
  $select_input_crm=select_input_crm($id_crm);
  $select_crm=select_crm($id_crm);
  $select_gdept=select_gdept($dept_position_active);
  $time=time();
  $thai_date_fullmonth=thai_date_fullmonth($time);
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
      Purchase Request
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"com_raw_materials.php\">คลังวัตถุดิบ</a></li>
        <li class=\"active\">ออกใบขอซื้อ (PR)</li>
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
              <h3 class=\"box-title\">ออกใบขอซื้อ (PR) &nbsp;&nbsp;&nbsp; <strong><u>เรื่อง</u> การขอสั่งซื้อ $select_crm</strong> </h3> &nbsp;&nbsp; <i>( $thai_date_fullmonth )</i>
            </div>
            <!-- /.box-header -->
            
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='com_raw_materials.php' method='POST'>

                <div class=\"row\">
                        <div class=\"col-md-2\">
                        </div>
                        <div class=\"col-md-10\">
                        <div class=\"box-body\">
                        <!-- //หน่วยงาน -->
                        <div class=\"row\">
                          <div class=\"col-md-6\">
                            <div class=\"form-group\">
                              <label>หน่วยงาน :</label>
                              <select class=\"form-control select2\" style=\"width: 100%;\" name='id_dept' id='id_dept'>
                                $select_gdept
                              </select>
                              <input type=\"hidden\" id=\"title_cpr\" name='title_cpr' value='การขอสั่งซื้อ $select_crm'>
                            </div>
                            <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
  
                      <div class=\"box-body\">
                        <!-- //วันที่ต้องการใช้งาน -->
                        <div class=\"row\">
                          <div class=\"col-md-6\">
                            <div class=\"form-group\">
                              <label>วันที่ต้องการใช้งาน :</label>
                              <div class=\"input-group date\">
                                <div class=\"input-group-addon\">
                                  <i class=\"fa fa-calendar\"></i>
                                </div>
                                <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker1\" name='date_use' value=''>
                              </div>
                              <!-- /.input group -->
                            </div>
                            <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div><!-- /.box-body -->

                            <div class=\"box-body\"><!-- //จำนวน -->
                                <div class=\"row\">
                                    <div class=\"col-md-6\">
                                        <div class=\"form-group\">
                                            <label>จำนวน :</label>
                                            <input type=\"text\" class=\"form-control\" NAME='unit_quantity' id='unit_quantity' placeholder=\"ระบุจำนวน\">
                                        </div><!-- /.form-group -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </div><!-- /.box-body -->

                            <div class=\"box-body\"><!-- //หมายเหตุ/จุดประสงค์ -->
                                <div class=\"row\">
                                    <div class=\"col-md-6\">
                                        <div class=\"form-group\">
                                            <label>หมายเหตุ/จุดประสงค์ :</label>
                                            <input type=\"text\" class=\"form-control\" NAME='comment_cpr' id='comment_cpr' value='$select_crm ใกล้หมดจากสต๊อก' placeholder=\"หมายเหตุ/จุดประสงค์\">
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
                  <input type='hidden' name='id_crm' value='$encode_id_crm'>
                    <input type=\"submit\" VALUE='Submit' name='add_com_purchase_request' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    })
    $('#datepicker2').datepicker({
      autoclose: true
    })
    $('#datepicker3').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type=\"checkbox\"].minimal, input[type=\"radio\"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type=\"checkbox\"].minimal-red, input[type=\"radio\"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type=\"checkbox\"].flat-red, input[type=\"radio\"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>

";
  include("../footer.php");
  exit;
}
#################################################
function delete_com_raw_material($select_com_raw_material,$id_crmd)
{
  $encode_id_crmd = base64_encode(base64_encode("$id_crmd"));
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
<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบรายละเอียดวัตถุดิบ</B></FONT><br><br>
<FONT size='3' color='#FFFFFF'><B> $select_com_raw_material[name_crm]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='com_raw_material.php?closeme=true&delete_com_raw_material=$encode_id_crmd'\">Delete changes</button>
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