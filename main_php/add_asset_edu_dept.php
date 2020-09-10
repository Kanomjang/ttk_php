<?php
include("../lib_org.php");
//$time_year = $_GET['time_year'];
#$time_year=time_year($time_year);
#$select_year=select_year($time_year);
$id_emp_use = $_SESSION['id_emp_use'];
$position_active='1';

if ($_GET['add_asset_edu']) {
  $select_name_dept = select_name_dept();
  add_asset_edu($select_name_dept,$position_active);
}
if ($_GET['edit_asset_edu']) {
  $id_ae = $_GET['edit_asset_edu'];
  $select_asset_edu = select_asset_edu($id_ae);
  edit_asset_edu($select_asset_edu, $id_emp_use, $id_ae,$position_active);
}
if ($_GET['delete_asset_edu']) {
  $id_ae = $_GET['delete_asset_edu'];
  $select_asset_edu = select_asset_edu($id_ae);
  delete_asset_edu($select_asset_edu, $id_ae);
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
  $shows = mysqli_query($connect, $sql_query);;
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
function select_asset_edu($id_ae)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_at,id_asset,name_asset,date_order,status_asset_type,year_asset,id_use,last_update,cost_price,comment,carcass_price from asset_edu where id_ae='$id_ae'";
  $shows = mysqli_query($connect, $sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $select_asset_edu['id_at'] = $row["id_at"];
    $select_asset_edu['id_asset'] = $row["id_asset"];
    $select_asset_edu['name_asset'] = $row["name_asset"];
    $select_asset_edu['year_asset'] = $row["year_asset"];
    $select_asset_edu['status_asset_type'] = $row["status_asset_type"];
    $select_asset_edu['date_order'] = $row["date_order"];
    $select_asset_edu['id_use'] = $row["id_use"];
    $select_asset_edu['last_update'] = $row["last_update"];
    $select_asset_edu['cost_price'] = $row["cost_price"];
    $select_asset_edu['comment'] = $row["comment"];
    $select_asset_edu['carcass_price'] = $row["carcass_price"];
  }
  return $select_asset_edu;
}
##############################
function select_gdept($id_ae)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  $sql_query = "select id_dept,date_dept from asset_dept order by  id_ad DESC limit 0,1";
  $shows = mysqli_query($connect, $sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $ad_gdept = $row["id_dept"];
    $date_dept = $row["date_dept"];
  }
  $sql_query = "select id_dept,name_dept from dept_edu order by  id_dept ASC";
  $shows = mysqli_query($connect, $sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $id_dept = $row["id_dept"];
    $name_dept = $row["name_dept"];
    if ($ad_gdept == $id_dept) {
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
  $select_gdept['date_dept']  = $date_dept;
  return $select_gdept;
}
##############################
function print_location()
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_location,id_loc FROM location_group ORDER BY id_loc ASC";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $name_location = $row["name_location"];
    $id_loc = $row["id_loc"];
    $print[$i] = "<option value='$id_loc'>$name_location</option>";
    $i++;
  }
  $list_group_item  = implode("\n", $print);
  return $list_group_item;
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
function print_edit_asset_group($id_at)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_asset_group FROM asset_group,asset_type where asset_type.id_at='$id_at' and asset_type.id_ag=asset_group.id_ag";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_group = $row["name_asset_group"];
  }
  return $print_edit_asset_group;
}
##############################
function print_edit_asset_type($id_at)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT name_asset_type FROM asset_type where id_at='$id_at'";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_asset_type = $row["name_asset_type"];
  }
  return $print_edit_asset_type;
}
##############################
function print_edit_date_location($id_ae)
{
  global $host, $user, $passwd, $database;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql = "SELECT date_location FROM asset_location where id_ae='$id_ae' order by id_al DESC limit 0,1";
  $query = mysqli_query($connect, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $print_edit_date_location = $row["date_location"];
  }
  return $print_edit_date_location;
}
##############################
function print_edit_location($id_ae)
{
  global $host, $user, $passwd, $database;
  $i = 0;
  $j = 0;
  $k = 0;
  $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database 4");
  //mysqli_select_db($database,$connect) or die(" ไม่สามารถเลือก database ได้");
  $sql_query = "select id_al,asset_location.id_loc_master,date_location,location_master.id_loc,floor_master,floor_location from asset_location,location_master,location_group where 
location_group.id_loc = location_master.id_loc and 
asset_location.id_loc_master = location_master.id_loc_master and id_ae='$id_ae' order by id_al DESC limit 0,1;";
  $shows = mysqli_query($connect, $sql_query);;
  while ($row = mysqli_fetch_array($shows)) {
    $id_al = $row["id_al"];
    $ad_loc_master = $row["id_loc_master"];
    $date_location = $row["date_location"];
    $ad_loc = $row["id_loc"];
    $ad_floor_master = $row["floor_master"];
    $floor_location = $row["floor_location"];
  }

  ######
  if ($ad_loc_master) {


    ######
    $sql_query = "select id_loc_master,name_location_master from location_master where id_loc='$ad_loc' and floor_master = '$ad_floor_master' order by id_loc_master ASC";
    $shows = mysqli_query($connect, $sql_query);;
    while ($row = mysqli_fetch_array($shows)) {
      $id_loc_master = $row["id_loc_master"];
      $name_location_master = $row["name_location_master"];
      if ($ad_loc_master == $id_loc_master) {
        $print_loc_master[$j] = "<option value='$id_loc_master' selected>$name_location_master</option>";
      } else {
        $print_loc_master[$j] = "<option value='$id_loc_master'>$name_location_master</option>";
      }
      $j++;
    }
    ######
    if (count($print_loc_master) == '0') {
      $print_loc_master[0] = "";
    }
    $print_edit_location['loc_master']  = implode("\n", $print_loc_master);//ห้อง
    for ($a = 1; $a <= $floor_location; $a++) {
      $b = $a + 1;
      if ($ad_floor_master == $a) {
        $select_floor_location[$a] = "<option value='$a&id_loc=$ad_loc' selected>$a</option>";
      } else {
        $select_floor_location[$a] = "<option value='$a&id_loc=$ad_loc'>$a</option>";
      }
    }

    if (count($select_floor_location) == '0') {
      $select_floor_location[0] = "";
    }
    $print_edit_location['floor_location']  = implode("\n", $select_floor_location);//ชั้น

    ######
    $sql_query = "select id_loc,name_location from location_group order by id_loc ASC";
    $shows = mysqli_query($connect, $sql_query);;
    while ($row = mysqli_fetch_array($shows)) {
      $name_location = $row["name_location"];
      $id_loc = $row["id_loc"];
      if ($ad_loc == $id_loc) {
        $print_loc[$i] = "<option value='$id_loc' selected>$name_location</option>";
      } else {
        $print_loc[$i] = "<option value='$id_loc'>$name_location</option>";
      }
      $i++;
    }
    ######
    if (count($print_loc) == '0') {
      $print_loc[0] = "";
    }
    $print_edit_location['loc']  = implode("\n", $print_loc);//อาคาร
  } else {

    $sql_query = "select id_loc,name_location from location_group order by id_loc ASC";
    $shows = mysqli_query($connect, $sql_query);;
    while ($row = mysqli_fetch_array($shows)) {
      $name_location = $row["name_location"];
      $ad_loc = $row["id_loc"];
      $print_loc[$k] = "<option value='$ad_loc'>$name_location</option>";
      $k++;
    }
    if (count($print_loc) == '0') {
      $print_loc[0] = "";
    }
    $print_edit_location['loc']  = implode("\n", $print_loc);
  }



  return $print_edit_location;
}

#################################################
function edit_asset_edu($select_asset_edu, $id_emp_use, $id_ae,$position_active)
{
  $encode_id_ae = base64_encode(base64_encode("$id_ae"));
  //$encode_id_emp = base64_encode(base64_encode("$select_asset_edu[id_emp]"));
  $print_edit_location = print_edit_location($id_ae);
  //$print_asset_group = print_asset_group();
  $print_edit_asset_type = print_edit_asset_type($select_asset_edu['id_at']);
  $print_edit_asset_group = print_edit_asset_group($select_asset_edu['id_at']);
  $select_name_dept = select_gdept($id_ae);
  $select_gdept=$select_name_dept['selecte'];
  $print_edit_date_location = print_edit_date_location($id_ae);
   if($select_name_dept['date_dept']=='0000-00-00'|| $select_name_dept['date_dept']==''){$date_dept="";}else{
   $date_deptt = explode("-", $select_name_dept['date_dept']);
  $date_dept = "$date_deptt[1]/$date_deptt[2]/$date_deptt[0]";
   }
   if($select_asset_edu['date_order']=='0000-00-00'|| $select_asset_edu['date_order']==''){$date_order="";}else{
  $date_orderr = explode("-", $select_asset_edu['date_order']);
  $date_order = "$date_orderr[1]/$date_orderr[2]/$date_orderr[0]";
   }
   if($print_edit_date_location=='0000-00-00'|| $print_edit_date_location==''){$date_location="";}else{
  $date_locationn = explode("-", $print_edit_date_location);
  $date_location = "$date_locationn[1]/$date_locationn[2]/$date_locationn[0]";
   }

  if ($select_asset_edu['status_asset_type'] == '0') {
    $status_asset_type0 = "selected";
  }else{    $status_asset_type0 = "";}
  if ($select_asset_edu['status_asset_type'] == '1') {
    $status_asset_type1 = "selected";
  }else{    $status_asset_type1 = "";}
  if ($select_asset_edu['status_asset_type'] == '2') {
    $status_asset_type2 = "selected";
  }else{    $status_asset_type2 = "";}
  if ($select_asset_edu['status_asset_type'] == '3') {
    $status_asset_type3 = "selected";
  }else{    $status_asset_type3 = "";}
  if ($select_asset_edu['status_asset_type'] == '4') {
    $status_asset_type4 = "selected";
  }else{    $status_asset_type4 = "";}

  $tyear_asset = $select_asset_edu['year_asset'] + 543;
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
        แก้ไขทะเบียนทรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"print_asset_edu.php\">ทะเบียนทรัพย์สิน</a></li>
        <li class=\"active\">แก้ไขทะเบียนทรัพย์สิน</li>
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
              <h3 class=\"box-title\">แก้ไขทะเบียนทรัพย์สิน  &nbsp; $select_asset_edu[id_asset] </h3>
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='print_asset_edu.php' method='POST'>

                <div class=\"row\">
                  <div class=\"col-md-6\">
                    <!-- Fild Left -->

                    <div class=\"box-body\">
                      <!-- //หน่วยงาน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หน่วยงานที่รับผิดชอบ :</label>
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
                      <!-- //ประเภท -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ประเภท :</label>
                              <input type=\"text\" class=\"form-control\" disabled name='id_ag' id='id_ag' value=\"$print_edit_asset_group\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //หมวด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หมวด :</label>
                              <input type=\"text\" class=\"form-control\" disabled  name='id_at1' id='id_at' value=\"$print_edit_asset_type\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //สถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>สถานที่/อาคาร :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_loc' id='id_loc'>
                              <option value='0'>เลือก</option>
                              $print_edit_location[loc] 
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ชั้น -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชั้น :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='floor_location' id='floor_location'>
                            $print_edit_location[floor_location]
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ห้อง -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ห้อง/อื่นๆ :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_loc_master' id='id_loc_master'>
                            $print_edit_location[loc_master]
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ปีที่ซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ปีที่ซื้อ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='year_asset' id='year_asset' value='$tyear_asset' disabled>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //วันสั่งซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันสั่งซื้อ :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker1\" name='date_order' value='$date_order'>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>


                  </div><!-- End Fild Left -->

                  <div class=\"col-md-6\">
                    <!-- Fild Right -->
                    <div class=\"box-body\">
                      <!-- //วันระบุสังกัด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันระบุสังกัด :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker2\" name='date_dept' value='$date_dept '>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //วันระบุสถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันระบุสถานที่ :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker3\" name='date_location' value='$date_location'>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //สถานะ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>สถานะ :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='status_asset_type' id='status_asset_type'>
                              <option $status_asset_type0 value='0'>เลือก</option>
                              <option $status_asset_type1 value='1'>ซื้อ</option>
                              <option $status_asset_type2 value='2'>เช่า</option>
                              <option $status_asset_type3 value='3'>เช่าซื้อ</option>
                              <option $status_asset_type4 value='4'>ยืม</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ข้อกำหนดทรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ข้อกำหนดทรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_asset' id='name_asset' value=\"$select_asset_edu[name_asset]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //รายละเอียด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>รายละเอียด :</label>
                            <textarea class=\"form-control\" rows=\"3\" NAME='comment'>$select_asset_edu[comment]</textarea>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ราคาซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ราคาซื้อ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='cost_price' id='cost_price' value=\"$select_asset_edu[cost_price]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ราคาซาก -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ราคาซาก :</label>
                            <input type=\"text\" class=\"form-control\" NAME='carcass_price' id='carcass_price' value=\"$select_asset_edu[carcass_price]\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                  </div><!-- End Fild Right -->
                </div><!-- End Row-->
                <div class=\"row\">
                  <div class=\"col-md-4\">
                  </div>
                  <div class=\"col-md-8\">
                    <input type='hidden' name='id_ae' value='$encode_id_ae'>
                    <input type=\"submit\" VALUE='Submit' name='save_asset' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
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

  <!-- page script -->
  <script type='text/javascript'>
    $(document).ready(function() {

          $('select#id_loc').change(function() {
            var id_loc1 = $.ajax({
              url: 'select_floor_location.php',
              data: 'id_loc2=' + $(this).val(),
              async: false
            }).responseText;
            $('select#floor_location').html(id_loc1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2  
            $('select#id_loc_master').html('');
            // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด
          });
          $('select#floor_location').change(function() {
            var floor_location1 = $.ajax({
              url: 'select_sub_location.php',
              data: 'floor_location=' + $(this).val(),
              async: false
            }).responseText;
            $('select#id_loc_master').html(floor_location1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2  
            // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด
            //alert(floor_location1);
          });
          $('select#id_ag').change(function() {
                var id_ag1 = $.ajax({
                  url: 'ajax_select_asset_group.php',
                  data: 'id_ag=' + $(this).val(),
                  async: false
                }).responseText;
                $('select#id_at').html(id_ag1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2

                var id_ag2 = $.ajax({
                  url: 'ajax_select_code_asset_group.php',
                  data: 'id_ag=' + $(this).val(),
                  async: false
                }).responseText;
                //alert(id_ag2);
                document.getElementById(\"code_asset\").innerHTML = id_ag2;
                  //$('span#code_asset').attr('innerHTML',id_ag2);
                  // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด  
                });

              $('select#id_at').change(function() {
                  if ($(this).val().length == '1') {
                    document.getElementById(\"asset_type\").innerHTML = \"0\"+$(this).val();
                      //$('span#asset_type').attr('innerHTML',\"0\"+$(this).val());
                    }
                    else {
                      document.getElementById(\"asset_type\").innerHTML = $(this).val();
                        //$('span#asset_type').attr('innerHTML',$(this).val());
                      }

                    });

                  $('#year_asset').blur(function() {
                        var time_year = $(this).val();
                        var Time_Year = time_year.substring(2);
                        document.getElementById(\"time_year\").innerHTML = Time_Year;
                          //$('span#time_year').attr('innerHTML',Time_Year);
                        });

                      $('#count_num').blur(function() {
                          var time_year = document.getElementById(\"year_asset\").value;
                            //alert('time_year='+time_year+'&id_at='+$('select#id_at').val());
                            var text_count_num = $.ajax({
                              url: 'ajax_select_code_ad.php',
                              data: 'time_year=' + time_year + '&id_at=' + $('select#id_at').val(),
                              async: false
                            }).responseText;
                            //alert(text_count_num);

                            var text_count_num1 = parseFloat(text_count_num);

                            var four_text_count_num = padIt(text_count_num1)
                            //alert(four_text_count_num); 

                            document.getElementById(\"no_asset\").innerHTML = four_text_count_num;
                              //$('span#no_asset').attr('innerHTML',four_text_count_num);
                              if ($(this).val() > '1') {
                                var x = parseFloat(text_count_num);
                                var y = parseFloat($(this).val());

                                var next_text_count_num = x + y - 1;
                                var four_next_text_count_num = padIt(next_text_count_num);
                                document.getElementById(\"next_asset\").innerHTML = '- ('+four_next_text_count_num+')';
                                  //$('span#next_asset').attr('innerHTML','- ('+four_next_text_count_num+')');

                                }
                                else {
                                  document.getElementById(\"no_asset\").innerHTML = '&nbsp;';
                                    //$('span#next_asset').attr('innerHTML','&nbsp;');
                                  }

                                  // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด  
                                });

                              function padIt(s) {
                                s = \"\"+s;
                                while (s.length < 4) {
                                  s = \"0\" + s;
                                }
                                return s;
                              }

                            });
  </script>
";
  include("../footer.php");
  exit;
}
######################หน้าแสดง###########################
function add_asset_edu($select_name_dept,$position_active)
{
  $print_location = print_location();
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
        เพิ่มทะเบียนทรัพย์สิน
        <small>Version 1.0</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li ><a href=\"print_asset_edu.php\">ทะเบียนทรัพย์สิน</a></li>
        <li class=\"active\">เพิ่มทะเบียนทรัพย์สิน</li>
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
              <h3 class=\"box-title\">ทะเบียนทรัพย์สิน</h3> &nbsp; <span id='code_asset'></span><span id='asset_type'></span><span id='time_year'></span><span id='no_asset'></span><span id='next_asset'></span>
            </div>
            <!-- /.box-header -->
            <div class=\"box-body\">
              <form name='theForm' target='_parent' action='print_asset_edu.php' method='POST'>

                <div class=\"row\">
                  <div class=\"col-md-6\">
                    <!-- Fild Left -->

                    <div class=\"box-body\">
                      <!-- //หน่วยงาน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หน่วยงานที่รับผิดชอบ :</label>
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
                      <!-- //ประเภท -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ประเภท :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_ag' id='id_ag'>
                              <option value='0'>เลือก</option>
                              $print_asset_group
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //หมวด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>หมวด :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_at1' id='id_at'>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //สถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>สถานที่/อาคาร :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_loc' id='id_loc'>
                              <option value='0'>เลือก</option>
                              $print_location
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ชั้น -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ชั้น :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='floor_location' id='floor_location'>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ห้อง -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ห้อง/อื่นๆ :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='id_loc_master' id='id_loc_master'>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ปีที่ซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ปีที่ซื้อ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='year_asset' size='5' id='year_asset' placeholder=\"Enter ...\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //วันสั่งซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันสั่งซื้อ :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker1\" name='date_order' value=''>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>


                  </div><!-- End Fild Left -->

                  <div class=\"col-md-6\">
                    <!-- Fild Right -->
                    <div class=\"box-body\">
                      <!-- //วันระบุสังกัด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันระบุสังกัด :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker2\" name='date_dept' value=''>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //วันระบุสถานที่ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>วันระบุสถานที่ :</label>
                            <div class=\"input-group date\">
                              <div class=\"input-group-addon\">
                                <i class=\"fa fa-calendar\"></i>
                              </div>
                              <input type=\"text\" class=\"form-control pull-right\" id=\"datepicker3\" name='date_location' value=''>
                            </div>
                            <!-- /.input group -->
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //สถานะ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>สถานะ :</label>
                            <select class=\"form-control select2\" style=\"width: 100%;\" name='status_asset_type' id='status_asset_type'>
                              <option value='0'>เลือก</option>
                              <option value='1'>ซื้อ</option>
                              <option value='2'>เช่า</option>
                              <option value='3'>เช่าซื้อ</option>
                              <option value='4'>ยืม</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ข้อกำหนดทรัพย์สิน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ข้อกำหนดทรัพย์สิน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='name_asset' id='name_asset' placeholder=\"ข้อกำหนดทรัพย์สิน ...\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //รายละเอียด -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>รายละเอียด :</label>
                            <textarea class=\"form-control\" rows=\"3\" NAME='comment'  id='comment' placeholder=\"รายละเอียด ...\"></textarea>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ราคาซื้อ -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ราคาซื้อ :</label>
                            <input type=\"text\" class=\"form-control\" NAME='cost_price' id='cost_price' placeholder=\"ราคาซื้อ ...\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //ราคาซาก -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>ราคาซาก :</label>
                            <input type=\"text\" class=\"form-control\" NAME='carcass_price' id='carcass_price' placeholder=\"ราคาซาก ...\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <div class=\"box-body\">
                      <!-- //จำนวน -->
                      <div class=\"row\">
                        <div class=\"col-md-6\">
                          <div class=\"form-group\">
                            <label>จำนวน :</label>
                            <input type=\"text\" class=\"form-control\" NAME='count_num' id='count_num' placeholder=\"จำนวน ...\">
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                  </div><!-- End Fild Right -->
                </div><!-- End Row-->
                <div class=\"row\">
                  <div class=\"col-md-4\">
                  </div>
                  <div class=\"col-md-8\">
                    <input type=\"submit\" VALUE='Submit' name='add_asset' class=\"btn btn-primary\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='Cancel' ONCLICK='window.history.back();'>
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

  <!-- page script -->
  <script type='text/javascript'>
    $(document).ready(function() {

          $('select#id_loc').change(function() {
            var id_loc1 = $.ajax({
              url: 'select_floor_location.php',
              data: 'id_loc2=' + $(this).val(),
              async: false
            }).responseText;
            $('select#floor_location').html(id_loc1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2  
            $('select#id_loc_master').html('');
            // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด
          });
          $('select#floor_location').change(function() {
            var floor_location1 = $.ajax({
              url: 'select_sub_location.php',
              data: 'floor_location=' + $(this).val(),
              async: false
            }).responseText;
            $('select#id_loc_master').html(floor_location1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2  
            // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด
            //alert(floor_location1);
          });
          $('select#id_ag').change(function() {
                var id_ag1 = $.ajax({
                  url: 'ajax_select_asset_group.php',
                  data: 'id_ag=' + $(this).val(),
                  async: false
                }).responseText;
                $('select#id_at').html(id_ag1); // นำค่า datalist2 มาแสดงใน listbox ที่ 2 ที่ชื่อ list2

                var id_ag2 = $.ajax({
                  url: 'ajax_select_code_asset_group.php',
                  data: 'id_ag=' + $(this).val(),
                  async: false
                }).responseText;
                //alert(id_ag2);
                document.getElementById(\"code_asset\").innerHTML = id_ag2;
                  //$('span#code_asset').attr('innerHTML',id_ag2);
                  // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด  
                });

              $('select#id_at').change(function() {
                  if ($(this).val().length == '1') {
                    document.getElementById(\"asset_type\").innerHTML = \"0\"+$(this).val();
                      //$('span#asset_type').attr('innerHTML',\"0\"+$(this).val());
                    }
                    else {
                      document.getElementById(\"asset_type\").innerHTML = $(this).val();
                        //$('span#asset_type').attr('innerHTML',$(this).val());
                      }

                    });

                  $('#year_asset').blur(function() {
                        var time_year = $(this).val();
                        var Time_Year = time_year.substring(2);
                        document.getElementById(\"time_year\").innerHTML = Time_Year;
                          //$('span#time_year').attr('innerHTML',Time_Year);
                        });

                      $('#count_num').blur(function() {
                          var time_year = document.getElementById(\"year_asset\").value;
                            //alert('time_year='+time_year+'&id_at='+$('select#id_at').val());
                            var text_count_num = $.ajax({
                              url: 'ajax_select_code_ad.php',
                              data: 'time_year=' + time_year + '&id_at=' + $('select#id_at').val(),
                              async: false
                            }).responseText;
                            //alert(text_count_num);

                            var text_count_num1 = parseFloat(text_count_num);

                            var four_text_count_num = padIt(text_count_num1)
                            //alert(four_text_count_num); 

                            document.getElementById(\"no_asset\").innerHTML = four_text_count_num;
                              //$('span#no_asset').attr('innerHTML',four_text_count_num);
                              if ($(this).val() > '1') {
                                var x = parseFloat(text_count_num);
                                var y = parseFloat($(this).val());

                                var next_text_count_num = x + y - 1;
                                var four_next_text_count_num = padIt(next_text_count_num);
                                document.getElementById(\"next_asset\").innerHTML = '- ('+four_next_text_count_num+')';
                                  //$('span#next_asset').attr('innerHTML','- ('+four_next_text_count_num+')');

                                }
                                else {
                                  document.getElementById(\"no_asset\").innerHTML = '&nbsp;';
                                    //$('span#next_asset').attr('innerHTML','&nbsp;');
                                  }

                                  // ชื่อตัวแปร และ element ต่างๆ สามารถเปลี่ยนไปตามการกำหนด  
                                });

                              function padIt(s) {
                                s = \"\"+s;
                                while (s.length < 4) {
                                  s = \"0\" + s;
                                }
                                return s;
                              }

                            });
  </script>
";
  include("../footer.php");
  exit;
}
#################################################
function delete_asset_edu($select_asset_edu, $id_ae)
{
  $encode_id_ae = base64_encode(base64_encode("$id_ae"));
  print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd'>
<HTML>
<HEAD>
<TITLE>ลบทะเบียนทรัพย์สิน</TITLE>
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
<br><FONT size='5' color='#FFFFFF'><B>ยืนยันการลบทะเบียนทรัพย์สิน</B></FONT><br><br><FONT size='3' color='#FFFFFF'><B>$select_asset_edu[id_asset] : $select_asset_edu[name_asset]</B><br><br>
              </div>
              <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-outline pull-left\" data-dismiss=\"modal\" onclick='window.close()'>Close</button>
                <button type=\"button\" class=\"btn btn-outline\" onclick=\"location.href='print_asset_edu.php?closeme=true&delete_asset=$encode_id_ae'\">Delete changes</button>
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