<?php
require_once('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "../../vendor/autoload.php"; //PHPMailer Object  "F:\xampp\phpMyAdmin\vendor\autoload.php"

Class Master extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    public function __destruct(){
        parent::__destruct();
    }
    function capture_err(){
        if(!$this->conn->error)
            return false;
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
            exit;
        }
    }
    function save_supplier(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `suppliers` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "supplier Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `suppliers` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `suppliers` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Supplier successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Supplier successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_supplier(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `suppliers` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Supplier successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_role(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `role` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Role Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `role` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `role` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Role successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Role successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_role(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `role` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Role successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_item(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                $v = $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `items` where `name` = '{$name}' and `supplier_id` = '{$supplier_id}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Item already exists under selected supplier.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `items` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `items` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $this->settings->set_flashdata('success',"New Item successfully saved.");
            else
                $this->settings->set_flashdata('success',"Item successfully updated.");
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_item(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `items` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Item  successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_po(){
        if(empty($_POST['id'])){
            $prefix = "PO";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `purchase_orders` where po_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['po_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }
        if(empty($id)){
            $sql = "INSERT INTO `purchase_orders` set {$data}";
        }else{
            $sql = "UPDATE `purchase_orders` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $po_id = $this->conn->insert_id;
            else
                $po_id = $id;
            $resp['id'] = $po_id;
            $data = "";
            foreach($item_id as $k =>$v){
                if(!empty($data)) $data .=", ";
                $data .= "('{$po_id}','{$v}','{$qty[$k]}','{$unit[$k]}')";
            }
            if(!empty($data)){
                $this->conn->query("DELETE FROM `po_items` where po_id = '{$po_id}'");
                $save = $this->conn->query("INSERT INTO `po_items` (`po_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
                if(!$save){
                    $resp['status'] = 'failed';
                    if(empty($id)){
                        $this->conn->query("DELETE FROM `purchase_orders` where id '{$po_id}'");
                    }
                    $resp['msg'] = 'PO has failed to save. Error: '.$this->conn->error;
                    $resp['sql'] = "INSERT INTO `po_items` (`po_id`,`item_id`,`quantity`,`unit`) VALUES {$data}";
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Purchase Order was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Purchase Order's Details Successfully updated.");
            }
        }

        return json_encode($resp);
    }
    function delete_po(){
        extract($_POST);
        $bo = $this->conn->query("SELECT * FROM back_orders where po_id = '{$id}'");
        $del = $this->conn->query("DELETE FROM `purchase_orders` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"po's Details Successfully deleted.");
            if($bo->num_rows > 0){
                $bo_res = $bo->fetch_all(MYSQLI_ASSOC);
                $r_ids = array_column($bo_res, 'receiving_id');
                $bo_ids = array_column($bo_res, 'id');
            }
            $qry = $this->conn->query("SELECT * FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )." ");
            while($row = $qry->fetch_assoc()){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$row['stock_ids']}) ");
                // echo "DELETE FROM `stock_list` where id in ({$row['stock_ids']}) </br>";
            }
            $this->conn->query("DELETE FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )." ");
            // echo "DELETE FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )."  </br>";
            // exit;
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_receiving(){
        if(empty($_POST['id'])){
            $prefix = "BO";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `back_orders` where bo_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['bo_code'] = $prefix."-".$code;
        }else{
            $get = $this->conn->query("SELECT * FROM back_orders where receiving_id = '{$_POST['id']}' ");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                $bo_id = $res['id'];
                $_POST['bo_code'] = $res['bo_code'];
            }else{

                $prefix = "BO";
                $code = sprintf("%'.04d",1);
                while(true){
                    $check_code = $this->conn->query("SELECT * FROM `back_orders` where bo_code ='".$prefix.'-'.$code."' ")->num_rows;
                    if($check_code > 0){
                        $code = sprintf("%'.04d",$code+1);
                    }else{
                        break;
                    }
                }
                $_POST['bo_code'] = $prefix."-".$code;

            }
        }

        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id','bo_code','supplier_id','po_id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        if(empty($id)){
            $sql = "INSERT INTO `receivings` set {$data}";
        }else{
            $sql = "UPDATE `receivings` set {$data} where id = '{$id}'";
        }

        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $r_id = $this->conn->insert_id;
            else
                $r_id = $id;
            $resp['id'] = $r_id;
            if(!empty($id)){
                $stock_ids = $this->conn->query("SELECT stock_ids FROM `receivings` where id = '{$id}'")->fetch_array()['stock_ids'];
                $this->conn->query("DELETE FROM `stock_list` where id in ({$stock_ids})");
            }
            $stock_ids= array();
            foreach($item_id as $k =>$v){
                if(!empty($data)) $data .=", ";
                $sql = "INSERT INTO stock_list (`item_id`,`quantity`,`expiry_date`,`unit`,`type`) VALUES ('{$v}','{$qty[$k]}','{$expiry_date[$k]}','{$unit[$k]}','1')";
                $this->conn->query($sql);
                $stock_ids[] = $this->conn->insert_id;
                if($qty[$k] < $oqty[$k]){
                    $bo_ids[] = $k;
                }
            }
            if(count($stock_ids) > 0){
                $stock_ids = implode(',',$stock_ids);
                $this->conn->query("UPDATE `receivings` set stock_ids = '{$stock_ids}' where id = '{$r_id}'");
            }
            if(isset($bo_ids)){
                $this->conn->query("UPDATE `purchase_orders` set status = 1 where id = '{$po_id}'");
                if($from_order == 2){
                    $this->conn->query("UPDATE `back_orders` set status = 1 where id = '{$form_id}'");
                }
                if(!isset($bo_id)){
                    $sql = "INSERT INTO `back_orders` set 
							bo_code = '{$bo_code}',	
							receiving_id = '{$r_id}',	
							po_id = '{$po_id}',	
							supplier_id = '{$supplier_id}'
						";
                }else{
                    $sql = "UPDATE `back_orders` set 
							receiving_id = '{$r_id}',	
							po_id = '{$form_id}',	
							supplier_id = '{$supplier_id}',	
							where bo_id = '{$bo_id}'
						";
                }
                $bo_save = $this->conn->query($sql);
                if(!isset($bo_id))
                    $bo_id = $this->conn->insert_id;
                $data = "";
                foreach($item_id as $k =>$v){
                    if(!in_array($k,$bo_ids))
                        continue;

                    if(!empty($data)) $data.= ", ";
                    $data .= " ('{$bo_id}','{$v}','".($oqty[$k] - $qty[$k])."','{$unit[$k]}') ";
                }
                $this->conn->query("DELETE FROM `bo_items` where bo_id='{$bo_id}'");
                $save_bo_items = $this->conn->query("INSERT INTO `bo_items` (`bo_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
//                if($save_bo_items){
//
//                    $amount = 0;
//                    $this->conn->query("UPDATE back_orders set amount = '{$amount}' where id = '{$bo_id}'");
//                }

            }else{
                $this->conn->query("UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'");
                if($from_order == 2){
                    $this->conn->query("UPDATE `back_orders` set status = 2 where id = '{$form_id}'");
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }



        /////////////////////////////////////


        if(isset($po_id)):
            if(!isset($bo_id))
                $qry = $this->conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$po_id}'");
            else
                $qry = $this->conn->query("SELECT b.*,i.name,i.description FROM `bo_items` b inner join items i on b.item_id = i.id where b.bo_id = '{$bo_id}'");
            while($row = $qry->fetch_assoc()):
                $row['qty'] = $row['quantity'];


                $qry1 = $this->conn->query("SELECT stock_ids FROM `receivings` where form_id= '{$po_id}'");
                $tqty = 0;
                while($row1 = $qry1->fetch_assoc()):

                    $qry2 = $this->conn->query("SELECT * FROM stock_list where id in (".$row1['stock_ids'].") and item_id = ".$row['item_id']);
                    while($row2 = $qry2->fetch_assoc()):

                        $tqty = $tqty + $row2['quantity'];
                    endwhile;
                endwhile;

                if ($row['quantity']== $tqty){
                    //echo "UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'";
//                  //  exit();
                    $this->conn->query("UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'");
                }

            endwhile;
        endif;


        ///////////////////////////////////////////

        if ($resp['status'] == 'success') {
            if (empty($id)) {
                $this->settings->set_flashdata('success', "New Stock was Successfully received.");
            } else {
                $this->settings->set_flashdata('success', "Received Stock's Details Successfully updated.");
            }

            // Check and update PO status
            if (isset($po_id)) {
                $qry = $this->conn->query("SELECT p.*, i.name, i.description FROM `po_items` p INNER JOIN items i ON p.item_id = i.id WHERE p.po_id = '{$po_id}'");
                $po_fully_received = true; // Assume all items in the PO are fully received

                while ($row = $qry->fetch_assoc()) {
                    $qry1 = $this->conn->query("SELECT stock_ids FROM `receivings` WHERE form_id = '{$po_id}'");
                    $total_ordered_qty = $row['quantity'];
                    $total_received_qty = 0;

                    while ($row1 = $qry1->fetch_assoc()) {
                        $qry2 = $this->conn->query("SELECT * FROM stock_list WHERE id IN (" . $row1['stock_ids'] . ") AND item_id = " . $row['item_id']);

                        while ($row2 = $qry2->fetch_assoc()) {
                            $total_received_qty += $row2['quantity'];
                        }
                    }

                    if ($total_received_qty < $total_ordered_qty) {
                        $po_fully_received = false; // At least one item is not fully received
                        break;
                    }
                }

                // Update the PO status based on whether all items are fully received or not
                $po_status = $po_fully_received ? 2 : 1;
                $this->conn->query("UPDATE `purchase_orders` SET status = '{$po_status}' WHERE id = '{$po_id}'");
            }
        }
        return json_encode($resp);
    }

    function delete_receiving(){
        extract($_POST);
        $qry = $this->conn->query("SELECT * from  receivings where id='{$id}' ");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $ids = $res['stock_ids'];
        }
        if(isset($ids) && !empty($ids))
            $this->conn->query("DELETE FROM stock_list where id in ($ids) ");
        $del = $this->conn->query("DELETE FROM receivings where id='{$id}' ");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Received Order's Details Successfully deleted.");

            if(isset($res)){
                if($res['from_order'] == 1){
                    $this->conn->query("UPDATE purchase_orders set status = 0 where id = '{$res['form_id']}' ");
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function delete_bo(){
        extract($_POST);
        $bo =$this->conn->query("SELECT * FROM `back_orders` where id = '{$id}'");
        if($bo->num_rows >0)
            $bo_res = $bo->fetch_array();
        $del = $this->conn->query("DELETE FROM `back_orders` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"po's Details Successfully deleted.");
            $qry = $this->conn->query("SELECT `stock_ids` from  receivings where form_id='{$id}' and from_order = '2' ");
            if($qry->num_rows > 0){
                $res = $qry->fetch_array();
                $ids = $res['stock_ids'];
                $this->conn->query("DELETE FROM stock_list where id in ($ids) ");

                $this->conn->query("DELETE FROM receivings where form_id='{$id}' and from_order = '2' ");
            }
            if(isset($bo_res)){
                $check = $this->conn->query("SELECT * FROM `receivings` where from_order = 1 and form_id = '{$bo_res['po_id']}' ");
                if($check->num_rows > 0){
                    $this->conn->query("UPDATE `purchase_orders` set status = 1 where id = '{$bo_res['po_id']}' ");
                }else{
                    $this->conn->query("UPDATE `purchase_orders` set status = 0 where id = '{$bo_res['po_id']}' ");
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    //////////////
    function save_return(){

        if(empty($_POST['id'])){
            $prefix = "R";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `return_list` where return_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['return_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        ////////////get alert manager's profile ids
        $qry = $this->conn->query("SELECT pa.profileid FROM `alert` a inner join profile_alert pa on a.id=pa.alertid where approval_for = 'purchasereturns'");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $pid = $res['profileid'];
        }
        /////////////

        if(empty($id)){
            $sql = "INSERT INTO `return_list` set {$data}";
        }else{
            $sql = "UPDATE `return_list` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);

        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $return_id = $this->conn->insert_id;
            else
                $return_id = $id;
            $resp['id'] = $return_id;
            $data = "";
            $sids = array();
            $get = $this->conn->query("SELECT * FROM `return_list` where id = '{$return_id}'");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                if(!empty($res['stock_ids'])){

                    $sqlt = base64_encode("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                    $sql = "INSERT INTO `messages` set profileid='{$pid}', `title` = 'Approval for return return id - ''{$return_id}', `message` = '".$sqlt."',`type` = 'insert',`status` = '0', `role_name` = 'returns', `event_id` = '{$return_id}'";
                    $save = $this->conn->query($sql);
                    /////////////
                    //$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                }
            }
            foreach($item_id as $k =>$v){

                /////////////
                $sqlt = base64_encode("INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 2 ");
                $sql = "INSERT INTO `messages` set profileid='".$pid."', `title` = 'Approval for return return id - ''{$return_id}', `message` = '".$sqlt."',`type` = 'delete',`status` = '0', `role_name` = 'returns', `event_id` = '{$return_id}'";
                $save = $this->conn->query($sql);
                /////////////
                //$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 2 ";
                //$save = $this->conn->query($sql);
                if($save){
                    $sids[] = $this->conn->insert_id;
                }
            }
            $sids = implode(',',$sids);
            $this->conn->query("UPDATE `return_list` set stock_ids = '{$sids}' where id = '{$return_id}'");
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Returned Item Record's Successfully updated.");
            }

            ////////////////////////////////////////////////////

            $mail = new PHPMailer(true);
//C:\xampp\htdocs
            try {
                //Server settings
                $mail->SMTPDebug = 0;                     //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'mudithasamarasinghe08@gmail.com';                     //SMTP username
                $mail->Password   = 'dscklkgchdvejrqr';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('mudithasamarasinghe08@gmail.com', 'MEDIX');
                $mail->addAddress('mudithasamarasinghe08@gmail.com');     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Medix Purchase Return Notification';
                $mail->Body    = 'Hello,<br><br>
        This is a system generated notification. Do not reply to this mail.<br> You have a pending approval for return id <b>$return_id</b>.<br><br>
        
        Please evaluate the return request. <br>
        <br><b>Regards,</b><br>Medix ';

                $mail->send();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            //////////////////////////////////////////////////
        }

        return json_encode($resp);
    }
    //
function approve_return(){

    extract($_POST);

    $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}'");
    if($get->num_rows > 0){

        while($row = $get->fetch_assoc()):
            if ($row['type']=="delete" ) {
                $del = $this->conn->query("\"".base64_decode($row['message'])."\"");
                $resp['status'] = 'success';
            }  else {
                $sql = "\"".base64_decode($row['message'])."\"";
                $save = $this->conn->query($sql);
                $resp['status'] = 'success';
            }
            $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}'");
        endwhile;
        $this->conn->query("UPDATE `return_list` set return_approval = '{$_SESSION['userdata']['id']}' ,date_approval = now() where id = '{$id}'");
        $resp['status'] = 'success';
    } else {
        $resp['status'] = 'failed';
    }
    if($resp['status'] == 'success'){
        if(empty($id)){
            $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
        }else{
            $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
        }
    }
    return json_encode($resp);

}
    //
    function delete_return(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM return_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `return_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Returned Item Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_sale(){
        if(empty($_POST['id'])){
            $prefix = "SALE";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `sales_list` where sales_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['sales_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }
        if(empty($id)){
            $sql = "INSERT INTO `sales_list` set {$data}";
        }else{
            $sql = "UPDATE `sales_list` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $sale_id = $this->conn->insert_id;
            else
                $sale_id = $id;
            $resp['id'] = $sale_id;
            $data = "";
            $sids = array();
            $get = $this->conn->query("SELECT * FROM `sales_list` where id = '{$sale_id}'");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                if(!empty($res['stock_ids'])){
                    $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                }
            }
            //////////////added for alert on 26/07/2023
            $qry = $this->conn->query("SELECT pa.profileid FROM `alert` a inner join profile_alert pa on a.id=pa.alertid where approval_for = 'purchasereturns'");
            if($qry->num_rows > 0){
                $res = $qry->fetch_array();
                $pid = $res['profileid'];
            }
            $ij = 0;
            /////////////
            foreach($item_id as $k =>$v){
                $sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}', `type` = 2 ";
                if ($ij == 0 ) {
                    $sql = "INSERT INTO `messages` set profileid='{$pid}', `title` = 'Approval for return', `message` = 'Approval for return',`status` = '1'";
                    $save = $this->conn->query($sql);
                    if($save){
                        $sids = $this->conn->insert_id;
                    }
//                    $sids = implode(',',$sids);
                    $ij=1;
                }

                $save = $this->conn->query($sql);
                if($save){
                    $sids = $this->conn->insert_id;
                }

            }
//            $sids = implode(',',$sids);
            $this->conn->query("UPDATE `sales_list` set stock_ids = '{$sids}' where id = '{$sale_id}'");
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Sales Record was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Sales Record's Successfully updated.");
            }
        }

        return json_encode($resp);
    }
    function delete_sale(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM sales_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `sales_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Sales Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }

    function save_profile(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `admin_profiles` where `profile_name` = '{$profile_name}' ".(!empty($id) ? " and profile_id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Profile Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `admin_profiles` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `admin_profiles` set {$data} where profile_id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Profile successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Profile successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);

    }
    function delete_profile(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `admin_profiles` where profile_id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Profile successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_rtop()
    {
        if( isset( $_POST['myCheckboxes'] ))
        {
            $del = $this->conn->query("DELETE FROM `profile_role` where profileid = '{$_POST['id']}'");
            $check_code = $this->conn->query("SELECT max(id) FROM `profile_role`")->num_rows;
            if($check_code == 0){
                $seq = 1;
            }else{
                $seq=$check_code+1;
            }
            for ( $i=0; $i < count($_POST['myCheckboxes'] ); $i++ )
            {

                $sql = "INSERT INTO `profile_role` set `id`='{$seq}', `profileid` = '{$_POST['id']}', `roleid` = '{$_POST['myCheckboxes'][$i]}', `status` = '1'";
                $save = $this->conn->query($sql);
                $seq=$seq+1;
            }
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success'," Profile roles Successfully updated.");
        }
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        header('location:../admin/index.php?page=maintenance/userprofile');
        return json_encode($resp);
    }
    ////
    function save_atop()
    {
        if( isset( $_POST['myCheckboxes'] ))
        {
            $del = $this->conn->query("DELETE FROM `profile_alert` where profileid = '{$_POST['id']}'");
            $check_code = $this->conn->query("SELECT max(id) FROM `profile_alert`")->num_rows;
            if($check_code == 0){
                $seq = 1;
            }else{
                $seq=$check_code+1;
            }
            for ( $i=0; $i < count($_POST['myCheckboxes'] ); $i++ )
            {

                $sql = "INSERT INTO `profile_alert` set `id`='{$seq}', `profileid` = '{$_POST['id']}', `alertid` = '{$_POST['myCheckboxes'][$i]}', `status` = '1'";
                $save = $this->conn->query($sql);
                $seq=$seq+1;
            }
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success'," Profile alerts Successfully updated.");
        }
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        header('location:../admin/index.php?page=maintenance/userprofile');
        return json_encode($resp);
    }
    function save_alert(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `alert` where `approval_for` = '{$approval_for}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Alert Name already exist.";
            return json_encode($resp);
            exit;
        }

        if(empty($id)){
            $sql = "INSERT INTO `alert` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `alert` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Alert successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Alert successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_alert(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM profile_alert where alertid = '{$id}'");
        if($get->num_rows > 0){
            $del = $this->conn->query("DELETE FROM `profile_alert` where alertid = '{$id}'");
        }
        $dela = $this->conn->query("DELETE FROM `alert` where id = '{$id}'");

        if($dela){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Alert Record Successfully deleted.");

        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function expiry_return(){

        extract($_POST);

        $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}'");
        if($get->num_rows > 0){

            while($row = $get->fetch_assoc()):
                $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}'");
            endwhile;
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
            }else{
                $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
            }
        }
        return json_encode($resp);

    }
    function mlcheck_return(){

        extract($_POST);

        $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}'");
        if($get->num_rows > 0){

            while($row = $get->fetch_assoc()):
                $this->conn->query("UPDATE `messages` set status = '0' where event_id = '{$id}'");
            endwhile;
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
            }else{
                $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
            }
        }
        return json_encode($resp);

    }
    function save_disposal(){
        if(empty($_POST['id'])){
            $prefix = "D";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `disposal` where disposal_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['disposal_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        ////////////get alert manager's profile ids
        $qry = $this->conn->query("SELECT pa.profileid FROM `alert` a inner join profile_alert pa on a.id=pa.alertid where approval_for = 'disposals'");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $pid = $res['profileid'];
        }
        /////////////

        if(empty($id)){
            $sql = "INSERT INTO `disposal` set {$data}";
        }else{
            $sql = "UPDATE `disposal` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);

        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $disposal_id = $this->conn->insert_id;
            else
                $disposal_id = $id;
            $resp['id'] = $disposal_id;
            $data = "";
            $sids = array();
            $get = $this->conn->query("SELECT * FROM `disposal` where id = '{$disposal_id}'");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                if(!empty($res['stock_ids'])){

                    $sqlt = base64_encode("DELETE FROM `disposal` where id in ({$res['stock_ids']}) ");
                    $sql = "INSERT INTO `messages` set profileid='{$pid}', `title` = 'Approval for disposals id - ''{$disposal_id}', `message` = '".$sqlt."',`type` = 'insert',`status` = '0', `role_name` = 'disposals', `event_id` = '{$disposal_id}'";
                    $save = $this->conn->query($sql);
                    /////////////
                    //$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                }
            }
            foreach($item_id as $k =>$v){

                /////////////
                $sqlt = base64_encode("INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 2 ");
                $sql = "INSERT INTO `messages` set profileid='".$pid."', `title` = 'Approval for disposal disposal id - ''{$disposal_id}', `message` = '".$sqlt."',`type` = 'delete',`status` = '0', `role_name` = 'disposals', `event_id` = '{$disposal_id}'";
                $save = $this->conn->query($sql);
                /////////////
                //$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 2 ";
                //$save = $this->conn->query($sql);
                if($save){
                    $sids[] = $this->conn->insert_id;
                }
            }
            $sids = implode(',',$sids);
            $this->conn->query("UPDATE `disposal_list` set stock_ids = '{$sids}' where id = '{$disposal_id}'");
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Disposed Item Record was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Disposed Item Record's Successfully updated.");
            }
        }

        return json_encode($resp);
    }
    function delete_disposal(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM return_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `return_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Returned Item Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'save_supplier':
        echo $Master->save_supplier();
        break;
    case 'delete_supplier':
        echo $Master->delete_supplier();
        break;
    case 'save_item':
        echo $Master->save_item();
        break;
    case 'delete_item':
        echo $Master->delete_item();
        break;
    case 'get_item':
        echo $Master->get_item();
        break;
    case 'save_po':
        echo $Master->save_po();
        break;
    case 'delete_po':
        echo $Master->delete_po();
        break;
    case 'save_receiving':
        echo $Master->save_receiving();
        break;
    case 'delete_receiving':
        echo $Master->delete_receiving();
        break;
    case 'save_return':
        echo $Master->save_return();
        break;
    case 'delete_return':
        echo $Master->delete_return();
        break;
    case 'approve_return':
        echo $Master->approve_return();
        break;
    case 'save_sale':
        echo $Master->save_sale();
        break;
    case 'delete_sale':
        echo $Master->delete_sale();
        break;
    case 'save_rtop':
        echo $Master->save_rtop();
        break;
    case 'save_atop':
        echo $Master->save_atop();
        break;
    case 'save_role':
        echo $Master->save_role();
        break;
    case 'delete_role':
        echo $Master->delete_role();
        break;
    case 'expiry_return':
        echo $Master->expiry_return();
        break;
    case 'mlcheck_return':
        echo $Master->mlcheck_return();
        break;
    case 'save_profile':
        echo $Master->save_profile();
        break;
    case 'delete_profile':
        echo $Master->delete_profile();
        break;
    case 'save_disposal':
        echo $Master->save_disposal();
        break;
    case 'delete_disposal':
        echo $Master->delete_disposal();
        break;
    case 'save_alert':
        echo $Master->save_alert();
        break;
    case 'delete_alert':
        echo $Master->delete_alert();
        break;
    default:
        // echo $sysset->index();
        break;
}

