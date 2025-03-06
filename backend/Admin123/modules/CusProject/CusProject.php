<?php
use classes\WebCore as RTTCore;
use classes\Module as Module;
use classes\Tools as  Tools;
use classes\myProject as myProject;
use classes\myOrder as myOrder;
use classes\myUser as myUser;
use classes\myCompany as myCompany;
use classes\myArticle as myArticle;

class CusProject extends Module
{
    public static function form()
    {
        $ret = '';
        $record      = array();
        $messages    = array();
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id = 0;
        $btnType = 'Add';
        
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record = myProject::getById($list_id)[0];
        }
        
        if (Tools::isSubmit('btnSubmitAdd')){
            if (isset($_POST['tacit_renewal']))
            {
                $_POST['tacit_renewal'] = 1;
            }
            
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST['id_status']           = _CUS_SYSTEM_ROLE_;
            $_POST['date_add']            = $currentDate;
            $_POST['date_upd']            = $currentDate;
                        
            $list_id = myProject::save();
            $messages[$btnType] = 'success';
            $record = myProject::getById($list_id)[0];
        }
        if (Tools::isSubmit('btnSubmitEdit')){
            if (isset($_POST['tacit_renewal']))
            {
                $_POST['tacit_renewal'] = 1;
            }
            
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST['id_status']    = _CUS_SYSTEM_ROLE_;
            $_POST["date_upd"]     = $currentDate;
            myProject::updateById(Tools::getValue('id'));
            $messages[$btnType] = 'success';
            $record = myProject::getById(Tools::getValue('id'))[0];
        }
        //echo $record['id_company'];
        $recordCompa = array();
        if (isset($record['id_company']))
        {
            $recordCompa = myCompany::getById($record['id_company'])[0];
        }
        
        $recordCus = array();
        if (isset($record['id_supplier'])){
            $recordCus = myUser::getById($record['id_supplier'])[0];
            //var_dump($recordCus);
        }
        
        $recordArt = array();
        if (isset($record['id_article'])){
            $recordArt = myArticle::getById($record['id_article'])[0];
            //var_dump($recordArt);
        }
        
        $ret.= '<form action="index.php?'.Tools::newUrl('t',Tools::getValue('t')).'" method="POST" class="">';
        $ret.= '<div class="row tools-title">';
        $ret.= '   <div class="col-2 txt-left">';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>'.RTTCore::l("Return","Admin").'</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-2 txt-right">';
        $ret.= '        <button name="btnSubmit'.$btnType.'" type="submit" class="btn-form"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/>&ensp;'.RTTCore::l("Save","Admin").'</button>';
        $ret.= '   </div>';
        $ret.= '</div>';
        
        /* Start Form */
        $ret.= '  <div class="form">';
        /* Form Title */
        $ret.= '    <div class="row">';
        $ret.= '       <span class="frm-title">'.RTTCore::l("Contract","Admin").'</span>';
        $ret.= '    </div>';
        /* End Form Title */
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
        /* Form Background */
        $ret.= '    <div class="row bg-form">';
        
        $ret.= '       <div class="row">';
        /* Left Side */
        $ret.= '        <div class="col-2">';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Code").'</label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="code" type="text" value="'.(!empty($record['code'])?$record["code"]:"").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Title").' </label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="title" type="text" value="'.(!empty($record['title'])?$record["title"]:"").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Start Date").' </label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="date_start" type="date" value="'.(!empty($record['date_start'])?$record["date_start"]:"").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("End Date").'</label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="date_end" type="date" value="'.(!empty($record['date_end'])?$record["date_end"]:"").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="">';
        $chkActive = "";
        if (!empty($record['tacit_renewal']) && $record['tacit_renewal'] !== 1){
            $chkActive = 'checked="checked"';
        }
        //$ret.= '                   <label>'.RTTCore::l('Modification').'.</label>';
        //$ret.= '               </div>';
        //$ret.= '               <div class="groupfield">';
        $ret.= '                   <label class="chk-container">'.RTTCore::l('Tacit Renewal').'';
        $ret.= '                       <input name="tacit_renewal" type="checkbox" value="'.(!empty($record['tacit_renewal'])?$record["tacit_renewal"]:0).'" '.$chkActive.'>';
        $ret.= '                       <span class="checkmark"></span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="">';
        $chkActive = "";
        if (!empty($record['active']) && $record['active'] !== 1){
            $chkActive = 'checked="checked"';
        }
        //$ret.= '                   <label>'.RTTCore::l('Modification').'.</label>';
        //$ret.= '               </div>';
        //$ret.= '               <div class="groupfield">';
        $ret.= '                   <label class="chk-container">'.RTTCore::l('Active').'';
        $ret.= '                       <input name="active" type="checkbox" value="'.(!empty($record['active'])?$record["active"]:0).'" '.$chkActive.'>';
        $ret.= '                       <span class="checkmark"></span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '        </div>';
        /* End Left Side */
        /* Right Side */
        $ret.= '        <div class="col-2">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Company').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                  <input class="" id="id_company" name="id_company"  type="hidden" value="'.(!empty($recordCompa['id_company'])?$recordCompa["id_company"]:"").'"/>';
        $ret.= '                  <input class="select" onclick="showModal(\'modalCompany\')" id="txtcompany" name="account_title"  type="text" value="'.(!empty($record['company'])?$record["company"]:(!empty($recordCompa['company'])?$recordCompa['company']:"")).'"/>';
        $ret.= '                  <button type="button" class="btn-select" onclick="showModal(\'modalCompany\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Customer').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_user" name="id_supplier"  type="hidden" value="'.((array_key_exists('id_supplier',$record) && !empty($record['id_supplier']))?$record['id_supplier']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalCustomer\')" id="txtuser" name="lang"  type="text" value="'.((!empty($record) && array_key_exists('customer',$record) && !empty($record['customer']))?$record["customer"]:(!empty($recordCus['full_name'])?$recordCus['full_name']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalCustomer\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Article').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_article" name="id_article"  type="hidden" value="'.((array_key_exists('id_article',$record) && !empty($record['id_article']))?$record['id_article']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalArticle\')" id="txtarticle" name="lang"  type="text" value="'.((!empty($record) && array_key_exists('customer',$record) && !empty($record['customer']))?$record["customer"]:(!empty($recordArt['title'])?$recordArt['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalArticle\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Amount").' </label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="amount_ht" type="text" value="'.(!empty($record['amount_ht'])?number_format($record["amount_ht"],2):"0.00").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Billing Rhythm").' </label>';
        $ret.= '                </div>';
        $ret.= '                <div class="groupfield">';
        $ret.= '                   <input name="billing_rhythm" type="text" value="'.RTTCore::l(!empty($record['billing_rhythm'])?$record["billing_rhythm"]:"monthly").'"/>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        
        $ret.= '            <div class="col-1">';
        $ret.= '                <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l("Description").'</label>';
        $ret.= '                </div>';
        //$ret.= '            </div>';
        //$ret.= '            <div class="col-1">';
        $ret.= '                <div class="">';
        $ret.= '                   <textarea class="h-5" name="termination_notice">'.(!empty($record['termination_notice'])?$record["termination_notice"]:"").'</textarea>';
        $ret.= '                </div>';
        //$ret.= '            </div>';
        $ret.= '        </div>';
        /* End Right Side */
        $ret.= '      </div>';
        
        $ret.= '      <div class="row pd-15">';
        //
        $ret.= '      </div>';
        
        //Tabs
        $ret.= '   <div class="tab">';
        $ret.= '     <button id="idjournalworktab" type="button" type="button" class="tablinks defaultTab"  id="defaultTab" onclick="openTab(event, \'journalworktab\')">'.RTTCore::l("Journal","Admin").'</button>';
        $ret.= '            <button type="button" class="tablinks" onclick="openTab(event, \'attachementtab\')">'.RTTCore::l("Files","Admin").'</button>';
        $ret.= '   </div>';
        
        /* Pièces Tab */
        $ret.= '   <div id="attachementtab" class="tabcontent">';
        $ret.= self::attachmentTab($list_id);
        $ret.= '   </div>';
        /* End Pièces */
        
        /* Journal work Tab */
        $ret.= self::journalTab();
        /* End Journal Tab */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalAttach" class="modal">';
        /*<!-- Modal content -->*/
        $ret.= self::modalAttach($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCustomer" class="modal">';
        $ret.= self::modalCustomer();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCompany" class="modal">';
        $ret.= self::modalCompany($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalArticle" class="modal">';
        $ret.= self::modalArticle($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        $ret.= '    </div>';
        /*End Form Background */
        $ret.= '   </div>';
        /*End Form */
        $ret.= '</form>';
        return $ret;
    }
    
    public static function journalTab()
    {
        $ret = '';
        
        $recordsJournal = array();
        if ($record){
            $recordsJournal = myOrder::getByWhere("`id_project`=".$record["id_project"]);
        }
        //var_dump($recordsJournal);
        $ret.= '   <div id="journalworktab" class="tabcontent">';
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Order","Admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsJournal).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '      </div>';
        
        $journalFields = array(
           'id_accomplishment_user' => RTTCore::l('Employee'),
           'real_time'              => RTTCore::l('Worked Hours'),
           'workorder_total_price'  => RTTCore::l('Total Detail'),
           'accomplishment_note'    => RTTCore::l('Completed Note'),
           'date_execute'           => RTTCore::l('Created Date')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRow(\'workorder_journal\',[\'checkbox\',\'user\',\'real_time\',\'workforce_total_price\',\'accomplishment_note\',\'date_execute\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddWorkForce" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="workorder_journal">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'workorder_journal\',[\'checkbox\',\'user\',\'real_time\',\'workforce_total_price\',\'accomplishment_note\',\'date_execute\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($journalFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'workorder_journal\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'workorder_journal" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsJournal){
            $indRow = 0;
            foreach($recordsJournal as $recordJournal)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'workorder_journal\',[\'checkbox\',\'user\',\'real_time\',\'workorder_total_price\',\'accomplishment_note\',\'date_execute\',\'action\']);" id="workforce'.$indRow.'" type="checkbox" value="'.$record['id_workorder_journal'].'"/></td>';
                foreach($journalFields as $field => $th)
                {
                    if ($field == 'real_time')
                    {
                        $ret.= '<td class="txt-center">'.$recordJournal[$field].'</td>';
                    }else if ($field == 'id_accomplishment_user')
                    {
                        $user_workorder = myUser::getById($recordJournal[$field])[0];
                        $ret.= '<td>'.$user_workorder['full_name'].'</td>';
                    }else
                    if ($field != 'complex_site')
                    {
                        $ret.= '<td>'.$recordJournal[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idwoj='.$record["id_workorder_journal"].'&token'.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_installation"].'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_installation"].'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= ' </tr>';
            }
        }else {
            $ret.= '     <tr class="tbl-empty">';
            $ret.= '     </tr>';
        }
        $ret.= '       </table>';
        $ret.= '     </div>';
        $ret.= '   </div>';
        
        return $ret;
    }
    
    public static function attachmentTab($idWorkOrder)
    {
        
        $record  = myProject::getById($idWorkOrder)[0];
        $ret = '';
        $recordsAttachement = array();
        $recordsAttachement = RTTCore::getFiles("Upload/C".$idWorkOrder);
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Files","Admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsAttachement).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '      </div>';
        
        $attachFields = array(
           'date_add'       => RTTCore::l('Date'),
           'title'          => RTTCore::l('Title'),
           'file_type'      => RTTCore::l('Type'),
           'size'           => RTTCore::l('Size'),
           //'preview'   => 'Aperçu'
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="showModal(\'modalAttach\')" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/ic_upload.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAttach" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="workforce">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'workforce\',[\'checkbox\',\'description\',\'distributed_to\',\'estimated_time\',\'passed_time\',\'result\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($attachFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'workforce\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'workforce" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsAttachement){
            $indRow = 0;
            foreach($recordsAttachement as $record)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'workforce\',[\'checkbox\',\'description\',\'distributed_to\',\'estimated_time\',\'passed_time\',\'result\',\'action\']);" id="workforce'.$indRow.'" type="checkbox" value="'.$record['id_workforce'].'"/></td>';
                foreach($attachFields as $field => $th)
                {
                    if ($field != 'title')
                    {
                        $ret.= '<td>'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td><a target="_blank" href="Upload/C'.$idWorkOrder.'/'.$record["title"].'" >'.$record[$field].'</a></td>';
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
    
    public static function modalAttach($idPurchase)
    {
        $table = 'lang';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = RTTCore::getFiles("Upload/C".$idPurchase);
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Files","admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'date_add'       => RTTCore::l('Date'),
           'title'          => RTTCore::l('Title'),
           'file_type'      => RTTCore::l('Type'),
           'size'           => RTTCore::l('Size')
        );
        $ret.= '     <div class="tbl-tools">';
        //$ret.= '        <input type="text" class="">';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '           <div class="col-1">';
        //$ret.= '               <div class="col-2 txt-left">';
        $ret.= '                   <input onchange="uploadFile(\'equ\')" class="attach-file" type="text" id="txtUpload" name="img_name" value="" placeholder=""> ';
        $ret.= '                   <label class="" >';
        $ret.= '                       <input onchange="getFileName(this,\'txtUpload\',\'imgPreview\',\'upload\')" id="file" name="file" type="file"/>';
        $ret.= '                       <span class="btn-attach"><img class="tbl-ico16" src="../Bundles/images/advancetools/237510.png"/></span>';
        $ret.= '                   </label>';
        //$ret.= '               </div>';
        //$ret.= '               <div class="col-2 txt-left">';
        $ret.= '                   <button onclick="uploadFile(\'../Upload/C'.$idPurchase.'\')"  name="btnSubmitAttach" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/2344147.png"/></button>';
        //$ret.= '               </div>';
        $ret.= '           </div>';
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
        $ret.= '         <button onclick="hideModal(\'modalAttach\')"  class="btn-form" type="button">'.RTTCore::l("Close","admin").'</button>';
        //$ret.= '         <button onclick="selectValue(\'modalLang\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l("Ok","admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalCompany()
    {
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myCompany::getRecords();
        //var_dump($recordsEquipment);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Company').'</span>';
        $ret.= '     <span class="article">'.count($recordsStatus).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'company\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fields = array(
           'code'                => RTTCore::l('Code'),
           'company'             => RTTCore::l('Company'),
           'email_principal'     => RTTCore::l('Email')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'company\',[\'checkbox\',\'code\',\'company\' ,\'email_principal\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="company">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fields as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\'company\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'company" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_company'])?$record['id_company']:$indRow).'" onchange="changeChecked(\'company\',this.id)" id="company'.$indRow.'" type="checkbox" value="'.(!empty($record['company'])?$record['company']:$indRow).'"/></td>';
                foreach($fields as $field => $th)
                {
                    if ($field == 'company')
                    {
                        $ret.= '<td class="w-50">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_company"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_company"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
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
        $ret.= '         <button onclick="hideModal(\'modalCompany\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalCompany\',\'company\',\'txtcompany\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalCustomer()
    {
        $table = 'user';
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
    
    public static function modalArticle()
    {
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = myArticle::getRecords();
        $table   = "article";
        //var_dump($recordsEquipment);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Company').'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'company\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fields = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'description'         => RTTCore::l('Description')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\''.$table.'\',[\'checkbox\',\'code\',\'company\' ,\'email_principal\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="'.$table.'">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fields as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_article'])?$record['article']:$indRow).'" onchange="changeChecked(\'id_article\',this.id)" id="article'.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
                foreach($fields as $field => $th)
                {
                    if ($field == 'title')
                    {
                        $ret.= '<td class="w-50">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_article"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\''.RTTCore::l('Are you sure?','admin').'\')" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_article"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
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
        $ret.= '         <button onclick="hideModal(\'modalArticle\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalArticle\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function hookCusProject($args)
    {
        global $defaultCurrency;
        $ret = "";
        $records = array();
        myProject::init();
        
        $fieldsDisplay = array(
           'id_project'       => RTTCore::l('ID'),
           'code'             => RTTCore::l('Code'),
           'title'            => RTTCore::l('Title'),
           'date_start'       => RTTCore::l('Start Date'),
           'date_end'         => RTTCore::l('End Date'),
           'amount_ht'        => RTTCore::l('Total HT'),
           'billing_rhythm'   => RTTCore::l('Billing Rhythm'),
           'description'      => RTTCore::l('Description'),
        );
        
        $records = myProject::getByWhere('`id_status`='._CUS_SYSTEM_ROLE_);
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
        if (Tools::getValue('act')=='del')
        {
            myProject::deleteById(Tools::getValue('id'));
            $records = myProject::getRecords();
        }
        $ret.= '<div class="row">';
        $ret.= '<div class="bg-table">';
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Contract","Admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","Admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","Admin").'" />';
        $ret.= '  </div>';
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="myTable">';
        $ret.= '<tr>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '<th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'myTable" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '<th class="bg-orange">'.RTTCore::l("Actions","Admin").'</th>';
        $ret.= '</tr>';
        
        /* body table */
        if ($records)
        {
            foreach($records as $record)
            {
                $ret.= '<tr>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ($field == 'billing_rhythm' OR $field == 'id_project')
                    {
                        $ret.= '<td class="txt-center">'.$record[$field].'</td>';
                    }else if ($field == 'amount_ht')
                    {
                        $ret.= '<td class="txt-right">'.number_format($record[$field],2).' '.$defaultCurrency.'</td>';
                    }else if ( $field == 'date_start' OR $field == 'date_end' )
                    {
                        $ret.= '<td class="txt-center">'.date('d-m-Y',strtotime($record[$field])).'</td>';
                    }else if ($field != 'title'){
                        $ret.= '<td class="">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form&id='.$record["id_project"].'&token='.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }
                }
                $ret.= '<td class="txt-center">';
                $ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_project"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_project"].'&token='.Tools::getValue('token').'" onclick="return confirm(\'Vous êtes sûre?\')"><img class="tbl-ico32" src="../Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '</td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '</table>';
        $ret.= '</div>';
        $ret.= '</div>';
        $ret.= '   </div>'; 
        return $ret;
    }
}
?>