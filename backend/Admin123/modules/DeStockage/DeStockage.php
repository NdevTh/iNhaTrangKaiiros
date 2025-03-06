<?php
use classes\WebCore as RTTCore;
use classes\Module as Module;
use classes\Tools as  Tools;
use classes\myConsommableOrder as myConsommableOrder;
use classes\myConsommableDetail as myConsommableDetail;
use classes\mySparePart as mySparePart;
use classes\myWorkOrder as myWorkOrder;
use classes\myCompany as myCompany;
use classes\myAsset as myAsset;
use classes\myAddress as myAddress;
use classes\myComplex as myComplex;
use classes\myBuilding as myBuilding;
use classes\myTaskReport as myTaskReport;
use classes\myTask as myTask;
use classes\myUser as myUser;
use classes\myEquipment as myEquipment;

class DeStockage extends Module
{
    public static function form($args)
    {
        global $AdminUser, $typeConsommable, $statusConsommable ;
        $ret = "";
        $record      = array();
        $messages    = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id = 0;
        myConsommableOrder::init();
        myConsommableDetail::init();
        
        $newCode = 'DS0001';
        //echo RTTCore::l("Test");
        if (myConsommableOrder::getLastRecord()){
            $newCode = !empty(myConsommableOrder::getLastRecord()['code'])?('DS'.RTTCore::addZeroBeforeNum((RTTCore::strToInt(myConsommableOrder::getLastRecord()['code'])+1),5)):$newCode;
        }
        //echo RTTCore::addZeroBeforeNum((RTTCore::strToInt('15')+1),5);
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record  = isset(myConsommableOrder::getById($list_id)[0])?myConsommableOrder::getById($list_id)[0]:array();
        }
        //var_dump($record);
        
        if (Tools::isSubmit('btnSubmitAdd')){
            $messages[$btnType] = 'success';
            $list_id = myConsommableOrder::save();
            $record  = isset(myConsommableOrder::getById($list_id)[0])?myConsommableOrder::getById($list_id)[0]:array();
            Tools::redirect("index.php?".Tools::newURL("id",$list_id));
        }
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= '    Action "Edit" was successful';
            $ret.= '</div>';
            //echo $_POST["problem_summary"];
            $_POST["date_upd"] = $currentDate;
            myConsommableOrder::updateById(Tools::getValue('id'));
            $record = isset(myConsommableOrder::getById(Tools::getValue('id'))[0])?myConsommableOrder::getById(Tools::getValue('id'))[0]:array();
            //Tools::refresh();
        }
        
        $recordWorkOrder = array();
        if (isset($record['id_workorder']))
        {
            $recordWorkOrder = isset(myWorkOrder::getById($record['id_workorder'])[0])?myWorkOrder::getById($record['id_workorder'])[0]:array();
        }
        $recordOwner = array();
        if (isset($record['id_created_by']))
        {
            $recordOwner = isset(myUser::getById($record['id_created_by'])[0])?myUser::getById($record['id_created_by'])[0]:array();
        }
        
        $recordOrder = array();
        if (isset($record['id_order_by']))
        {
            $recordOrder = isset(myUser::getById($record['id_order_by'])[0])?myUser::getById($record['id_order_by'])[0]:array();
        }
        
        $recordCompa = array();
        if (isset($record['id_account']))
        {
            $recordCompa = isset(myCompany::getById($record['id_account'])[0])?myCompany::getById($record['id_account'])[0]:array();
        }
        
        if (Tools::isSubmit('btnSubmitAddConsommableDetail')){
            //var_dump($_POST['arrid_Consommable_detail']);
            if(isset($_POST['arrid_consommabledetail'])){
                $indRow = 0;
                
                //var_dump($_POST['arrid_consommabledetail']);
                
                foreach($_POST['arrid_consommabledetail'] as $id_qd_report)
                {
                    //$ret.= '<br/>'.$id_task_report .' Equipment: ' . $_POST['arrestimated_time'];
                    if ($id_qd_report == 0){
                        $_POST['id_consommable_order']   = $list_id;
                        $_POST['id_customer']          = $_POST['id_customer'];
                        if (isset(myEquipment::getByWhere('`title` = "'.$_POST['arrtitle'][$indRow].'"')[0]))
                        {
                            $article = myEquipment::getByWhere('`title`="'.$_POST['arrtitle'][$indRow].'"')[0];
                            $_POST['id_equipment']         = $article['id_equipment'];
                            $_POST['refference']           = $article['refference'];
                        }else{
                            $_POST['id_equipment']         = '';
                            $_POST['refference']           = '';
                        }
                        $_POST['title']                      = $_POST['arrtitle'][$indRow];
                        $_POST['quantity_consommable']         = $_POST['arrquantity_consommable'][$indRow];
                        $_POST['unit_price_estimated']       = str_replace($record['currency_symbole'],"",$_POST['arrunit_price_estimated'][$indRow]);
                        $_POST['amount_cost_estimated']      = str_replace($record['currency_symbole'],"",$_POST['arramount_cost_estimated'][$indRow]);
                        $_POST['discount_percent_estimated'] = str_replace($record['currency_symbole'],"",$_POST['arrdiscount_percent_estimated'][$indRow]);
                        $_POST['discount_amount_estimated']  = str_replace($record['currency_symbole'],"",$_POST['arrdiscount_amount_estimated'][$indRow]);
                        $_POST['quantity_purchase']          = $_POST['arrquantity_purchase'][$indRow];
                        $_POST['unit_price_purchase']        = str_replace($record['currency_symbole'],"",$_POST['arrunit_price_purchase'][$indRow]);
                        $_POST['id_tax']                     = 1;
                        $_POST['date_add']                   = $currentDate;
                        $_POST['date_upd']                   = $currentDate;
                        
                        //\'date_upd\',\'title\',\'refference\',\'quantity_consommable\',\'unit_price_estimated\',\'quantity_purchase\',\'unit_price_purchase\',\'action
                        myConsommableDetail::save();
                    }elseif ($id_qd_report >= 1){
                        $_POST['id_consommable_order']   = $list_id;
                        $_POST['id_customer']          = $_POST['id_customer'];
                        if (isset(myEquipment::getByWhere('`title` = "'.$_POST['arrtitle'][$indRow].'"')[0]))
                        {
                            $article = myEquipment::getByWhere('`title`="'.$_POST['arrtitle'][$indRow].'"')[0];
                            $_POST['id_equipment']         = $article['id_equipment'];
                            $_POST['refference']           = $article['refference'];
                        }else{
                            $_POST['id_equipment']         = '';
                            $_POST['refference']           = '';
                        }
                        $_POST['title']                      = $_POST['arrtitle'][$indRow];
                        $_POST['quantity_consommable']       = $_POST['arrquantity_consommable'][$indRow];
                        $_POST['unit_price_estimated']       = str_replace($record['currency_symbole'],"",$_POST['arrunit_price_estimated'][$indRow]);
                        $_POST['amount_cost_estimated']      = str_replace($record['currency_symbole'],"",$_POST['arramount_cost_estimated'][$indRow]);
                        $_POST['discount_percent_estimated'] = str_replace($record['currency_symbole'],"",$_POST['arrdiscount_percent_estimated'][$indRow]);
                        $_POST['discount_amount_estimated']  = str_replace($record['currency_symbole'],"",$_POST['arrdiscount_amount_estimated'][$indRow]);
                        $_POST['quantity_purchase']          = $_POST['arrquantity_purchase'][$indRow];
                        $_POST['unit_price_purchase']        = str_replace($record['currency_symbole'],"",$_POST['arrunit_price_purchase'][$indRow]);
                        $_POST['id_tax']                     = 1;
                        //$_POST['date_add']                   = $currentDate;
                        $_POST['date_upd']                   = $currentDate;
                        
                        $fields = array('date_upd','title','refference','quantity_consommable','unit_price_estimated','amount_cost_estimated','discount_percent_estimated','discount_amount_estimated','quantity_purchase','unit_price_purchase','id_tax');
                        myConsommableDetail::updateByFields($id_qd_report,$fields);
                    }
                    $indRow++;
                }
                
                $messages[$btnType] = 'success';
                $ret.= '<script type="text/javascript">';
                //$ret.= 'var currentSubTab = \'idgeneraltab\';';
                $ret.= '</script>';
        
            }
        }
        /* Form Tools Bar */
        $ret.= '<form action="index.php?'.Tools::newUrl('t',Tools::getValue('t')).'" method="POST" class="">';
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <div class="col-2 txt-left">';
        $ret.= '        <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>'.RTTCore::l("Return","Admin").'</a>';
        $ret.= '     </div>';
        $ret.= '     <div class="col-2 txt-right">';
        $ret.= '        <button onclick="showModal(\'modalReport\')" name="btnPreview" type="button" class="btn-form"><img class="tbl-ico16" src="../Bundles/images/advancetools/10397230.png"/>&ensp;'.RTTCore::l("Printer","Admin").'</button>';
        $ret.= '        <button name="btnSubmit'.$btnType.'" type="submit" class="btn-form"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/>&ensp;'.RTTCore::l("Save","Admin").'</button>';
        $ret.= '     </div>';
        $ret.= '   </div>';
        /* End Form Tools Bar */
        
        /* message Actions */
        if ($messages)
        {
            $ret.= '<div class="msg">';
            foreach($messages as $k => $message)
            {
                $ret.= '<span class="'.$message.'">' . RTTCore::l('Operation:  "'.$k.'" with succesful') . '</span><br/>';
            }
            $ret.= '</div>';
        }
        /* End Message Actions */
        /* End Form Background */
        $ret.= '   <div class="form">';
        /* Form Title */
        $ret.= '     <div class="row">';
        $ret.= '        <span class="frm-title">'.RTTCore::l("DeStockage","Admin").'</span>';
        $ret.= '     </div>';
        /* Form Title */
        /* Form Background */
        $ret.= '     <div class="bg-form">';
        /* Row 1 */
        $ret.= '        <div class="row">';
        $ret.= '            <div class="col-3">';
        
        $ret.= '               <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label>'.RTTCore::l("Type").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="id_type" name="id_type"  type="text" value="'.$typeConsommable[(!empty($record['id_type'])?$record["id_type"]:"0")].'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Code").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="code" name="code"  type="text" value="'.(!empty($record['code'])?$record["code"]:$newCode).'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Status").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="id_status" name="id_status"  type="text" value="'.$statusConsommable[(!empty($record['id_status'])?$record["id_status"]:"0")].'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Date").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="date_add" name="date_add"  type="date" value="'.(!empty($record['date_add'])?date('Y-m-d', strtotime($record["date_add"])):date('Y-m-d', strtotime($currentDate))).'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Expected Date").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="date_estimated" name="date_estimated"  type="date" value="'.(!empty($record['date_estimated'])?date('Y-m-d',strtotime($record["date_estimated"])):"").'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Title").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="title" name="title"  type="text" value="'.(!empty($record['title'])?$record["title"]:"").'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '           </div>';
        $ret.= '           <div class="col-3">';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Order").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="id_workorder" name="id_workorder"  type="hidden" value="'.(!empty($record['id_workorder'])?$record["id_workorder"]:"").'"/>';
        $ret.= '                      <input class="select" onclick="showModal(\'modalWorkOrder\')" id="txtworkorder" name="workorder"  type="text" value="'.(!empty($record['workorder'])?$record["workorder"]:(!empty($recordWorkOrder['workorder_title'])?$recordWorkOrder['workorder_title']:"")).'"/>';
        $ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'modalWorkOrder\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Created By").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        //$ret.= '                      <input class="" id="id_created_by" name="id_created_by"  type="text" value="'.(!empty($record['id_created_by'])?$record["id_created_by"]:$AdminUser["id_user"]).'"/>';
        $ret.= '                      <input class="" id="id_created_by" name="id_created_by"  type="hidden" value="'.(!empty($record['id_created_by'])?$record["id_created_by"]:$AdminUser["id_user"]).'"/>';
        $ret.= '                      <input class="select" onclick="showModal(\'modalOwner\')" id="txtcreated_by" name="created_by"  type="text" value="'.(!empty($record['created_by'])?$record["id_created_by"]:(!empty($recordOwner['full_name'])?$recordOwner['full_name']:$AdminUser["full_name"])).'"/>';
        $ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'modalOwner\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Order By").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        //$ret.= '                      <input class="" id="id_company" name="id_company"  type="text" value="'.(!empty($record['id_company'])?$record["id_company"]:"").'"/>';
        $ret.= '                      <input class="" id="id_order_by" name="id_order_by"  type="hidden" value="'.(!empty($record['id_order_by'])?$record["id_order_by"]:$AdminUser["id_user"]).'"/>';
        $ret.= '                      <input class="select" onclick="showModal(\'modalOrder\')" id="txtorder_by" name="order_by"  type="text" value="'.(!empty($record['order_by'])?$record["order_by"]:(!empty($recordOrder['full_name'])?$recordOrder['full_name']:"")).'"/>';
        $ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'modalOrder\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Account").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="id_account" name="id_account"  type="hidden" value="'.(!empty($recordCompa['id_company'])?$recordCompa["id_company"]:"").'"/>';
        $ret.= '                      <input class="select" onclick="showModal(\'modalAccount\')" id="txtaccount" name="account_title"  type="text" value="'.(!empty($record['company'])?$record["company"]:(!empty($recordCompa['company'])?$recordCompa['company']:"")).'"/>';
        $ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'modalAccount\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Description").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input class="" id="description" name="description"  type="text" value="'.(!empty($record['description'])?$record["description"]:"").'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <div class="groupform">';
        $ret.= '                      <label> '.RTTCore::l("Currency").'</label>';
        $ret.= '                  </div>';
        $ret.= '                  <div class="groupfield txt-left">';
        $ret.= '                      <input style="width:60%;" id="currency" name="currency"  type="text" value="'.(!empty($record['currency'])?$record["currency"]:"Euro").'"/>';
        $ret.= '                      <input style="width:30%;" id="currency_symbole" name="currency_symbole"  type="text" value="'.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        //$ret.= '                      <button type="button" class="btn-select" onclick="showModal(\'myModal1\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '                  </div>';
        $ret.= '              </div>';
        
        $ret.= '           </div>';
        $ret.= '           <div class="col-3">';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Total Detail").'</b><span id="total_detail" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0.00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <b class="pTitle"> '.RTTCore::l("Discount").'</b><span id="total_remise" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0.00").'</span>';
        $ret.= '              </div>';
        
        $ret.= '              <div class="col-1">';
        $ret.= '                  <hr><b class="pTitle"> '.RTTCore::l("Total HT").'</b><span id="total_exonere" class="pValue">'.(!empty($record['subtotal'])?$record["subtotal"]:"0.00").'</span>';
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
        
        $ret.= '           </div>';
        
        $ret.= '        </div>';
        /* End Row 1 */
        
        /* Tabs */
        $ret.= '        <div class="tab">';
        $ret.= '            <button type="button" class="tablinks defaultTab" onclick="openTab(event, \'detailtab\')" id="defaultTab">'.RTTCore::l("Detail","Admin").'</button>';
        $ret.= '            <button type="button" class="tablinks" onclick="openTab(event, \'spareparttab\')">'.RTTCore::l("Order","Admin").'</button>';
        $ret.= '            <button type="button" class="tablinks" onclick="openTab(event, \'otherexpensetab\')">'.RTTCore::l("Taxes","Admin").'</button>';
        $ret.= '            <button type="button" class="tablinks" onclick="openTab(event, \'attachementtab\')">'.RTTCore::l("Files","Admin").'</button>';
        $ret.= '            <button type="button" class="tablinks" onclick="openTab(event, \'journalworktab\')">'.RTTCore::l("Journal","Admin").'</button>';
        $ret.= '        </div>';
        
        /* Pièces Tab */
        $ret.= '   <div id="detailtab" class="tabcontent">';
        $ret.= self::detailTab($list_id);
        $ret.= '   </div>';
        /* End Pièces */
        
        /* Pièces Tab */
        $ret.= '   <div id="spareparttab" class="tabcontent">';
        $ret.= self::sparepartTab($list_id);
        $ret.= '   </div>';
        /* End Pièces */
        
        
        /* End Tabs */
        $ret.= '    </div>';
        /* End Form Background */
        $ret.= '  </div>';
        /* End Form util */
        
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalWorkOrder" class="modal">';
        /*<!-- Modal content -->*/
        $ret.= self::modalWorkorder($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalAccount" class="modal">';
        $ret.= self::modalCompany($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalOwner" class="modal">';
        /*<!-- Modal content -->*/
        $ret.= self::modalOwner($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalOrder" class="modal">';
        /*<!-- Modal content -->*/
        $ret.= self::modalOrder($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '       <div id="modalReport" class="modal">';
        $ret.= self::reportPreview($list_id,$record["id_workorder"],$record["id_order_by"]);
        $ret.= '       </div>';
        /*<!-- End The Modal -->*/
        
        $ret.= '</form>';
        /* End Form */
        
        return $ret;
    }
    
    public static function modalCompany($idConsommableOrder)
    {
        global $AdminUser, $MaintenanceCategories;
        $ret = "";
        $record      = array();
        $list_id = $id;
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        $recordsAccount = myCompany::getByWhere('`system_role`='._CUS_SYSTEM_ROLE_);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Company","Admin").'</span>';
        $ret.= '     <span id="account_records" class="article">'.count($recordsAccount).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'account\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        $ret.= '   <div class="modal-body">';
        $fieldsAccount = array(
           'code'                => RTTCore::l('Code'),
           'company'             => RTTCore::l('Company'),
           'description'         => RTTCore::l('Description'),
           'date_start'          => RTTCore::l('Start Date'),
           'date_end'            => RTTCore::l('End Date')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'account\',[\'checkbox\',\'code\',\'account_title\' ,\'description\', \'date_start\',\'date_end\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","Admin").'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="account">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsAccount as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'" src="Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '       </tr>';
        
        /* body table */
        if ($recordsAccount)
        {
            foreach($recordsAccount as $recordAcc)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($recordAcc['id_company'])?$recordAcc['id_company']:$indRow).'"  onchange="changeChecked(\'account\',this.id)" id="account'.$indRow.'" type="checkbox" value="'.(!empty($recordAcc['company'])?$recordAcc['company']:$indRow).'"/></td>';
                
                foreach($fieldsAccount as $field => $th)
                {
                    if ($field == 'id_company' OR $field == 'date_start' OR $field == 'date_end')
                    {
                        $ret.= '<td class="txt-center">'.$recordAcc[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$recordAcc[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_workorder"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_workorder"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '      </table>';
        $ret.= '   </div>';
        $ret.= '   </div>';
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalAccount\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","Admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalAccount\',\'account\',\'txtaccount\');" class="btn-form" type="button">'.RTTCore::l("Ok","Admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '  </div>';
    
        return $ret;
    }
    public static function reportPreview($idConsommable,$idWorkOrder,$idCustomer=0,$idOwner=1)
    {
        global $AdminUser, $MaintenanceCategories;
        $ret = "";
        $record      = array();
        $list_id = $id;
        $record  = isset(myConsommableOrder::getById($idConsommable)[0])?myConsommableOrder::getById($idConsommable)[0]:array();
        
        $recordWorkOrder = array();
        $recordAsset = array();
        $recordAssetLoc = array();
        $recordAssetLocBuild = array();
        $recordOwner = array();
        $intervenant = array();
        $recordCustomer = array();
        $recordCusAddress = array();
        $recordsTaskReport = array();
        
        if ($idWorkOrder){
            $recordWorkOrder = myWorkOrder::getById($idWorkOrder)[0];
            //var_dump($recordWorkOrder);
            $recordAsset = myAsset::getById($recordWorkOrder["id_asset"])[0];
            //var_dump($recordWorkOrder);
            $recordAssetLoc = myComplex::getById($recordAsset["id_complex"])[0];
            //var_dump($recordWorkOrder);
            $recordAssetLocBuild = myBuilding::getById($recordAsset["id_building"])[0];
            //var_dump($recordWorkOrder);
            $recordOwner = myCompany::getById($idOwner)[0];
            //var_dump($recordOwner);
            $intervenant = myUser::getById($record["id_created_by"])[0];
            //var_dump($recordOwner);
            
            $recordCustomer = myCompany::getById($idCustomer)[0];
            //var_dump($recordCustomer);
            if ($recordCustomer){
                $recordCusAddress = myAddress::getById($recordCustomer["id_address"])[0];
                //var_dump($recordCusAddress);
            }
            $recordsConsommableDetail = myConsommableDetail::getByWhere("id_consommable_order = '".$idConsommable."'");
        }
        //var_dump($recordsTaskReport);
        $ret.= '         <div class="modal-content">';
        //$ret.= '            Test';
        $ret.= '            <div class="row tools-title">';
        $ret.= '               <span class="tbl-title">Appercevoir le rappot</span>';
        //$ret.= '               <span class="article">'.count($recordsAsset).' Article(s)</span>';
        //$ret.= '               <input id="inpSearch" onkeyup="myFunction(this,\'asset\')" class="txt-search" type="text" placeholder="Introduir le mot clé pour rechercher" />';
        $ret.= '             <span onclick="printDoc(\'reportDiv\')" class="mdl-close"><img class="tbl-ico32" src="Bundles/images/advancetools/15525195.png"/></span>';
        $ret.= '            </div>';
        /* Modal Body */
        $ret.= '            <div id="reportDiv" class="modal-body">';
        /* Report Header */
        $ret.= '            <div class="print-header">';
        $ret.= '               <table class="tbl-report">';
        $ret.= '                 <tr>';
        $ret.= '                  <th rowspan="2">';
        $ret.= '                    <img class="img-report" src="images/s/'.$recordOwner['img_name'].'" />';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Identification du document	';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Révision';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Date d\'application			';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Page	';
        $ret.= '                  </th>';
        $ret.= '                 </tr>';
        $ret.= '                 <tr>';
        $ret.= '                  <td>';
        $ret.= '                      R-MNT-FOR-0-6		';
        $ret.= '                  </td>';
        $ret.= '                  <td>';
        $ret.= '                      0	';
        $ret.= '                  </td>';
        $ret.= '                  <td>';
        $ret.= '                      15/04/2021'; //.$recordWorkOrder['date_execute'];
        $ret.= '                  </td>';
        $ret.= '                  <td>';
        $ret.= '                      1		';
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        $ret.= '                 <tr>';
        $ret.= '                  <th rowspan= "2" colspan="3">';
        $ret.= '                      N° de Bon de Travail :		' . $recordWorkOrder['code'];
        $ret.= '                  </th>';
        $ret.= '                  <th colspan="2">';
        $ret.= '                      Fiche d\'enregistrement';
        $ret.= '                  </th>';
        $ret.= '                 <tr>';
        $ret.= '                  <th>';
        $ret.= '                      N° de Devis		';
        $ret.= '                  </th>';
        $ret.= '                  <td colspan="2">';
        $ret.= '                      	'. $record['code'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        $ret.= '               </table>';
        $ret.= '               <table>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <th colspan="2">';
        $ret.= '                      Données client';
        $ret.= '                  </th>';
        $ret.= '                  <th colspan="2">';
        $ret.= '                      Données client		Données matériels	';
        $ret.= '                  </th>';
        $ret.= '                 </tr>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Raison sociale	';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordCustomer['company'];
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Marque ';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAsset['mark'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Adresse		';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordCusAddress['address'];
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Modèle';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAsset['model'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Code postal			';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordCusAddress['postal_code'];
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      N° de Série	';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAsset['serial_number'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Ville			';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordCusAddress['city'];
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      N° Interne	';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAsset['code'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      N° de Commande			';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAsset['id_purchaseorder'];
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-field">';
        $ret.= '                      Localisation	';
        $ret.= '                  </td>';
        $ret.= '                  <td class="tbl-value">';
        $ret.= '                      '. $recordAssetLoc['complex_site'] . $recordAssetLocBuild['complex_site'];
        $ret.= '                  </td>';
        $ret.= '                 </tr>';
        
        $ret.= '               </table>';
        $ret.= '            </div>';
        /* End Report Header */
        /* Report Container */
        $ret.= '            <div class="print-container">';
        $ret.= '               <table>';
        
        $ret.= '                 <tr>';
        $ret.= '                  <th>';
        $ret.= '                      Code';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Description/Désignation';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Quantité	';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Prix Unitaire';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Total HT';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      % Remise	';
        $ret.= '                  </th>';
        $ret.= '                  <th>';
        $ret.= '                      Total Remise';
        $ret.= '                  </th>';
        $ret.= '                 </tr>';
        if ($recordsConsommableDetail)
        {
            foreach($recordsConsommableDetail as $recordConsommableDetail)
            {
                //$task = myTask::getById($recordTR['id_task'])[0];
                $ret.= '                 <tr>';
                $ret.= '                    <td>';
                $ret.= $recordConsommableDetail['code'];
                $ret.= '                    </td>';
                $ret.= '                    <td>';
                $ret.= $recordConsommableDetail['title'];
                $ret.= '                    </td>';
                $ret.= '                    <td class="txt-center">';
                $ret.= $recordConsommableDetail['quantity_consommable'];
                $ret.= '                    </td>';
                $ret.= '                    <td class="txt-right">';
                $ret.= $recordConsommableDetail['unit_price_estimated']. $record['currency_symbole'] . " ";
                $ret.= '                    </td>';
                $ret.= '                    <td class="txt-right">';
                $ret.= $recordConsommableDetail['amount_cost_estimated'] . $record['currency_symbole'] . " ";
                $ret.= '                    </td>';
                $ret.= '                    <td class="txt-right">';
                $ret.= $recordConsommableDetail['discount_percent_estimated'] . " % ";
                $ret.= '                    </td>';
                $ret.= '                    <td class="txt-right">';
                $ret.= $recordConsommableDetail['discount_amount_estimated'] . $record['currency_symbole'] . " ";
                $ret.= '                    </td>';
                $ret.= '                 </tr>';
            }
        }
        /* End Total price */
        $ret.= '                 <tr>';
        $ret.= '                    <td colspan="3" class="txt-right empty-total">';
        $ret.= "   ";
        $ret.= '                    </td>';
        $ret.= '                    <th colspan="2" class="txt-right">';
        $ret.= "  Total HT: ";
        $ret.= '                    </th>';
        $ret.= '                    <td colspan="2" class="txt-right">';
        $ret.= $record['total_detail'] . "  ";
        $ret.= '                    </td>';
        
        $ret.= '                 </tr>';
        $ret.= '                 <tr>';
        $ret.= '                    <td colspan="3" class="txt-right empty-total">';
        $ret.= "   ";
        $ret.= '                    </td>';
        $ret.= '                    <th colspan="2" class="txt-right">';
        $ret.= "  Totak TVA: ";
        $ret.= '                    </th>';
        $ret.= '                    <td colspan="2" class="txt-right">';
        $ret.= $record['total_tva'] . "  ";
        $ret.= '                    </td>';
        $ret.= '                 </tr>';
        
        $ret.= '                 </tr>';
        $ret.= '                 <tr>';
        $ret.= '                    <td colspan="3" class="txt-right empty-total">';
        $ret.= "   ";
        $ret.= '                    </td>';
        $ret.= '                    <th colspan="2" class="txt-right">';
        $ret.= "  Total TTC: ";
        $ret.= '                    </th>';
        $ret.= '                    <td colspan="2" class="txt-right">';
        $ret.= $record['total_ttc'] . "  ";
        $ret.= '                    </td>';
        $ret.= '                 </tr>';
        /* End Total price */
        
        $ret.= '               </table>';
        $ret.= '            </div>';
        /* End Report Container */
        /* Report Footer */
        $ret.= '            <div class="print-footer txt-center">';
        $ret.= '                <table>';
        $ret.= '                   <tr>';
        $ret.= '                       <th>';
        $ret.= '                          Date';
        $ret.= '                       </th>';
        $ret.= '                       <th>';
        $ret.= '                          Intervenant	';
        $ret.= '                       </th>';
        $ret.= '                       <th>';
        $ret.= '                          Visa et Signature';
        $ret.= '                       </th>';
        $ret.= '                   </tr>';
        $ret.= '                   <tr>';
        $ret.= '                       <td class="txt-center">';
        $ret.= '                          ' . $record['date_add'];
        $ret.= '                       </td>';
        $ret.= '                       <td class="txt-center">';
        $ret.= '                          ' . $intervenant['full_name'];
        $ret.= '                       </td>';
        $ret.= '                       <td class="txt-center">';
        $ret.= '                          <img src="images/emp/' . $intervenant['img_signature'] . '" alt="images/emp/' . $intervenant['img_signature'] . '"/>';
        $ret.= '                       </td>';
        $ret.= '                   </tr>';
        $ret.= '                </table>';
        
        $ret.= '                <table>';
        $ret.= '                   <tr>';
        $ret.= '                   </tr>';
        $ret.= '                </table>';
        
        //$ret.= '                <p>Power by RithyThidaTévy</p>';
        $ret.= '            </div>';
        /* End Report Footer */
        $ret.= '           </div>';
        /* End Modal Body */
        
        /* Modal Footer */
        $ret.= '           <div class="row modal-footer">';
        $ret.= '                <div class="col-2"';
        $ret.= '                    <h3></h3>';
        $ret.= '                </div>';
        $ret.= '                <div class="col-2 txt-right"';
        $ret.= '                    <button onclick="hideModal(\'modalReport\')"  class="txt-black btn-form" type="button">Fermer</button>';
        //$ret.= '                  <button onclick="selectValue(\'modalReport\',\'asset\',\'txtasset\');" class="btn-form" type="button">Ok</button>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        /* End Modal Footer */
        
        return $ret;
    }
    
    public static function detailTab($idConsommable)
    {
        $record  = isset(myConsommableOrder::getById($idConsommable)[0])?myConsommableOrder::getById($idConsommable)[0]:array();
        $recordsDetails = array();
        if ($record["id_consommable_order"])
        {
            $recordsDetails = myConsommableDetail::getByWhere("`id_consommable_order`=".$record["id_consommable_order"]);
        }
        //var_dump($recordsDetails);
        $ret = '';
        
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Detail","Admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsDetails).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '      </div>';
        
        $consommabledetailFields = array(
           'date_upd'                   => RTTCore::l('Date'),
           'title'                      => RTTCore::l('Description'),
           'refference'                 => RTTCore::l('Refference'),
           'quantity_consommable'       => RTTCore::l('Destockage Quantity'),
           'unit_price_estimated'       => RTTCore::l('Unit Price'),
           'amount_cost_estimated'      => RTTCore::l('Total Detail'),
           'discount_percent_estimated' => RTTCore::l('Discount in Percent'),
           'discount_amount_estimated'  => RTTCore::l('Total Remise Déstockage'),
           'quantity_purchase'          => 'Quantité Achetée',
           'unit_price_purchase'        => 'Prix Unitaie Acheté'
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRow(\'consommabledetail\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'quantity_consommable\',\'unit_price_estimated\',\'amount_cost_estimated\',\'discount_percent_estimated\',\'discount_amount_estimated\',\'quantity_purchase\',\'unit_price_purchase\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddConsommableDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        /* Declation Variables */
        $totalDetail  = 0;
        $totalRemise  = 0;
        
        /* End Declation Variables */
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="consommabledetail">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'consommabledetail\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'quantity_consommable\',\'unit_price_estimated\',\'amount_cost_estimated\',\'discount_percent_estimated\',\'discount_amount_estimated\',\'quantity_purchase\',\'unit_price_purchase\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($consommabledetailFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'consommabledetail\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'consommabledetail" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">Action</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsDetails){
            $indRow = 0;
            foreach($recordsDetails as $recordDetail)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'consommabledetail\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'quantity_consommable\',\'unit_price_estimated\',\'amount_cost_estimated\',\'discount_percent_estimated\',\'discount_amount_estimated\',\'quantity_purchase\',\'unit_price_purchase\',\'action\']);" id="consommabledetail'.$indRow.'" type="checkbox" value="'.$recordDetail['id_consommable_detail'].'"/></td>';
                foreach($consommabledetailFields as $field => $th)
                {
                    if ($field == 'unit_price_purchase')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0,00').' '.$record['currency_symbole'].'</td>';
                    }else  if ($field == 'unit_price_estimated')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0,00').' '.$record['currency_symbole'].'</td>';
                    }else if ($field == 'discount_amount_estimated')
                    {
                        $totalAmountDiscount  = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['unit_price_estimated'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['quantity_consommable'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['discount_percent_estimated']))) / 100;
                        $totalRemise += $totalAmountDiscount;
                    
                        $ret.= '<td class="txt-right">'.(!empty($totalAmountDiscount)?RTTCore::dotToComma($totalAmountDiscount):'0,00').' '.$record['currency_symbole'].'</td>';
                    }else if ($field == 'amount_cost_estimated')
                    {
                        $totalSub      = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['unit_price_estimated'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['quantity_consommable'])));
                        $totalDetail  += $totalSub;
                        $ret.= '<td class="txt-right">'.(!empty($totalSub)?$totalSub:'0,00').' '.$record['currency_symbole'].'</td>';
                    }else if ($field == 'unit_price_purchase')
                    {
                        //$totalDetail  += RTTCore::commaToDot($recordDetail[$field]);
                        $ret.= '<td class="txt-right">'.$recordDetail[$field].' '.$record['currency_symbole'].'</td>';
                    }else if ($field == 'quantity_consommable' OR $field == 'quantity_purchase' OR $field == 'discount_percent_estimated')
                    {
                        $ret.= '<td class="txt-center">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0').'</td>';
                    }else if ($field == 'title')
                    {
                        $ret.= '<td class="autocomplete">'.$recordDetail[$field].'</td>';
                    }else if ($field != 'complex_site')
                    {
                        $ret.= '<td>'.$recordDetail[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idwoj='.$record["id_workorder_journal"].'&token'.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
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
        
        $ret.= '<input class="" id="total_detail" name="total_detail"  type="hidden" value="'.RTTCore::dotToComma($totalDetail).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '<input class="" id="total_remise" name="total_remise"  type="hidden" value="'.RTTCore::dotToComma($totalRemise).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '<input class="" id="total_exonere" name="total_exonere"  type="hidden" value="'.RTTCore::dotToComma($totalExonere).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '<input class="" id="total_tva" name="total_tva"  type="hidden" value="'.RTTCore::dotToComma($totalTVA).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '<input class="" id="total_ttc" name="total_ttc"  type="hidden" value="'.RTTCore::dotToComma($totalTTC).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        $ret.= '<input class="" id="total_soumise" name="total_soumise"  type="hidden" value="'.RTTCore::dotToComma($totalSoumise).' '.(!empty($record['currency_symbole'])?$record["currency_symbole"]:"€").'"/>';
        
        $ret.= '<script type="text/javascript">';
        $ret.= '     document.getElementById("total_detail").innerText  = "'.RTTCore::dotToComma($totalDetail).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_remise").innerText  = "'.RTTCore::dotToComma($totalRemise).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_exonere").innerText = "'.RTTCore::dotToComma($totalExonere).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_soumise").innerText = "'.RTTCore::dotToComma($totalSoumise).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_tva").innerText     = "'.RTTCore::dotToComma($totalTVA).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_ttc").innerText     = "'.RTTCore::dotToComma($totalTTC).' " + document.getElementById("currency_symbole").value;';
        $ret.= '</script>';
        
        return $ret;
    }
    public static function modalWorkorder($idPurchase)
    {
        $record = array();
        if (!empty($idPurchase)){
            $record  = isset(myConsommableOrder::getById($idPurchase)[0])?myConsommableOrder::getById($idPurchase)[0]:array();
        }
        $ret = '';
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        //$recordsWorkOrder = array();
        //if ($record){
            $recordsWorkOrder = myWorkOrder::getRecords();
        //}
        //var_dump($recordsEquipment);
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Order","Admin").'</span>';
        $ret.= '     <span class="article">'.count($recordsWorkOrder).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'workorder\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '   </div>';
        
        //$ret.= '   </div>';
        /* modal-body */
        $ret.= '   <div class="modal-body">';
        $fieldsAsset = array(
           'workorder_title'     => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description'),
           //'state'               => 'État de l\'actif',
           'categorie'           => RTTCore::l('Categorie'),
           'problem_summary'     => RTTCore::l('Problem Summery'),
        /*
           'site'                => 'Site',
           'code'                => 'Code Intern',
           'refference'          => 'Refference du fournisseur',
           'model'               => 'Modèle',
           'mark'                => 'Marque',
           'serial_number'       => 'Numéro de série'
        */
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'asset\',[\'checkbox\',\'title\' ,\'description\' ,\'state\',\'categorie\',\'id_parent\',\'site\',\'code\',\'refference\',\'model\',\'mark\',\'serial_number\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Vous êtes sûre?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '    <div class="row table">';
        $ret.= '       <table id="workorder">';
        $ret.= '          <tr>';
        $ret.= '            <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsAsset as $k => $th)
        {
            $ret.= '          <th onclick="sortTable('.$indCol.',\'workorder\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'workorder" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '             <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '          </tr>';
        /* body table */
        if ($recordsWorkOrder)
        {
            $indRow = 0;
            foreach($recordsWorkOrder as $recordWorkOrder)
            {
                $ret.= '  <tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input  onchange="changeChecked(\'workorder\',this.id)" id="workorder'.$indRow.'" type="checkbox" data-listId="'.(!empty($recordWorkOrder['id_workorder'])?$recordWorkOrder['id_workorder']:$indRow).'" data-img="'.(!empty($recordWorkOrder['img_name'])?$recordWorkOrder['img_name']:'blank.jpg').'"  value="'.(!empty($recordWorkOrder['workorder_title'])?$recordWorkOrder['workorder_title']:$indRow).'"/></td>';
                foreach($fieldsAsset as $field => $th)
                {
                    if ($field == 'subject')
                    {
                        $ret.= '<td class="w-50">'.$recordWorkOrder[$field].'</td>';
                    }else if (array_key_exists($field,$recordWorkOrder)){
                        $ret.= '<td>'.$recordWorkOrder[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_workorder"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_workorder"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '   </tr>';
            }
        }
        $ret.= '         </table>';
        $ret.= '     </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        /* End modal body */
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalWorkOrder\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","Admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalWorkOrder\',\'workorder\',\'txtworkorder\');" class="btn-form" type="button">'.RTTCore::l("Ok","Admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /*<!-- End Modal content -->*/
        return $ret;
    }
    
    public static function modalOrder($idQuotation)
    {
        
        $record  = isset(myConsommableOrder::getById($idQuotation)[0])?myConsommableOrder::getById($idQuotation)[0]:array();
        $ret = '';
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        //$recordsWorkOrder = array();
        //if ($record){
            $recordsUserOrder = myUser::getRecords();
        //}
        //var_dump($recordsEquipment);
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Order By").'</span>';
        $ret.= '     <span class="article">'.count($recordsUserOrder).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'userorder\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '   </div>';
        
        //$ret.= '   </div>';
        /* modal-body */
        $ret.= '   <div class="modal-body">';
        $fieldsAsset = array(
           'full_name'           => RTTCore::l('Principal Contact'),
           'id_company'          => RTTCore::l('Company'),
           //'state'               => 'État de l\'actif',
           'email'               => RTTCore::l('Email'),
           //'problem_summary'     => 'Résumé du problème',
        /*
           'site'                => 'Site',
           'code'                => 'Code Intern',
           'refference'          => 'Refference du fournisseur',
           'model'               => 'Modèle',
           'mark'                => 'Marque',
           'serial_number'       => 'Numéro de série'
        */
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'userorder\',[\'checkbox\',\'title\' ,\'description\' ,\'state\',\'categorie\',\'id_parent\',\'site\',\'code\',\'refference\',\'model\',\'mark\',\'serial_number\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","Admin").'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '    <div class="row table">';
        $ret.= '       <table id="userorder">';
        $ret.= '          <tr>';
        $ret.= '            <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsAsset as $k => $th)
        {
            $ret.= '          <th onclick="sortTable('.$indCol.',\'userorder\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'userorder" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '             <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '          </tr>';
        /* body table */
        if ($recordsUserOrder)
        {
            $indRow = 0;
            foreach($recordsUserOrder as $recordUserOrder)
            {
                $ret.= '  <tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input  onchange="changeChecked(\'userorder\',this.id)" id="userorder'.$indRow.'" type="checkbox" data-listId="'.(!empty($recordUserOrder['id_user'])?$recordUserOrder['id_user']:$indRow).'" data-img="'.(!empty($recordUserOrder['img_name'])?$recordUserOrder['img_name']:'blank.jpg').'"  value="'.(!empty($recordUserOrder['full_name'])?$recordUserOrder['full_name']:$indRow).'"/></td>';
                foreach($fieldsAsset as $field => $th)
                {
                    if ($field == 'id_company')
                    {
                        $company = isset(myCompany::getById($recordUserOrder[$field])[0])?myCompany::getById($recordUserOrder[$field])[0]:array();
                        $ret.= '<td class="w-50">'.$company['company'].'</td>';
                    }else{
                        $ret.= '<td>'.$recordUserOrder[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_asset"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_asset"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '   </tr>';
            }
        }
        $ret.= '         </table>';
        $ret.= '     </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        /* End modal body */
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalOrder\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","Admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalOrder\',\'userorder\',\'txtorder_by\');" class="btn-form" type="button">'.RTTCore::l("Ok","Admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /*<!-- End Modal content -->*/
        return $ret;
    }
    
    public static function modalOwner($idQuotation)
    {
        
        $record  = isset(myConsommableOrder::getById($idQuotation)[0])?myConsommableOrder::getById($idQuotation)[0]:array();
        $ret = '';
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        //$recordsWorkOrder = array();
        //if ($record){
            $recordsUserOrder = myUser::getRecords();
        //}
        //var_dump($recordsEquipment);
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Created By").'</span>';
        $ret.= '     <span class="article">'.count($recordsUserOrder).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'userorder\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '   </div>';
        
        //$ret.= '   </div>';
        /* modal-body */
        $ret.= '   <div class="modal-body">';
        $fieldsAsset = array(
           'full_name'           => RTTCore::l('Principal Contact'),
           'id_company'          => RTTCore::l('Company'),
           //'state'               => 'État de l\'actif',
           'email'               => RTTCore::l('Email'),
           //'problem_summary'     => 'Résumé du problème',
        /*
           'site'                => 'Site',
           'code'                => 'Code Intern',
           'refference'          => 'Refference du fournisseur',
           'model'               => 'Modèle',
           'mark'                => 'Marque',
           'serial_number'       => 'Numéro de série'
        */
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'userorder\',[\'checkbox\',\'title\' ,\'description\' ,\'state\',\'categorie\',\'id_parent\',\'site\',\'code\',\'refference\',\'model\',\'mark\',\'serial_number\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","Admin").'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '    <div class="row table">';
        $ret.= '       <table id="userowner">';
        $ret.= '          <tr>';
        $ret.= '            <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsAsset as $k => $th)
        {
            $ret.= '          <th onclick="sortTable('.$indCol.',\'userowner\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'userowner" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '             <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '          </tr>';
        /* body table */
        if ($recordsUserOrder)
        {
            $indRow = 0;
            foreach($recordsUserOrder as $recordUserOrder)
            {
                $ret.= '  <tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input  onchange="changeChecked(\'userowner\',this.id)" id="userowner'.$indRow.'" type="checkbox" data-listId="'.(!empty($recordUserOrder['id_user'])?$recordUserOrder['id_user']:$indRow).'" data-img="'.(!empty($recordUserOrder['img_name'])?$recordUserOrder['img_name']:'blank.jpg').'"  value="'.(!empty($recordUserOrder['full_name'])?$recordUserOrder['full_name']:$indRow).'"/></td>';
                foreach($fieldsAsset as $field => $th)
                {
                    if ($field == 'id_company')
                    {
                        $company = isset(myCompany::getById($recordUserOrder[$field])[0])?myCompany::getById($recordUserOrder[$field])[0]:array();
                        $ret.= '<td class="w-50">'.$company['company'].'</td>';
                    }else{
                        $ret.= '<td>'.$recordUserOrder[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_asset"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_asset"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '   </tr>';
            }
        }
        $ret.= '         </table>';
        $ret.= '     </div>';
        
        //$ret.= '      <p>Some text in the Modal Body</p>';
        //$ret.= '      <p>Some other text...</p>';
        //$ret.= '      <button id="myBtn2" onclick="showModal(\'myModal2\')">Open Modal</button>';
        $ret.= '   </div>';
        /* End modal body */
        $ret.= '   <div class="row modal-footer">';
        $ret.= '      <div class="col-2"';
        $ret.= '         <h3></h3>';
        $ret.= '      </div>';
        $ret.= '      <div class="col-2 txt-right"';
        $ret.= '         <button onclick="hideModal(\'modalOwner\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","Admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalOwner\',\'userowner\',\'txtcreated_by\');" class="btn-form" type="button">'.RTTCore::l("Ok","Admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /*<!-- End Modal content -->*/
        return $ret;
    }
    
    public static function sparepartTab($idConsommable)
    {
        
        $record  = isset(myConsommableOrder::getById($idConsommable)[0])?myConsommableOrder::getById($idConsommable)[0]:array();
        $ret = '';
        $recordsWorkOrder = array();
        if ($record["id_workorder"]){
            $recordsWorkOrder = mySparePart::getByWhere("`id_workorder`=".$record["id_workorder"]);
        }
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Purchase","Admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsWorkOrder).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '      </div>';
        
        $workorderFields = array(
           'date_upd'               => RTTCore::l('Date'),
           'id_workorder'           => RTTCore::l('Code'),
           'title'                  => RTTCore::l('Description'),
           'refference'             => RTTCore::l('Refference'),
           'last_price'             => RTTCore::l('Unit Price'),
           'customer'               => RTTCore::l('Customer')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRow(\'workorder_journal\',[\'checkbox\',\'user\',\'real_time\',\'workforce_total_price\',\'accomplishment_note\',\'date_execute\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddWorkOrder" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="sparepart">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'workorder_journal\',[\'checkbox\',\'user\',\'real_time\',\'workforce_total_price\',\'accomplishment_note\',\'date_execute\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($workorderFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'sparepart\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'sparepart" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsWorkOrder){
            $indRow = 0;
            foreach($recordsWorkOrder as $recordJournal)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'sparepart\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'last_price\',\'customer\',\'action\']);" id="workforce'.$indRow.'" type="checkbox" value="'.$record['id_workorder_journal'].'"/></td>';
                foreach($workorderFields as $field => $th)
                {
                    if ($field == 'workorder_total_price')
                    {
                        $consommable = myConsommable::getByWhere('`id_workorder`=' .$recordJournal['id_workorder'])[0];
                        $ret.= '<td>'.$consommable['total_expense_real'].'</td>';
                    }else if ($field == 'real_time' OR $field == 'date_execute' )
                    {
                        $ret.= '<td class="txt-center">'.$recordJournal[$field].'</td>';
                    }else if ($field == 'id_accomplishment_user')
                    {
                        $user_workorder = myUser::getById($recordJournal[$field])[0];
                        $ret.= '<td>'.$user_workorder['full_name'].'</td>';
                    }else if ($field != 'complex_site')
                    {
                        $ret.= '<td>'.$recordJournal[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idwoj='.$record["id_workorder_journal"].'&token'.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
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
        
        return $ret;
    }
    public static function hookDeStockage($args)
    {
        $ret = "";
        //$ret.= "Hook: DeStockage";
        myConsommableOrder::init();
        myConsommableDetail::init();
        $records = array();
        $fieldsDisplay = array(
           'id_consommable_order'      => RTTCore::l('ID'),
           'date_add'                  => RTTCore::l('Created Date'),
           'date_upd'                  => RTTCore::l('Updated Date'),
           'code'                      => RTTCore::l('Code'),
           'title'                     => RTTCore::l('Title'),
           'total_detail'              => RTTCore::l('Total Detail'),
           'total_ttc'                 => RTTCore::l('Total TTC'),
        );
        $records = myConsommableOrder::getRecords();
        $ret.= '<div class="row tools-title">';
        $ret.= '   <div class="col-2 txt-left">';
        //$ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>'.RTTCore::l("Return","Admin").'</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-2 txt-right">';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&act=xls"> '.RTTCore::l("XLS","Admin").'</a>';
        $ret.= '     <a class="btn" onclick="return printDoc(\'myTable\');" href=""> <i class="bx bx-printer"></i> '.RTTCore::l("Printer","Admin").'</a>';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form&token='.Tools::getValue('token').'"> <i class="bx bx-plus-circle"></i> '.RTTCore::l("Create","Admin").'</a>';
        $ret.= '   </div>';
        $ret.= '</div>';
        
        $ret.= '<div class="row">';
        $ret.= '  <div class="bg-table">';
        $ret.= '     <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("DeStockage","Admin").'</span>';
        $ret.= '         <span class="article">'.count($records).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this,\'myTable\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '     </div>';
        $ret.= '     <div class="row table">';
        $ret.= '         <table id="myTable">';
        $ret.= '            <tr>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '           <th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'myTable" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.strtoupper($th).'</th>';
            $indCol++;
        }
        $ret.= '               <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '            </tr>';
        
        /* body table */
        if ($records)
        {
            foreach($records as $record)
            {
                $ret.= '   <tr>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field != 'title'){
                        $ret.= '<td class="">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form&id='.$record["id_consommable_order"].'&token='.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form&id='.$record["id_consommable_order"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a onclick="return confirm(\''.RTTCore::l("Are you sure?","Admin").'\')" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=del&id='.$record["id_consommable_order"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= '   </tr>';
            }
        }
        $ret.= '        </table>';
        $ret.= '     </div>';
        $ret.= '  </div>';
        $ret.= '</div>'; 
        
        return $ret;
    }
}
?>