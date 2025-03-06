<?php
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as RTTCore;
use classes\myOrder as myOrder;
use classes\myOrderDetail as myOrderDetail;
use classes\myPaymentMode as myPaymentMode;
use classes\myPaymentDetail as myPaymentDetail;
use classes\myShippingMode as myShippingMode;
use classes\myPlace as myPlace;
use classes\xLang as xLang;
use classes\myUser as myUser;
use classes\myArticle as myArticle;

class Order extends Module
{
    public static function form($args)
    {
        global $langs,$actions;
        //var_dump($langs);
        myOrder::init();
        myOrderDetail::init();
        myPaymentDetail::init();
        
        $record      = array();
        $messages    = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id     = 0;
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record  = (myOrder::getById($list_id) !== null)?myOrder::getById($list_id)[0]:array();
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            $messages[$btnType] = 'Success';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            $list_id = myOrder::save();
            $record  = myOrder::getById($list_id)[0];
            $btnType = 'Edit';
            $query   = Tools::newURL("id",$list_id);
            Tools::refresh(3,"index.php?$query");
            //echo $list_id;
        }
        if (Tools::isSubmit('btnSubmitEdit')){
            /*
            $ret.= '<div class="msg">';
            $ret.= '    Action "Edit" was successful';
            $ret.= '</div>';
            */
            $messages[$btnType] = 'Success';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            
            //echo $_POST["problem_summary"];
            $_POST["date_add"] = !empty($record)?$record["date_add"]:$currentDate;
            $_POST["date_upd"] = $currentDate;
            myOrder::updateById(Tools::getValue('id'));
            $record = myOrder::getById(Tools::getValue('id'))[0];
            //Tools::refresh();
        }
        if (Tools::isSubmit('btnSubmitDelOrderDetail')){
            if(isset($_POST['arrid_order_detail'])){
                //$ret.= '<div class="msg">';
                $messages[$btnType] = '   Action "Delete Detail Order" was successful';
                //$ret.= '</div>';
                $indRow = 0;
                
                //var_dump($_POST['arrid_task']);
                foreach($_POST['arrid_order_detail'] as $id_record)
                {
                    myOrderDetail::deleteByWhere('`id_order_detail`='.$id_record);
                    $indRow++;
                }
                //$recordsTask = myTask::getByField('id_task_group',$list_id);
                //$ret.= '</div>';
                $ret.= '<script type="text/javascript">';
                $ret.= 'var currentSubTab = \'iddetailtab\';';
                $ret.= '</script>';
        
            }
        }
        if (Tools::isSubmit('btnSubmitAddOrderDetail')){
            if(isset($_POST['arrid_order_detail'])){
                //$ret.= '<div class="msg">';
                $messages[$btnType] = '   Action "Add Task Report" was successful';
                //$ret.= '</div>';
                $indRow = 0;
                
                //var_dump($_POST['arrid_task']);
                foreach($_POST['arrid_order_detail'] as $id_record)
                {
                    //$ret.= '<br/>'.$id_record .' Equipment: ' . $_POST['arrorder_quantity'][$indRow];
                    if ($id_record == 0){
                        $_POST['id_order_detail']       = $_POST['arrid_order_detail'][$indRow];
                        $_POST['id_customer']           = $_POST['arrid_customer'][$indRow];
                        $_POST['id_order_list']         = $list_id;
                        $_POST['code']                  = $_POST['arrcode'][$indRow];
                        $_POST['title']                 = $_POST['arrtitle'][$indRow];
                        $_POST['order_quantity']        = $_POST['arrorder_quantity'][$indRow];
                        $_POST['sale_unit_price']       = $_POST['arrsale_unit_price'][$indRow];
                        $_POST['sale_discount_percent'] = $_POST['arrsale_discount_percent'][$indRow];
                        $_POST['sale_discount_amount']  = $_POST['arrsale_discount_amount'][$indRow];
                        $_POST['sale_amount_cost']      = $_POST['arrsale_amount_cost'][$indRow];
                        $_POST['date_add']            = $currentDate;
                        $_POST['date_upd']            = $currentDate;
                        
                        //'suggestion','message_error','messure','customer_criteria','result','note'
                        myOrderDetail::save();
                    }elseif ($id_record >= 1){
                        $_POST['id_order_detail']       = $_POST['arrid_order_detail'][$indRow];
                        $_POST['id_customer']           = $_POST['arrid_customer'][$indRow];
                        $_POST['id_order_list']         = $list_id;
                        $_POST['code']                  = $_POST['arrcode'][$indRow];
                        $_POST['title']                 = $_POST['arrtitle'][$indRow];
                        $_POST['order_quantity']        = $_POST['arrorder_quantity'][$indRow];
                        $_POST['sale_unit_price']       = $_POST['arrsale_unit_price'][$indRow];
                        $_POST['sale_discount_percent'] = $_POST['arrsale_discount_percent'][$indRow];
                        $_POST['sale_discount_amount']  = $_POST['arrsale_discount_amount'][$indRow];
                        $_POST['sale_amount_cost']      = $_POST['arrsale_amount_cost'][$indRow];
                        $_POST['date_add']            = $currentDate;
                        $_POST['date_upd']            = $currentDate;
                        $fields = array('code','title','order_quantity','sale_unit_price','sale_discount_percent','sale_discount_amount','sale_amount_cost','date_upd');
                        //$fields = array('description','estimated_time','real_time','suggestion','message_error','messure','customer_criteria','result','note','date_upd');
                        myOrderDetail::updateByFields($id_record,$fields);
                    }
                    $indRow++;
                }
                //$recordsTask = myTask::getByField('id_task_group',$list_id);
                //$ret.= '</div>';
                $ret.= '<script type="text/javascript">';
                $ret.= 'var currentSubTab = \'iddetailtab\';';
                $ret.= '</script>';
        
            }
        }
        
        if (Tools::isSubmit('btnSubmitDelPaymentDetail')){
            if(isset($_POST['arrid_payment_detail'])){
                //$ret.= '<div class="msg">';
                $messages[$btnType] = '   Action "Delete Detail Payment" was successful';
                //$ret.= '</div>';
                $indRow = 0;
                
                //var_dump($_POST['arrid_task']);
                foreach($_POST['arrid_payment_detail'] as $id_record)
                {
                    myPaymentDetail::deleteByWhere('`id_payment_detail`='.$id_record);
                    $indRow++;
                }
                //$recordsTask = myTask::getByField('id_task_group',$list_id);
                //$ret.= '</div>';
                $ret.= '<script type="text/javascript">';
                $ret.= 'var currentSubTab = \'idpaymenttab\';';
                $ret.= '</script>';
        
            }
        }
        if (Tools::isSubmit('btnSubmitAddPaymentDetail')){
            if(isset($_POST['arrid_payment_detail'])){
                //$ret.= '<div class="msg">';
                $messages[$btnType] = '   Action "Add Task Report" was successful';
                //$ret.= '</div>';
                $indRow = 0;
                
                //var_dump($_POST['arrid_task']);
                foreach($_POST['arrid_payment_detail'] as $id_record)
                {
                    //$ret.= '<br/>'.$id_task_report .' Equipment: ' . $_POST['arrestimated_time'];
                    if ($id_record == 0){
                        $_POST['id_payment_detail']     = $_POST['arrid_payment_detail'][$indRow];
                        $_POST['id_customer']           = $_POST['arrid_customer'][$indRow];
                        $_POST['id_order_list']         = $list_id;
                        $_POST['code']                  = $_POST['arrcode'][$indRow];
                        $_POST['title']                 = $_POST['arrtitle'][$indRow];
                        $_POST['order_quantity']        = $_POST['arrorder_quantity'][$indRow];
                        $_POST['sale_unit_price']       = $_POST['arrsale_unit_price'][$indRow];
                        $_POST['sale_discount_percent'] = $_POST['arrsale_discount_percent'][$indRow];
                        $_POST['sale_discount_amount']  = $_POST['arrsale_discount_amount'][$indRow];
                        $_POST['sale_amount_cost']      = $_POST['arrsale_amount_cost'][$indRow];
                        $_POST['date_add']              = $currentDate;
                        $_POST['date_upd']              = $currentDate;
                        
                        //'suggestion','message_error','messure','customer_criteria','result','note'
                        myPaymentDetail::save();
                    }elseif ($id_record >= 1){
                        $_POST['id_payment_detail']     = $_POST['arrid_payment_detail'][$indRow];
                        $_POST['id_customer']           = $_POST['arrid_customer'][$indRow];
                        $_POST['id_order_list']         = $list_id;
                        $_POST['code']                  = $_POST['arrcode'][$indRow];
                        $_POST['title']                 = $_POST['arrtitle'][$indRow];
                        $_POST['order_quantity']        = $_POST['arrorder_quantity'][$indRow];
                        $_POST['sale_unit_price']       = $_POST['arrsale_unit_price'][$indRow];
                        $_POST['sale_discount_percent'] = $_POST['arrsale_discount_percent'][$indRow];
                        $_POST['sale_discount_amount']  = $_POST['arrsale_discount_amount'][$indRow];
                        $_POST['sale_amount_cost']      = $_POST['arrsale_amount_cost'][$indRow];
                        $_POST['date_add']              = $currentDate;
                        $_POST['date_upd']              = $currentDate;
                        $fields = array('code','title','order_quantity','sale_unit_price','sale_discount_percent','sale_discount_amount','sale_amount_cost','date_upd');
                        //$fields = array('description','estimated_time','real_time','suggestion','message_error','messure','customer_criteria','result','note','date_upd');
                        myPaymentDetail::updateByFields($id_record,$fields);
                    }
                    $indRow++;
                }
                //$recordsTask = myTask::getByField('id_task_group',$list_id);
                //$ret.= '</div>';
                $ret.= '<script type="text/javascript">';
                $ret.= 'var currentSubTab = \'idpaymenttab\';';
                $ret.= '</script>';
        
            }
        }
        
        $recordLang = array();
        if (array_key_exists('id_lang',$record) && $record['id_lang']){
            $recordLang = xLang::getById($record['id_lang']);
        }
        
        $recordCreatedBy = array();
        if (array_key_exists('created_by',$record) && $record['created_by']){
            $recordCreatedBy = myUser::getById($record['created_by'])[0];
        }
        
        $recordReceivedBy = array();
        if (array_key_exists('received_by',$record) && $record['received_by']){
            $recordReceivedBy = myUser::getById($record['received_by'])[0];
        }
        
        $recordCus = array();
        if (!empty($record) && array_key_exists('id_customer',$record) && $record['id_customer']){
            $recordCus = myUser::getById($record['id_customer'])[0];
        }
        
        $recordPaymentMode = array();
        if (array_key_exists('id_payment',$record) && $record['id_payment']){
            $recordPaymentMode = myPaymentMode::getById($record['id_payment'])[0];
        }
        
        $recordShippingMode = array();
        if (array_key_exists('id_shipping_mode',$record) && $record['id_shipping_mode']){
            $recordShippingMode = myShippingMode::getById($record['id_shipping_mode'])[0];
        }
        
        $recordPlace = array();
        if (array_key_exists('id_place',$record) && $record['id_place']){
            $recordPlace = myPlace::getById($record['id_place'])[0];
        }
        
        $ret.= '<form action="index.php?'.Tools::newUrl('t',Tools::getValue('t')).'" method="POST" class="">';
        /* Tools Bar */
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <div class="col-2 txt-left">';
        //$ret.= '        <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>Retourner</a>';
        $ret.= '     </div>';
        $ret.= '     <div class="col-2 txt-right">';
        $ret.= '        <button name="btnSubmit'.$btnType.'" type="submit" class="btn-form"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/>&ensp;'.RTTCore::l('Save','admin').'</button>';
        $ret.= '     </div>';
        $ret.= '   </div>';
        /* Tools Bar */
        /* Formulaire */
        $ret.= '  <div class="form">';
        /* Form Title */
        $ret.= '    <div class="row">';
        $ret.= '       <span class="frm-title">'.RTTCore::l('Order','admin').'</span>';
        $ret.= '    </div>';
        /* message Actions */
        if ($messages)
        {
            $ret.= '<div class="msg">';
            foreach($messages as $k => $message)
            {
                $ret.= '<span class="'.$message.'">' . RTTCore::l('Operation:  "'.$k.'" succesful') . '</span><br/>';
            }
            $ret.= '</div>';
        }
        
        /* End Message Actions */
        /* Form utils */
        $ret.= '    <div class="row bg-form">';
        /* 1st Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Type').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_type" name="id_type"  type="hidden" value="'.((array_key_exists('id_type',$record) && !empty($record['id_type']))?$record['id_type']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalType\')" id="txttype" name="type"  type="text" value="'.RTTCore::l($actions[((array_key_exists('id_type',$record) && !empty($record['id_type']))?$record['id_type']:Tools::getValue("tp"))],'admin').'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalType\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Language').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_lang" name="id_lang"  type="hidden" value="'.((array_key_exists('id_lang',$record) && !empty($record['id_lang']))?$record['id_lang']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalLang\')" id="txtlang" name="lang"  type="text" value="'.((array_key_exists('id_lang',$record) && !empty($record['lang']))?$record["lang"]:(!empty($recordLang['title'])?$recordLang['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalLang\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Code').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="code" type="text" value="'.(!empty($record['code'])?$record["code"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Created By').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_created_by" name="created_by"  type="hidden" value="'.((array_key_exists('created_by',$record) && !empty($record['created_by']))?$record['created_by']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalCreatedBy\')" id="txtcreated_by" name="txtcreated_by"  type="text" value="'.((array_key_exists('full_name',$record) && !empty($record['full_name']))?$record["full_name"]:(!empty($recordCreatedBy['full_name'])?$recordCreatedBy['full_name']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalCreatedBy\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Received By').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_received_by" name="received_by"  type="hidden" value="'.((array_key_exists('received_by',$record) && !empty($record['received_by']))?$record['received_by']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalReceivedBy\')" id="txtreceived_by" name="lang"  type="text" value="'.((array_key_exists('full_name',$record) && !empty($record['full_name']))?$record["full_name"]:(!empty($recordReceivedBy['full_name'])?$recordReceivedBy['full_name']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalReceivedBy\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="">';
        $chkActive = "";
        if (!empty($record['active']) && $record['active'] !== '0'){
            $chkActive = "checked=\"checked\"";
        }
        //$ret.= '                   <label>'.RTTCore::l('Completed').'.</label>';
        //$ret.= '               </div>';
        //$ret.= '               <div class="groupfield">';
        $ret.= '                   <label class="chk-container">'.RTTCore::l('Completed').'';
        $ret.= '                       <input name="active" type="checkbox" value="'.(!empty($record['active'])?$record["active"]:0).'" '.$chkActive.'>';
        $ret.= '                       <span class="checkmark"></span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 1nd Column */
        /* 2rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Customer').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_customer" name="id_customer"  type="hidden" value="'.((array_key_exists('id_customer',$record) && !empty($record['id_customer']))?$record['id_customer']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalCustomer\')" id="txtcustomer" name="lang"  type="text" value="'.((!empty($record) && array_key_exists('customer',$record) && !empty($record['customer']))?$record["customer"]:(!empty($recordCus['full_name'])?$recordCus['full_name']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalCustomer\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Payment Mode').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_payment_mode" name="id_payment"  type="hidden" value="'.((array_key_exists('id_payment',$record) && !empty($record['id_payment']))?$record['id_payment']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalPaymentMode\')" id="txtpayment_mode" name="lang"  type="text" value="'.((array_key_exists('payment_mode',$record) && !empty($record['payment_mode']))?$record["payment_mode"]:(!empty($recordPaymentMode['title'])?$recordPaymentMode['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalPaymentMode\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Shipping Mode').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_shipping_mode" name="id_shipping_mode"  type="hidden" value="'.((!empty($record) &&  array_key_exists('id_shipping_mode',$record))?$record['id_shipping_mode']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalShippingMode\')" id="txtshipping_mode" name="shipping_mode"  type="text" value="'.((array_key_exists('shipping_mode',$record) && !empty($record['shipping_mode']))?$record["shipping_mode"]:(!empty($recordShippingMode['title'])?$recordShippingMode['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalShippingMode\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Table').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_place" name="id_place"  type="hidden" value="'.((!empty($record) && array_key_exists('id_lang',$record) && !empty($record['id_lang']))?$record['id_lang']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalPlace\')" id="txtplace" name="place"  type="text" value="'.((!empty($record) && array_key_exists('place',$record))?$record["place"]:(!empty($recordPlace['title'])?$recordPlace['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalPlace\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Title').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="title" type="text" value="'.(!empty($record['title'])?$record["title"]:"Tbl-".date('Y')."-".date('m')).'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label> '.RTTCore::l("Currency").'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input style="width:60%;" id="currency" name="currency"  type="text" value="'.(!empty($record['currency'])?$record["currency"]:"Euro").'"/>';
        $ret.= '                    <input style="width:30%;" id="currency_symbole" name="currency_symbole"  type="text" value="'.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Description').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="description" type="text" value="'.(!empty($record['description'])?$record["description"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Total Detail").'</b><span id="total_detail" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Total Discount").'</b><span id="total_remise" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <hr><b class="pTitle"> '.RTTCore::l("Total HT").'</b><span id="total_exonere" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Total TVA").'</b><span id="total_tva" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <hr><b class="pTitle"> '.RTTCore::l("Total TTC").'</b><span id="total_ttc" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Total Paid").'</b><span id="total_soumise" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0,00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '       </div>';
        /* End 3rd Column */
        
        $ret.= '    </div>';
        /* End Form utils */
        
        /* Tab */
        $ret.= '    <div class="tab">';
        $ret.= '      <button type="button" class="tablinks defaultTab" onclick="openTab(event, \'detailtab\')" id="defaultTab">'.RTTCore::l('Detail','admin').'</button>';
        $ret.= '      <button id="idpaymenttab" type="button" class="tablinks" onclick="openTab(event, \'paymenttab\')">'.RTTCore::l('Payment','admin').'</button>';
        $ret.= '    </div>';
        
        /* General Tab */
        $ret.= '    <div id="detailtab" class="tabcontent">';
        $ret.= self::detailTab($list_id);
        $ret.= '    </div>';
        /* End General Tab */
        
        /* Payment Tab */
        $ret.= '    <div id="paymenttab" class="tabcontent">';
        $ret.= self::paymentTab($list_id);
        $ret.= '    </div>';
        /* End Payment Tab */
        
        /* End Tab */
        
        $ret.= '  </div>';
        /* End Formulaire */
        
        /* Modal Block */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalLang" class="modal">';
        $ret.= self::modalLang();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCreatedBy" class="modal">';
        $ret.= self::modalCreatedBy();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalReceivedBy" class="modal">';
        $ret.= self::modalReceivedBy();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCustomer" class="modal">';
        $ret.= self::modalCustomer();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalPaymentMode" class="modal">';
        $ret.= self::modalPaymentMode();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalShippingMode" class="modal">';
        $ret.= self::modalShippingMode();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalPlace" class="modal">';
        $ret.= self::modalPlace();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalArticle" class="modal">';
        $ret.= self::modalArticle($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalPayment" class="modal">';
        $ret.= self::modalPayment($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* End Modal Block */
        
        /* Form */
        $ret.= '</form>';
        return $ret;
    }
    
    public static function modalPayment($id_list)
    {
        $recordList = 0;
        $recordList = ((myOrder::getById($id_list) !== null)?(myOrder::getById($id_list)[0]):0);
        $table = "payment_mode";
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        $records = myPaymentMode::getRecords();
        //var_dump($records[0]["id_payment_mode"]);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Payment','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        $ret.= '   <div class="modal-body">';
        $fieldsTaskGroup = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description'),
           'id_add'              => RTTCore::l('Start Date'),
           'id_upd'              => RTTCore::l('End Date')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'title\' ,\'description\', \'date_add\',\'date_upd\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsTaskGroup as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '       </tr>';
        
        /* body table */
        if ($records)
        {
            foreach($records as $recordPay)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-equipmentId="'.(!empty($recordList)?$recordList['id_customer']:0).'" data-listId="'.(!empty($id_list)?$id_list:$indRow).'"  onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($recordPay['id_'.$table.''])?$recordPay['id_'.$table.'']:0).'"/></td>';
                
                foreach($fieldsTaskGroup as $field => $th)
                {
                    if ($field == 'id_'.$table.'' OR $field == 'date_add' OR $field == 'date_upd')
                    {
                        $ret.= '<td class="txt-center">'.$recordPay[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$recordPay[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record['id_'.$table.''].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_message"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '      </table>';
        $ret.= '   </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalPayment\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectGroupValue(\'modalPayment\',\''.$table.'\',\''.$table.'\',\'payment_detail\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '  </div>';
        return $ret;
    }
    
    public static function modalArticle($id_list)
    {
        $recordList = 0;
        $recordList = ((myOrder::getById($id_list) !== null)?(myOrder::getById($id_list)[0]):0);
        $table = "article";
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        $records = myArticle::getRecords();
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Product','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        $ret.= '   <div class="modal-body">';
        $fieldsTaskGroup = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description'),
           'id_add'              => RTTCore::l('Start Date'),
           'id_upd'              => RTTCore::l('End Date')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'title\' ,\'description\', \'date_add\',\'date_upd\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsTaskGroup as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '       </tr>';
        
        /* body table */
        if ($records)
        {
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-equipmentId="'.(!empty($recordList)?$recordList['id_customer']:0).'" data-listId="'.(!empty($id_list)?$id_list:$indRow).'"  onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:0).'"/></td>';
                
                foreach($fieldsTaskGroup as $field => $th)
                {
                    if ($field == 'id_'.$table.'' OR $field == 'date_add' OR $field == 'date_upd')
                    {
                        $ret.= '<td class="txt-center">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record['id_'.$table.''].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_message"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '      </table>';
        $ret.= '   </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalArticle\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectGroupValue(\'modalArticle\',\''.$table.'\',\''.$table.'\',\'order_detail\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '  </div>';
        return $ret;
    }
    
    public static function paymentTab($idWorkOrder)
    {
        $total_soumise = 0;
        $table = "payment_detail";
        $record  = myOrder::getById($idWorkOrder)[0];
        $ret = '';
        $recordsJournal = array();
        if ($record["id_order_list"]){
            $recordsJournal = myPaymentDetail::getByWhere("`id_order_list`=".$record["id_order_list"]);
        }
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l('Order Detail','admin').'</span>';
        $ret.= '         <span id="'.$table.'_records" class="article">'.count($recordsJournal).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '      </div>';
        
        $journalFields = array(
           'code'                   => RTTCore::l('Code'),
           'title'                  => RTTCore::l('Title'),
           'order_quantity'         => RTTCore::l('Quantity'),
           'sale_unit_price'        => RTTCore::l('Unit Price'),
           'sale_discount_percent'  => RTTCore::l('Discount (%)'),
           'sale_discount_amount'   => RTTCore::l('Total Discount'),
           'sale_amount_cost'       => RTTCore::l('Total Detail')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="showModal(\'modalPayment\')" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1090923.png"/></button>';
        $ret.= '        <button onclick="addRow(\'workorder_journal\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelPaymentDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddPaymentDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table bg-tbl">';
        $ret.= '       <table id="payment_detail">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'article_group\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($journalFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'payment_detail\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'payment_detail" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsJournal){
            $indRow = 0;
            foreach($recordsJournal as $recordJournal)
            {
                $indRow++;
                $discount_amount = 0;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'payment_detail\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" id="payment_detail'.$indRow.'" type="checkbox" value="'.$recordJournal['id_payment_detail'].'"/></td>';
                foreach($journalFields as $field => $th)
                {
                    if ($field == 'order_quantity')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordJournal[$field])?$recordJournal[$field]:0).'</td>';
                    }else if ($field == 'sale_unit_price' OR $field == 'sale_discount_percent')
                    {
                        $ret.= '<td class="txt-right">'.number_format((float)$recordJournal[$field],2).'</td>';
                    }else if ($field == 'sale_discount_amount')
                    {
                        $discount_amount = (number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2)) * ( number_format((float)$recordJournal['sale_discount_percent'],2) / 100 );
                        $ret.= '<td class="txt-right">'.number_format((float)$discount_amount,2).'</td>';
                    }else if ($field == 'sale_amount_cost')
                    {
                        $order_amount = number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2);
                        $total_soumise += $order_amount;
                        $ret.= '<td class="txt-right">'.number_format((float)($order_amount + $discount_amount ),2).'</td>';
                    }elseif ($field != 'title')
                    {
                        $ret.= '<td class="">'.$recordJournal[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idor='.$recordJournal["id_order_detail"].'&token'.Tools::getValue('token').'" >'.$recordJournal[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/></a>';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= ' </tr>';
            }
        }else {
            $ret.= '     <tr class="tbl-empty">';
            $ret.= '     </tr>';
        }
        $ret.= '       </table>';
        $ret.= '     </div>';
        
        $ret.= '<script type="text/javascript">';
        $ret.= '     document.getElementById("total_soumise").innerText  = "'.number_format($total_soumise,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '</script>';
            
        $ret.= '<input class="" id="total_soumise" name="total_soumise"  type="hidden" value="'.number_format($total_soumise,2).'"/>';
        
        return $ret;
    }
    
    public static function detailTab($idWorkOrder)
    {
        /* Declation Variables */
        $totalDetail  = 0;
        $totalRemise  = 0;
        
        $table = "order_detail";
        $record  = myOrder::getById($idWorkOrder)[0];
        $ret = '';
        $recordsJournal = array();
        if ($record["id_order_list"]){
            $recordsJournal = myOrderDetail::getByWhere("`id_order_list`=".$record["id_order_list"]);
        }
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l('Order Detail','admin').'</span>';
        $ret.= '         <span id="'.$table.'_records" class="article">'.count($recordsJournal).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '      </div>';
        
        $journalFields = array(
           'code'                   => RTTCore::l('Code'),
           'title'                  => RTTCore::l('Title'),
           'order_quantity'         => RTTCore::l('Quantity'),
           'sale_unit_price'        => RTTCore::l('Unit Price'),
           'sale_discount_percent'  => RTTCore::l('Discount (%)'),
           'sale_discount_amount'   => RTTCore::l('Total Discount'),
           'sale_amount_cost'       => RTTCore::l('Total Detail')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="showModal(\'modalArticle\')" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1090923.png"/></button>';
        $ret.= '        <button onclick="addRow(\'workorder_journal\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelOrderDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddOrderDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table bg-tbl">';
        $ret.= '       <table id="order_detail">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'article_group\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($journalFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'article_group\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'article_group" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsJournal){
            $indRow = 0;
            foreach($recordsJournal as $recordJournal)
            {
                $indRow++;
                $discount_amount = 0;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'order_detail\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" id="order_detail'.$indRow.'" type="checkbox" value="'.$recordJournal['id_order_detail'].'"/></td>';
                foreach($journalFields as $field => $th)
                {
                    if ($field == 'order_quantity')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordJournal[$field])?$recordJournal[$field]:0).'</td>';
                    }else if ($field == 'sale_unit_price' OR $field == 'sale_discount_percent')
                    {
                        $ret.= '<td class="txt-right">'.number_format((float)$recordJournal[$field],2).'</td>';
                    }else if ($field == 'sale_discount_amount')
                    {
                        $discount_amount = (number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2)) * ( number_format((float)$recordJournal['sale_discount_percent'],2) / 100 );
                        $totalRemise += $discount_amount;
                        $ret.= '<td class="txt-right">'.number_format((float)$discount_amount,2).'</td>';
                    }else if ($field == 'sale_amount_cost')
                    {
                        $order_amount = number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2);
                        $totalDetail  += $order_amount;
                        $ret.= '<td class="txt-right">'.number_format((float)($order_amount - $discount_amount ),2).'</td>';
                    }elseif ($field != 'title')
                    {
                        $ret.= '<td class="">'.$recordJournal[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idor='.$recordJournal["id_order_detail"].'&token'.Tools::getValue('token').'" >'.$recordJournal[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/></a>';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= ' </tr>';
            }
        }else {
            $ret.= '     <tr class="tbl-empty">';
            $ret.= '     </tr>';
        }
        $ret.= '       </table>';
        $ret.= '     </div>';
        
        $totalExonere =  (float)$totalDetail - (float)$totalRemise ;
        $tva          = 20;
        $totalTVA     =  ((float)$totalExonere * $tva)/100;
        $totalTTC     = (float)$totalExonere + (float)$totalTVA;
        $totalSoumise = '0';
        
        $ret.= '<script type="text/javascript">';
        $ret.= '     document.getElementById("total_detail").innerText  = "'.number_format($totalDetail,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_remise").innerText  = "'.number_format($totalRemise,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_exonere").innerText = "'.number_format($totalExonere,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_soumise").innerText = "'.number_format($totalSoumise,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_tva").innerText     = "'.number_format($totalTVA,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_ttc").innerText     = "'.number_format($totalTTC,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '</script>';
            
        $ret.= '<input class="" id="total_detail" name="total_detail"  type="hidden" value="'.number_format($totalDetail,2).'"/>';
        $ret.= '<input class="" id="total_remise" name="total_remise"  type="hidden" value="'.number_format($totalRemise,2).'"/>';
        $ret.= '<input class="" id="total_exonere" name="total_exonere"  type="hidden" value="'.number_format($totalExonere,2).'"/>';
        $ret.= '<input class="" id="total_tva" name="total_tva"  type="hidden" value="'.number_format($totalTVA,2).'"/>';
        $ret.= '<input class="" id="total_ttc" name="total_ttc"  type="hidden" value="'.number_format($totalTTC,2).'"/>';
        $ret.= '<input class="" id="total_soumise" name="total_soumise"  type="hidden" value="'.number_format($totalSoumise,2).'"/>';
            
        return $ret;
    }
    
    public static function modalPlace()
    {
        $table = 'place';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myPlace::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Place','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'title\' ,\'description\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalPlace\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalPlace\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalShippingMode()
    {
        $table = 'shipping_mode';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myShippingMode::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Shipping Mode','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'title\' ,\'description\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalShippingMode\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalShippingMode\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalPaymentMode()
    {
        $table = 'payment_mode1';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myPaymentMode::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Payment Mode','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'title\' ,\'description\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalPaymentMode\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalPaymentMode\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalCustomer()
    {
        $table = 'customer';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myUser::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Service','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'full_name'           => RTTCore::l('Full Name'),
           'email'               => RTTCore::l('Email')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'full_name\' ,\'email\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['full_name'])?$record['full_name']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalCustomer\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalCustomer\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalReceivedBy()
    {
        $table = 'received_by';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myUser::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Service','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'full_name'           => RTTCore::l('Full Name'),
           'email'               => RTTCore::l('Email')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'full_name\' ,\'email\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['full_name'])?$record['full_name']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalReceivedBy\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalReceivedBy\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalCreatedBy()
    {
        $table = 'created_by';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myUser::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Employee','admin').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'full_name'           => RTTCore::l('Full Name'),
           'email'               => RTTCore::l('Email')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'full_name\' ,\'email\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['full_name'])?$record['full_name']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalCreatedBy\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalCreatedBy\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalLang()
    {
        $table = 'lang';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = xLang::getRecords();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Language","admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'id_lang'             => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'iso_code'            => RTTCore::l('ISO Code')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'status\' ,\'description\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'\'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l("Actions","admin").'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_'.$table.''])?$record['id_'.$table.'']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field !== 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_work_order_status"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '      </tr>';
            }
        }
        $ret.= '           </table>';
        $ret.= '       </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalLang\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalLang\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l("Ok","admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function hookOrder($args)
    {
        global $actions,$status,$actives;
        $records = array();
        $table = "order_list";
        myOrder::init();
        myPaymentMode::init();
        myShippingMode::init();
        $records = myOrder::getByWhere("DATE(`date_add`)='".date('Y-m-d',time())."' AND `active`=0");
        
        $fieldsDisplay = array(
            'id_lang'          => RTTCore::l('Language'),
            'id_type'          => RTTCore::l('Type'),
            'title'            => RTTCore::l('Title'),
            'id_shipping_mode' => RTTCore::l('Shipping Mode'),
            'id_payment'       => RTTCore::l('Payment Mode'),
            'id_place'         => RTTCore::l('Table'),
            'code'             => RTTCore::l('Code'),
            'total_detail'     => RTTCore::l('Total Detail'),
            'total_remise'     => RTTCore::l('Total Discount'),
            'total_exonere'    => RTTCore::l('Total HT'),
            'total_tva'        => RTTCore::l('Total TVA'),
            'total_ttc'        => RTTCore::l('Total TTC'),
            'total_soumise'    => RTTCore::l('Total Paid'),
            'description'      => RTTCore::l('Description'),
            'active'           => RTTCore::l('Active'),
        );
        $ret = "";
        //$ret.= "Hook: Order";
        
        /* Tools actions */
        $ret.= '<div class="row tools-title">';
        $ret.= '   <div class="col-2 txt-left">';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>'.RTTCore::l('Return','admin').'</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-2 txt-right">';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&act=form'.'&token='.Tools::getValue('token').'"> XLS</a>';
        //$ret.= '     <a class="btn-form" onclick="return printDoc(\'myTable\');" href=""> <i class="bx bx-printer"></i> Imprimer</a>';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&act=form'.'&token='.Tools::getValue('token').'"> <i class="bx bx-plus-circle"></i> Créer</a>';
        $ret.= '     <select class="action" onchange="javascript:action(this.value,\''.$table.'\')" >';
        //$ret.= '         <i class="bx bx-list-ul"></i> Options';
        $ret.= '         <option class="txt-center" value="-1"> <i class="bx bx-list-check"></i>'.RTTCore::l('Actions','admin').'</option>';
        foreach($actions as $k => $val){
            $ret.= '     <option style="background-image:url(\'../Bundles/images/advancetools/10629723.png\');" value="'.$k.'"> <img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/>'.RTTCore::l($val,'admin').'</option>';
        }
        $ret.= '     </select>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Tools actions */
        
        $ret.= '<div class="row">';
        $ret.= ' <div class="bg-table">';
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Order List','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '     <span class="article flt-right">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '  </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '       <tr>';
        $ret.= '         <th onclick="sortTable(0)" class="bg-orange"> <img class="tbl-ico" id="col0'.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; </th>';
        $indCol = 1;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '     <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '         <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '       </tr>';
        
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $ret.= '  <td class="txt-center"><input id="'.$table.$indRow.'" type="checkbox" value="'.$record['id_'.$table].'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if (array_key_exists('id_type',$record) && $field == 'id_type')
                    {
                        //$ret.= '<td class="txt-center stat '.$actions[2].'">'.$actions[2].'</td>';
                        $ret.= '<td class="txt-center stat '.strtolower($actions[$record[$field]]).'">'.RTTCore::l($actions[$record[$field]],'admin').'</td>';
                    }else if (array_key_exists('active',$record) && $field == 'active')
                    {
                        $ret.= '<td class="txt-center stat '.strtolower($status[$record[$field]]).'">'.RTTCore::l($actives[$record[$field]]).'</td>';
                    }else if (array_key_exists('title',$record) && $field == 'title')
                    {
                        $ret.= '<td class="w-50">'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }else{
                        $ret.= '<td>'.(array_key_exists($field,$record)?$record[$field]:'').'</td>';
                    }
                }
                $ret.= '  <td class="txt-center">';
                $ret.= '     <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_".$table].'&token='.Tools::getValue('token').'&tp=1"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '     <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_".$table].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '  </td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '     </table>';
        $ret.= '   </div>';
        $ret.= ' </div>';
        $ret.= '</div>'; 
        return $ret;
    }
}
?>