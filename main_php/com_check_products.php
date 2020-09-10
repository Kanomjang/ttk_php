<?php
include("../lib_org.php");
$id_emp_use = $_SESSION['id_emp_use'];
$title_head_html = "ใบรับสินค้า";
$position_active = '14';
#$dept_position_active=dept_position_active($position_active);
if ($_GET['com_purchase_order']) {
    $id_cpo = $_GET['com_purchase_order'];
    $id_cpo = base64_decode(base64_decode("$id_cpo"));
    $select_data_cpo = select_data_cpo($id_cpo);
    $com_receive_inventory = com_receive_inventory($id_cpo);

    com_purchase_order($select_data_cpo, $id_cpo, $com_receive_inventory, $position_active);
}
if ($_POST['add_com_receive_inventory']) {
    $id_cpo = $_POST['id_cpo'];
    $id_cpo = base64_decode(base64_decode("$id_cpo"));
    $no_delivery_note = $_POST['no_delivery_note'];
    $unit_quantity = $_POST['unit_quantity'];
    $unit_price = $_POST['unit_price'];
    $discounts = $_POST['discounts'];
    $vat = $_POST['vat'];
    $real_price = $_POST['real_price'];
    $com_receive_inventory = com_receive_inventory($id_cpo);
    if ($com_receive_inventory['id_cri']) {
        update_com_receive_inventory($com_receive_inventory['id_cri'], $no_delivery_note, $unit_quantity, $unit_price, $discounts, $vat, $real_price);
    } else {
        insert_com_receive_inventory($id_cpo, $no_delivery_note, $unit_quantity, $unit_price, $discounts, $vat, $real_price);
    }

    $select_data_cpo = select_data_cpo($id_cpo);
    com_purchase_order($select_data_cpo, $id_cpo, $com_receive_inventory, $position_active);
}
#if ($_SESSION['id_emp_use']) {
#} else {
#  print "$error_login";
#}

#################################################
function update_com_receive_inventory($id_cri, $no_delivery_note, $unit_quantity, $unit_price, $discounts, $vat, $real_price)
{
    global $host, $user, $passwd, $database;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "update com_receive_inventory set no_delivery_note='$no_delivery_note',unit_quantity='$unit_quantity',unit_price='$unit_price',discounts='$discounts',vat='$vat',real_price='$real_price' where id_cri='$id_cri'";
    print "$sql_query <br>";
    mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
##############################
function insert_com_receive_inventory($id_cpo, $no_delivery_note, $unit_quantity, $unit_price, $discounts, $vat, $real_price)
{
    global $host, $user, $passwd, $database, $id_emp_use;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "insert into com_receive_inventory(id_cpo,no_delivery_note,unit_quantity,unit_price,discounts,vat,real_price,date_delivery_note,id_use)
   values('$id_cpo','$no_delivery_note','$unit_quantity','$unit_price','$discounts','$vat','$real_price',NOW(),'$id_emp_use')";
    print "$sql_query <br>";
    mysqli_query($connect, $sql_query);
    mysqli_close($connect);
}
#################################################
function select_data_cpo($id_cpo)
{
    global $host, $user, $passwd, $database;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "SELECT id_crm,no_cpo,id_quotation,date_delivery,vat,discounts,unit,place_delivery,payment_term,com_raw_material_details.code_crmd,com_raw_material_details.name_raw_material_details,com_accounts_payable.code_cap,com_accounts_payable.name_accounts_payable,com_accounts_payable.address_accounts_payable,com_accounts_payable.contact_person_name,com_accounts_payable.tel_contact,com_accounts_payable.email_contact,id_purchasing_jobs,date_purchasing_jobs,no_order,id_use_approve,date_approve,com_purchase_request.title_cpr,com_purchase_request.date_purchasing_jobs,com_purchase_request.date_use,com_purchase_request.id_dept,com_purchase_request.id_crmse,com_purchase_request.unit_quantity,com_purchase_request.unit_price,com_purchase_request.comment_cpr,com_purchase_request.approve,com_purchase_request.id_use FROM com_purchase_request,com_raw_matreial_seller,com_accounts_payable,com_raw_material_details,com_purchase_order WHERE com_purchase_order.id_cpo='$id_cpo' AND com_purchase_order.id_cpr=com_purchase_request.id_cpr AND com_purchase_request.id_crmse = com_raw_matreial_seller.id_crmse AND com_raw_matreial_seller.id_cap = com_accounts_payable.id_cap AND com_raw_matreial_seller.id_crmd = com_raw_material_details.id_crmd";
    $query = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($query)) {
        $select_data_cpo['no_cpo'] = $row["no_cpo"];
        $select_data_cpo['id_quotation'] = $row["id_quotation"];
        $select_data_cpo['date_delivery'] = $row["date_delivery"];
        $select_data_cpo['vat'] = $row["vat"];
        $select_data_cpo['discounts'] = $row["discounts"];
        $select_data_cpo['unit'] = $row["unit"];
        $select_data_cpo['place_delivery'] = $row["place_delivery"];
        $select_data_cpo['payment_term'] = $row["payment_term"];
        $select_data_cpo['code_crmd'] = $row["code_crmd"];
        $select_data_cpo['name_raw_material_details'] = $row["name_raw_material_details"];
        $select_data_cpo['code_cap'] = $row["code_cap"];
        $select_data_cpo['name_accounts_payable'] = $row["name_accounts_payable"];
        $select_data_cpo['address_accounts_payable'] = $row["address_accounts_payable"];
        $select_data_cpo['contact_person_name'] = $row["contact_person_name"];
        $select_data_cpo['tel_contact'] = $row["tel_contact"];
        $select_data_cpo['email_contact'] = $row["email_contact"];
        $select_data_cpo['no_order'] = $row["no_order"];
        $select_data_cpo['title_cpr'] = $row["title_cpr"];
        $select_data_cpo['date_purchasing_jobs'] = $row["date_purchasing_jobs"];
        $select_data_cpo['date_use'] = $row["date_use"];
        $select_data_cpo['id_dept'] = $row["id_dept"];
        $select_data_cpo['id_crm'] = $row["id_crm"];
        $select_data_cpo['id_crmse'] = $row["id_crmse"];
        $select_data_cpo['unit_quantity'] = $row["unit_quantity"];
        $select_data_cpo['unit_price'] = $row["unit_price"];
        $select_data_cpo['comment_cpr'] = $row["comment_cpr"];
        $select_data_cpo['approve'] = $row["approve"];
        $select_data_cpo['id_use'] = $row["id_use"];
        $select_data_cpo['id_use_approve'] = $row["id_use_approve"];
        $select_data_cpo['date_approve'] = $row["date_approve"];
        $select_data_cpo['id_purchasing_jobs'] = $row["id_purchasing_jobs"];
        $select_data_cpo['date_purchasing_jobs'] = $row["date_purchasing_jobs"];
    }
    mysqli_close($connect);
    return $select_data_cpo;
}
##############################
function select_position_emp($id_emp)
{
    global $host, $user, $passwd, $database;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "SELECT name_position FROM position_emp,position_edu WHERE position_edu.id_position = position_emp.id_position AND position_emp.id_emp='$id_emp'";
    $shows = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($shows)) {
        $select_position_emp = $row["name_position"];
    }
    mysqli_close($connect);
    return $select_position_emp;
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
    mysqli_close($connect);
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
    mysqli_close($connect);
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
    mysqli_close($connect);
    return $print_edit_asset_type;
}
##############################
function select_input_crm($id_crm, $id_crmse_old)
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
        if ($id_crmse == $id_crmse_old) {
            $print[$i] = "<option value='$id_crmse' selected>$name_accounts_payable</option>";
        } else {
            $print[$i] = "<option value='$id_crmse'>$name_accounts_payable</option>";
        }
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
    $sql_query = "SELECT com_raw_material_details.name_raw_material_details FROM com_raw_material,com_raw_material_details WHERE com_raw_material.id_crm = '$id_crm' AND com_raw_material_details.id_crmd = com_raw_material.id_crmd";
    print "$sql_query <br>";
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
    $check_crmd = '0';
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
    mysqli_close($connect);
    if (count($print) == '0') {
        $print[0] = "";
    }
    $select_gdept  = implode("\n", $print);
    return $select_gdept;
}
#################################################
function select_com_suggestion($id_cpo)
{
    global $host, $user, $passwd, $database, $dayTH, $monthTH;
    $i = 0;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "SELECT com_suggestion.comment_suggestion,com_suggestion.id_use,date(com_suggestion.last_update) AS date_com_suggestion,time(com_suggestion.last_update) AS time_com_suggestion FROM com_suggestion WHERE com_suggestion.id_cpo='$id_cpo'";
    $shows = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($shows)) {
        $comment_suggestion = $row["comment_suggestion"];
        $id_use = $row["id_use"];
        $date_com_suggestion = $row["date_com_suggestion"];
        $time_com_suggestion = $row["time_com_suggestion"];
        $name_user = name_user($id_use);
        $date02 = new DateTime($date_com_suggestion);
        $dd02 = $date02->format('j');
        $mm02 = $monthTH[$date02->format('n')];
        $yy02 = $date02->format('Y') + 543;
        $day_com_suggestion = "$dd02 $mm02 $yy02 : $time_com_suggestion";

        $print[$i] = "
    <div class=\"row\">
      <div class=\"col-md-12\">
        <div class=\"box box-success box-solid\">
          <div class=\"box-header with-border\">
            <div class=\"col-md-6 text-left\">
            &nbsp;&nbsp; จาก $name_user[name]</i>
            </div><!-- /.col-md-6 -->
            <div class=\"col-md-6 text-right\">
              <i>$day_com_suggestion</i> &nbsp;&nbsp; &nbsp;&nbsp;
            </div><!-- /.col-md-6 -->
            <div class=\"box-tools pull-right\">
                <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
        <div class=\"box-body\">
          $comment_suggestion
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  ";
        $i++;
    }
    mysqli_close($connect);
    $select_com_suggestion1 = implode("\n", $print);
    $encode_id_cpo = base64_encode(base64_encode("$id_cpo"));
    if (count($print) > '0') {
        $select_com_suggestion = "<br>
    <div class=\"row\">
    <div class=\"col-md-2\"></div>

    <div class=\"col-md-8\">
      <div class=\"box box-info box-solid collapsed-box\">
        <div class=\"box-header box-success with-border\">
          <h3 class=\"box-title\">ข้อเสนอแนะ ($i)</h3>

          <div class=\"box-tools pull-right\">
            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-plus\"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class=\"box-body\">
        $select_com_suggestion1

        <form name='theForm' target='_parent' action='com_purchase_order_approval.php' method='POST'>
        <div class=\"box-body\"><!-- //ข้อเสนอแนะ -->
        <div class=\"row\">        <div class=\"col-xs-2\"><label>ข้อเสนอแนะ :</div>
        <div class=\"col-xs-6\"><input type='hidden' name='id_cpo' value='$encode_id_cpo'>
        <input type=\"text\" class=\"form-control\" NAME='comment_suggestion' id='comment_suggestion'  value=\"\">
        </div>
              </label>
              <div class=\"col-xs-4\"><input type=\"submit\" VALUE='ส่งข้อเสนอแนะ' name='send_po_suggestion' class=\"btn btn-primary\"></div>                  
        </div><!-- /.row -->
      </div><!-- /.box-body -->
      </form>


        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

";
    } else {
        $select_com_suggestion = "";
    }


    return $select_com_suggestion;
}
#################################################
function select_cpo($id_cpo)
{
    global $host, $user, $passwd, $database;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "SELECT com_purchase_order.no_cpo FROM com_purchase_order WHERE id_cpo='$id_cpo'";
    $shows = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($shows)) {
        $select_cpo = $row["no_cpo"];
    }
    mysqli_close($connect);
    return $select_cpo;
}
#################################################
function com_receive_inventory($id_cpo)
{
    global $host, $user, $passwd, $database;
    $connect = mysqli_connect($host, $user, $passwd, $database) or die("not connect database");
    $sql_query = "SELECT no_delivery_note,date_delivery_note,unit_quantity,unit_price,discounts,vat,real_price FROM com_receive_inventory WHERE id_cpo='$id_cpo'";
    print "$sql_query <br>";
    $query = mysqli_query($connect, $sql_query);
    while ($row = mysqli_fetch_array($query)) {
        $com_receive_inventory['no_delivery_note'] = $row["no_delivery_note"];
        $com_receive_inventory['date_delivery'] = $row["date_delivery"];
        $com_receive_inventory['unit_quantity'] = $row["unit_quantity"];
        $com_receive_inventory['unit_price'] = $row["unit_price"];
        $com_receive_inventory['vat'] = $row["vat"];
        $com_receive_inventory['discounts'] = $row["discounts"];
        $com_receive_inventory['real_price'] = $row["real_price"];
    }
    mysqli_close($connect);
    return $com_receive_inventory;
}
######################หน้าแสดง###########################
function com_purchase_order($select_data_cpo, $id_cpo, $com_receive_inventory, $position_active)
{
    global $dayTH, $monthTH, $monthTH_brev, $id_emp_use;

    $encode_id_cpo = base64_encode(base64_encode("$id_cpo"));
    if ($com_receive_inventory['no_delivery_note'] != "") {
        $no_delivery_note = $com_receive_inventory['no_delivery_note'];
    } else {
        $year_delivery_note = date('Y') + 543;
        $no_delivery_note = "$year_delivery_note" . "001";
    }

    if ($com_receive_inventory['date_delivery'] != "") {
        $date02 = $com_receive_inventory['date_delivery'];
    } else {
        $date02 = new DateTime(date_create('now')->format('Y-m-d'));
    }

    $dd02 = $date02->format('j');
    $mm02 = $monthTH[$date02->format('n')];
    $yy02 = $date02->format('Y') + 543;
    $day_date_cpo = "$dd02 $mm02 $yy02";

    $name_use = name_user($select_data_cpo['id_use']);
    $name_purchasing_jobs = name_user($select_data_cpo['id_purchasing_jobs']);
    $select_crm = select_crm($select_data_cpo['id_crm']);
    if ($com_receive_inventory['unit_quantity']) {
        $toral_price = $com_receive_inventory['unit_quantity'] * $com_receive_inventory['unit_price'];
        $total_price_number_format = number_format($toral_price, 2, '.', ',');
    } else {
        $total_price_number_format = "";
    }

    $com_purchase_order_report = "
  <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"60%\" align=\"center\">
          <h2>ใบรับสินค้า (Receive Inventory)</h2>
      </td>
      <td width=\"40%\" align=\"right\">
          <table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td width=\"50%\" align=\"right\"><strong>&nbsp; เลขที่เอกสาร RI.No &nbsp;&nbsp;</strong></td>
                  <td width=\"50%\" align=\"left\"><strong>&nbsp; &nbsp;&nbsp; $no_delivery_note</strong></td>
              </tr>
              <tr>
                  <td align=\"right\"><strong>&nbsp; วันที่เอกสาร RI.No &nbsp;&nbsp;</strong></td>
                  <td align=\"left\"><strong>&nbsp; &nbsp;&nbsp; $day_date_cpo</strong></td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"50%\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><strong>&nbsp; ผู้ขาย :</strong>&nbsp; $select_data_cpo[name_accounts_payable]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; ที่อยู่ :</strong>&nbsp; $select_data_cpo[address_accounts_payable]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; โทรศัพท์ :</strong>&nbsp; $select_data_cpo[tel_contact]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; E-mail :</strong>&nbsp; $select_data_cpo[email_contact]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; ชื่อผู้ติดต่อ :</strong>&nbsp; $select_data_cpo[contact_person_name]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; เลขที่ใบส่งของ :</strong>&nbsp; $com_receive_inventory[no_delivery_note]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; รหัสผู้ขาย :</strong>&nbsp; $select_data_cpo[code_cap]</td>
              </tr>

          </table>

      </td>
      <td width=\"50%\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><strong>&nbsp; เงื่อนไขการชำระเงิน :</strong>&nbsp; $select_data_cpo[payment_term]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; ชื่อผู้ขอซื้อ :</strong>&nbsp; $name_use[name]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; โทรศัพท์ :</strong>&nbsp; $name_use[tel]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; E-mail :</strong>&nbsp; $name_use[email]</td>
              </tr>
              <tr>
                  <td><strong>&nbsp; เลขที่ใบสั่งซื้อ (PO) :</strong>&nbsp; $select_data_cpo[no_cpo]</td>
              </tr>
          </table>
      </td>
  </tr>
</table>
<br><br>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>ลำดับ</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>รหัสสินค้า</B></td>
      <td width=\"30%\" align=\"center\" bgcolor=\"fefefe\"><B>รายละเอียด</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>หน่วย</B></td>
      <td width=\"10%\" align=\"center\" bgcolor=\"fefefe\"><B>ปริมาณ</B></td>
      <td width=\"15%\" align=\"center\" bgcolor=\"fefefe\"><B>ราคาต่อหน่วย</B></td>
      <td width=\"15%\" align=\"center\" bgcolor=\"fefefe\"><B>จำนวนเงิน</B></td>
  </tr>
  <tr>
      <td align=\"center\">1.</td>
      <td align=\"center\">$select_data_cpo[code_crmd]</td>
      <td>&nbsp;$select_data_cpo[name_raw_material_details]</td>
      <td align=\"center\">$select_data_cpo[unit]</td>
      <td align=\"center\">$com_receive_inventory[unit_quantity]</td>
      <td align=\"center\">$com_receive_inventory[unit_price]</td>
      <td align=\"right\">$total_price_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"center\"></td>
      <td align=\"center\"></td>
      <td>&nbsp;</td>
  </tr>
  <tr>
      <td align=\"left\" colspan=\"4\" rowspan=\"4\">
          <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
              <tr>
                  <td><b>จำนวนเงิน(ตัวอักษร)</b></td>
              </tr>
              <tr>
                  <td align=\"center\"><br><br>text_final_price</td>
              </tr>
          </table>
      </td>
      <td align=\"right\" colspan=\"2\"><b>จำนวนเงิน</b>&nbsp;&nbsp;</td>
      <td align=\"right\">$total_price_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>ส่วนลดสินค้า(เป็นเงิน)</b>&nbsp;&nbsp;</td>
      <td align=\"right\">discounts_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>ภาษีมูลค่าเพิ่ม</b>&nbsp;&nbsp;</td>
      <td align=\"right\">vat_number_format &nbsp;</td>
  </tr>
  <tr>
      <td align=\"right\" colspan=\"2\"><b>จำนวนเงินทั้งสิ้น</b>&nbsp;&nbsp;</td>
      <td align=\"right\">final_price_number_format &nbsp;</td>
  </tr>
</table>
<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tr align=\"center\">
      <td width=\"25%\"><br><br>.................................................<br>
          (&nbsp;&nbsp;$name_purchasing_jobs[name]&nbsp;&nbsp;)
          <br>ผู้จัดทำ</td>
      <td width=\"25%\"><br><br>.................................................<br>
      (.................................................)
          <br>ผู้ส่งของ</td>
      <td width=\"25%\"><br><br>.................................................<br>
          (&nbsp;&nbsp;$name_use[name]&nbsp;&nbsp;)
          <br>ผู้รับของ</td>
      <td width=\"25%\"><br><br>
          .................................................<br>
          (.................................................)
          <br>ผู้ตรวจสอบ
      </td>
</tr>
</table>
  ";
    //  $time=time();
    //  $thai_date_fullmonth=thai_date_fullmonth($time);
    print "
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
    print "

    <div class=\"wrapper\">
    <!-- Content Wrapper. Contains page content -->
    <div class=\"content-wrapper\">
        <!-- Content Header (Page header) -->
        <section class=\"content-header\">
            <h1>
                ใบรับสินค้า (Receive Inventory)
                <small>Version 1.0</small>
            </h1>
            <ol class=\"breadcrumb\">
                <li><a href=\"#\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
                <li class=\"active\">ใบรับสินค้า</li>
            </ol>
        </section>
        <!-- Info boxes -->
        <!-- /.row -->
        <!-- Main content -->
        <section class=\"content\">
        <form name='theForm' target='_parent' action='com_check_products.php' method='POST'>
            <div class=\"row\">
                <div class=\"col-xs-12\">
                    <div class=\"box\">
                        <div class=\"box-header\">
                            <h3 class=\"box-title\">ใบรับสินค้า (Receive Inventory) &nbsp;&nbsp;&nbsp; <strong>&nbsp;
                                    การขอสั่งซื้อ $select_crm</strong> </h3>&nbsp;&nbsp;&nbsp; $text_disabled
                        </div>
                        <!-- /.box-header -->

                        <div class=\"box-body\">
                            <div class=\"row\">
                                <div class=\"col-md-12\">

                                    $com_purchase_order_report
                                </div>
                                <div class=\"col-md-2\">
                                </div>
                                <div class=\"col-md-10\">
                                    <div class=\"box-body\">

                                        <div class=\"box-body\">
                                            <!-- //เลขที่ใบเสนอราคา -->
                                            <div class=\"row\">
                                                <div class=\"col-md-6\">
                                                    <div class=\"form-group\">
                                                        <label>เลขที่ใบส่งของ :</label>
                                                        <input type=\"text\" class=\"form-control\"
                                                            NAME='no_delivery_note' id='no_delivery_note'
                                                            value='$com_receive_inventory[no_delivery_note]'
                                                            placeholder=\"เลขที่ใบส่งของ\">
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        </div><!-- /.box-body -->
                                        <div class=\"box-body\">
                                            <!-- //ราคาต่อหน่วย/กิโลกรัม -->
                                            <div class=\"row\">
                                                <div class=\"col-md-6\">
                                                    <div class=\"form-group\">
                                                        <label>ปริมาณ :</label>
                                                        <input type=\"text\" class=\"form-control\" NAME='unit_quantity'
                                                            id='unit_quantity' value='$com_receive_inventory[unit_quantity]'
                                                            placeholder=\"ชิ้น/กิโลกรัม\">
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        </div><!-- /.box-body -->

                                        <div class=\"box-body\">
                                            <!-- //ราคาต่อหน่วย/กิโลกรัม -->
                                            <div class=\"row\">
                                                <div class=\"col-md-6\">
                                                    <div class=\"form-group\">
                                                        <label>ราคาต่อหน่วย/กิโลกรัม :</label>
                                                        <input type=\"text\" class=\"form-control\" NAME='unit_price'
                                                            id='unit_price' value='$com_receive_inventory[unit_price]'
                                                            placeholder=\"ระบุราคา(บาท)\">
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        </div><!-- /.box-body -->

                                    </div><!-- /.box-body -->
                                    <div class=\"box-body\">
                                        <!-- //ส่วนลดสินค้า(คิดเป็นเงิน) -->
                                        <div class=\"row\">
                                            <div class=\"col-md-6\">
                                                <div class=\"form-group\">
                                                    <label>ส่วนลดสินค้า(คิดเป็นเงิน) :</label>
                                                    <input type=\"text\" class=\"form-control\" NAME='discounts'
                                                        id='discounts' value='$com_receive_inventory[discounts]'
                                                        placeholder=\"ระบุส่วนลดสินค้า(บาท)\">
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.box-body -->

                                    <div class=\"box-body\">
                                        <!-- //ภาษีมูลค่าเพิ่ม -->
                                        <div class=\"row\">
                                            <div class=\"col-md-6\">
                                                <div class=\"form-group\">
                                                    <label>ภาษีมูลค่าเพิ่ม :</label>
                                                    <input type=\"text\" class=\"form-control\" NAME='vat' id='vat'
                                                        value='$com_receive_inventory[vat]'
                                                        placeholder=\"ระบุภาษีมูลค่าเพิ่ม(บาท)\">
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.box-body -->
                                    <div class=\"box-body\">
                                    <!-- //จำนวนเงินทั้งสิ้น -->
                                    <div class=\"row\">
                                        <div class=\"col-md-6\">
                                            <div class=\"form-group\">
                                                <label>จำนวนเงินทั้งสิ้น :</label>
                                                <input type=\"text\" class=\"form-control\" NAME='real_price' id='real_price'
                                                    value='$com_receive_inventory[real_price]'
                                                    placeholder=\"จำนวนเงินทั้งสิ้น\">
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
                                    <input type='hidden' name='id_cpo' value='$encode_id_cpo'>
                                    <input type=\"submit\" VALUE='Submit' name='add_com_receive_inventory' class=\"btn btn-primary\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <INPUT TYPE='reset' class=\"btn btn-default\" VALUE='<<Back'
                                        ONCLICK='window.history.back();'>
                                </div>
                            </div>
                        </div><!-- End Row-->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
    </div><!-- /.row -->
    </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
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
function delete_com_raw_material($select_com_raw_material, $id_crmd)
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