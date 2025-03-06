<?php
use classes\Module as Module; 
use Bundles\classes\WebCore as RTTCore;
use classes\Tools as Tools;
use classes\myCompany as myCompany;
use classes\myAddress as myAddress;
use classes\myOpeningHours as myOpeningHours;
use classes\xLang as xLang;

class Company extends Module
{    
    public static function hookCompany($args)
    {
        global $AdminUser; 
        //var_dump($AdminUser['id_company']);
        $list_id = 0;
        $record  = array();
        $messages    = array();
        myCompany::init();
        myAddress::init();
        myOpeningHours::init();
        
        /* Start QR Code */
        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../images/temp'.DIRECTORY_SEPARATOR;
        
        //html PNG location prefix
        $PNG_WEB_DIR = 'images/temp/';
        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR))
        {
            //mkdir($PNG_TEMP_DIR);
            mkdir($PNG_TEMP_DIR, 0777, true);
        }
        $filename = $PNG_TEMP_DIR.'test.png';
        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        {
            $errorCorrectionLevel = $_REQUEST['level'];    
        }
        $matrixPointSize = 4;
        if (isset($_REQUEST['size']))
        {
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
        }
        if (isset($_REQUEST['workorder_title'])) { 
            //echo $_REQUEST['img_qr'];
            //it's very important!
            if (trim($_REQUEST['workorder_title']) == ''){
                die('data cannot be empty! <a href="?">back</a>');
            }
            // user data
            $filename = $PNG_TEMP_DIR.$_REQUEST['workorder_title'].md5($_REQUEST['workorder_title'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
            QRcode::png($_REQUEST['workorder_title'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            //$fields = array('img_qr');
            //myEquipment::updateByFields($fields,Tools::getValue('id'));
        } else {    
            
            //default data
            $ret.= '<span class="hide">You can provide data in GET parameter: <a href="?data=like_that">like that</a></span>';
            QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        }    
        //display generated file
        /*
        //$ret.= $PNG_WEB_DIR.basename($filename);
        $ret.= '<img class="hide" src="Bundles/phpqrcode/'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
        //config form
        $ret.= '<form class="hide" action="index.php?'.Tools::newUrl('t',Tools::getValue('t')).'" method="post"> Data:&nbsp;<input id="txtqrcode" name="img_qr" value="'.(isset($_REQUEST['img_qr'])?htmlspecialchars($_REQUEST['img_qr']):'PHP QR Code :)').'" />&nbsp;ECC:&nbsp;<select name="level"><option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option><option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option><option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option><option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option></select>&nbsp;Size:&nbsp;<select name="size">';
        for($i=1;$i<=10;$i++){
            $ret.= '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';
        }
        $ret.= '</select>&nbsp;<input name="generateqr" type="submit" value="GENERATE"></form><hr/>';
        // benchmark
        //QRtools::timeBenchmark();
        /* End QRCode */
        
        $ret = "";
        //$ret.= "Hook: Company";
        $btnType = 'Add';
        if (isset($AdminUser['id_company']))
        {
            $btnType = 'Edit';
            $list_id = $AdminUser['id_company'];
            $record  = (myCompany::getById($list_id) !== null)?myCompany::getById($list_id)[0]:array();
            if (!$record){
                $btnType = 'Add';
            }
            //Tools::refresh(10,"index.php?".Tools::newURL("id",$list_id));
            //$messages[$btnType] = 'Success';
        }
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record  = (myCompany::getById($list_id) !== null)?myCompany::getById($list_id)[0]:array();
            if (!$record){
                $btnType = 'Add';
            }
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST["system_role"] = 1;
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            $list_id = myCompany::save();
            $recordDetail = myAddress::getByWhere("`id_company`=".$list_id)[0];
            $_POST["id_company"] = $list_id;
            if (!empty($recordDetail))
            {
                myAddress::updateById($list_id);
            }else{
                myAddress::save();
            }
            $record  = myCompany::getById($list_id)[0];
            $messages[$btnType] = 'Success';
            //Tools::refresh(10,"index.php?".Tools::newURL("id",$list_id));
        }
        if (Tools::isSubmit('btnSubmitEdit')){
            
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            //echo $_POST["problem_summary"];
            $_POST["system_role"] = 1;
            $_POST["date_upd"] = $currentDate;
            myCompany::updateById($list_id);
            $recordDetail = myAddress::getByWhere("`id_company`=".$list_id)[0];
            $_POST["id_company"] = $list_id;
            //var_dump($_POST["department"]);
            if (!empty($recordDetail))
            {
                myAddress::updateById($list_id);
            }else{
                myAddress::save();
            }
            $record = myCompany::getById($list_id)[0];
            //Tools::refresh();
            $messages[$btnType] = 'Success';
        }
        
        if (Tools::isSubmit('btnSubmitOpeningHours')){
            //echo 'btnSubmitOpeningHours';
            //var_dump($_POST['arrid_opening_hours']);
            
            if(isset($_POST['arrid_opening_hours'])){
                //$ret.= '<div class="msg">';
                //$ret.= '   Action "Add Task Report" was successful';
                //$ret.= '</div>';
                $indRow = 0;
                
                foreach($_POST['arrid_opening_hours'] as $id_record)
                {
                    //$ret.= '<br/>'.$id_task_report .' Equipment: ' . $_POST['arrestimated_time'];
                    if ($id_record == 0){
                        $_POST['id_company']          = $list_id;
                        $_POST['code']                = $_POST['arrcode'][$indRow];
                        $_POST['day']                 = $_POST['arrday'][$indRow];
                        $_POST['hours']               = $_POST['arrhours'][$indRow];
                        $_POST['date_add']            = $currentDate;
                        $_POST['date_upd']            = $currentDate;
                        
                        myOpeningHours::save();
                    }elseif ($id_record >= 1){
                        $_POST['id_company']          = $list_id;
                        $_POST['code']                = $_POST['arrcode'][$indRow];
                        $_POST['day']                 = $_POST['arrday'][$indRow];
                        $_POST['hours']               = $_POST['arrhours'][$indRow];
                        $_POST['date_add']            = $currentDate;
                        $_POST['date_upd']            = $currentDate;
                        
                        $fields = array('id_company','code','day','hours','date_upd');
                        myOpeningHours::updateByFields($id_record,$fields);
                    }
                    $indRow++;
                }
            
                //$recordsSparePart = mySparePart::getByField('id_opening_hour',$list_id);
                //var_dump($recordsSparePart);
                //$ret.= '</div>';
                $messages[$btnType] = 'Success';
        
            }
            $ret.= '<script type="text/javascript">';
            $ret.= 'var currentSubTab = \'idopeninghourstab\';';
            $ret.= '</script>';
        }
        
        $recordLang = array();
        if (array_key_exists('id_lang',$record) && $record['id_lang']){
            $recordLang = xLang::getById($record['id_lang']);
        }
        
        //var_dump($recordLang['id_lang']);
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
        /* Form */
        $ret.= '  <div class="form">';
        /* Form Title */
        $ret.= '    <div class="row">';
        $ret.= '       <span class="frm-title">'.RTTCore::l('Company').'</span>';
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
        $ret.= '    <div class="bg-form">';
        $ret.= '       <div class="row">';
        /* 1st Column */
        $ret.= '         <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Type').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="type" type="text" value="'.(!empty($record['type'])?$record["type"]:RTTCore::l($typeEmployee[Tools::getValue('tp')],'admin')).'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Status').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="status" type="text" value="'.(!empty($record['status'])?RTTCore::l($statusUser[$record["status"]]):RTTCore::l($statusUser[$record["status"]])).'"/>';
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
        $ret.= '                   <label>'.RTTCore::l('System Role').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="system_role" type="text" value="'.(!empty($record['system_role'])?$record["system_role"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="">';
        $chkActive = "";
        if (!empty($record['active']) && $record['active'] !== '0'){
            $chkActive = "checked=\"checked\"";
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
        
        $ret.= '       </div>';
        /* End 1st Column */
        /* 2nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Company').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="company" type="text" value="'.(!empty($record['company'])?$record["company"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Siren').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="siren" type="text" value="'.(!empty($record['siren'])?$record["siren"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Siret').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="siret" type="text" value="'.(!empty($record['siret'])?$record["siret"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('TVA Number').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="tva_number" type="text" value="'.(!empty($record['tva_number'])?$record["tva_number"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Category').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="categorie_company" type="text" value="'.(!empty($record['categorie_company'])?$record["categorie_company"]:"").'"/>';
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
        /* 3rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <input onchange="uploadFile(\'s\')" class="txtUpload" type="text" id="txtUpload" name="img_name" value="'.(isset($record['img_name'])?$record['img_name']:'blank.jpg').'" placeholder=""> ';
        $ret.= '                   <label class="upload" >';
        $ret.= '                       <input onchange="getFileName(this,\'txtUpload\',\'imgPreview\',\'s\')" id="file" name="file" type="file"/>';
        $ret.= '                       <span class="btn-form btn-upload txt-center">'.RTTCore::l('Upload','admin').'</span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <img id="imgPreview" class="img-form" src="images/s/'.(!empty($record['img_name'])?$record['img_name']:'blank.jpg').'">';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1 txt-center">';
        $ret.= '               <div class="col-1">';
        $ret.= '                   <button name="generateqr" class="btn-form" type="submit">'.RTTCore::l('Generate QR','admin').'</button>';
        $ret.= '                   <input class="inpW100" id="txtqrcode" name="img_qr"  type="hidden" value="'.(isset($_REQUEST['img_qr'])?htmlspecialchars($_REQUEST['img_qr']):(!empty($record["img_qr"])?$record["img_qr"]:'PHP QR Code :)')).'"/>';
        $ret.= '               </div>';
        $ret.= '               <div class="col-1">';
        $ret.= '                   <img class="" src="'.$PNG_WEB_DIR.basename($filename).'">';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 3rd Column */
        $ret.= '    </div>';
        /* Tab */
        $ret.= '    <div class="tab">';
        $ret.= '      <button type="button" class="tablinks defaultTab" onclick="openTab(event, \'contacttab\')" id="defaultTab">'.RTTCore::l('Contact','admin').'</button>';
        $ret.= '      <button id="idaccomplishmenttab" type="button" class="tablinks" onclick="openTab(event, \'addresstab\')">'.RTTCore::l('Address','admin').'</button>';
        $ret.= '      <button id="idopeninghourstab" type="button" class="tablinks" onclick="openTab(event, \'openinghourstab\')">'.RTTCore::l('Openning Hours','admin').'</button>';
        
        $ret.= '    </div>';
        
        /* General Tab */
        $ret.= '    <div id="contacttab" class="tabcontent">';
        $ret.= self::contactTab($list_id);
        $ret.= '    </div>';
        /* End General Tab */
        
        /* Address Tab */
        $ret.= '    <div id="addresstab" class="tabcontent">';
        $ret.= self::addressTab($list_id);
        $ret.= '    </div>';
        /* End Address Tab */
        
        /* Opening Hours Tab */
        $ret.= '    <div id="openinghourstab" class="tabcontent">';
        $ret.= self::openingHoursTab($list_id);
        $ret.= '    </div>';
        /* End Opening Hours Tab */
        
        /* End Tab */
        $ret.= '   </div>';
        $ret.= '  </div>';
        
        
        /* Modal Block */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalLang" class="modal">';
        $ret.= self::modalLang();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* End Modal Block */
        
        $ret.= '</form>';
        /* End Form */
        return $ret;
    }
    
    public static function openingHoursTab($idCompany)
    {
        
        $record  = myCompany::getById($idCompany)[0];
        $ret = '';
        $recordsJournal = array();
        if ($record["id_company"]){
            $recordsJournal = myOpeningHours::getByWhere("`id_company`=".$record["id_company"]);
        }
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l('Openning Hours','admin').'</span>';
        $ret.= '         <span class="article">'.count($recordsJournal).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '      </div>';
        
        $journalFields = array(
           'day'                => RTTCore::l('Days'),
           'hours'              => RTTCore::l('Opening Hours'),
           'code'               => RTTCore::l('Code'),
           //'accomplish_note'        => 'Note d\'achèvement',
           //'date_execute'           => 'Date de journalisation'
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRow(\'opening_hours\',[\'checkbox\',\'day\',\'hours\',\'code\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitOpeningHours" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="opening_hours">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'opening_hours\',[\'checkbox\',\'day\',\'hours\',\'code\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($journalFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'opening_hours\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'opening_hours" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l('Actions','admin').'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsJournal){
            $indRow = 0;
            foreach($recordsJournal as $recordJournal)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'opening_hours\',[\'checkbox\',\'day\',\'hours\',\'code\',\'action\']);" id="opening_hours'.$indRow.'" type="checkbox" value="'.$recordJournal['id_opening_hour'].'"/></td>';
                foreach($journalFields as $field => $th)
                {
                    if ($field == 'workorder_total_price')
                    {
                        $quotation = myQuotation::getByWhere('`id_workorder`=' .$recordJournal['id_opening_hour'])[0];
                        $ret.= '<td>'.$quotation['total_expense_real'].'</td>';
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
    
    public static function contactTab($list_id)
    {
        $record = array();
        if (!empty($list_id))
        {
            $record = myCompany::getById($list_id)[0];
        }
        $ret = "";
        //$ret.= "Contact Tab";
        $ret.= '    <div class="row">';
        /* 1st Column */
        $ret.= '         <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Principal Contact').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="principal_contact" type="text" value="'.(!empty($record['principal_contact'])?$record["principal_contact"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Website').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="website" type="text" value="'.(!empty($record['website'])?$record["website"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Fax').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="fax" type="text" value="'.(!empty($record['fax'])?$record["fax"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 1st Column */
        /* 2nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Principal Telephone').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="telephone_principal" type="text" value="'.(!empty($record['telephone_principal'])?$record["telephone_principal"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Secondary Telephone').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="telephone_seconde" type="text" value="'.(!empty($record['telephone_seconde'])?$record["telephone_seconde"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Email').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="email_principal" type="text" value="'.(!empty($record['email_principal'])?$record["email_principal"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Secondary Email').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="email_secondaire" type="text" value="'.(!empty($record['email_secondaire'])?$record["email_secondaire"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 3rd Column */
        $ret.= '    </div>';
        return $ret;
    }
    
    public static function addressTab($list_id)
    {
        $record = array();
        if (!empty($list_id))
        {
            $record = myAddress::getByWhere("`id_company`=".$list_id)[0];
        }
        $ret = "";
        //$ret.= "Contact Tab";
        $ret.= '    <div class="row">';
        /* 1st Column */
        $ret.= '         <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Address').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="address" type="text" value="'.(!empty($record['address'])?$record["address"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Commune').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="commune" type="text" value="'.(!empty($record['commune'])?$record["commune"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Department').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="department" type="text" value="'.(!empty($record['department'])?$record["department"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 1st Column */
        /* 2nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Region').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="region" type="text" value="'.(!empty($record['region'])?$record["region"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('City').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="city" type="text" value="'.(!empty($record['city'])?$record["city"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Postal Code').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="postal_code" type="text" value="'.(!empty($record['postal_code'])?$record["postal_code"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Country').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="country" type="text" value="'.(!empty($record['country'])?$record["country"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Time Zone').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="time_zone" type="text" value="'.(!empty($record['time_zone'])?$record["time_zone"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 3rd Column */
        $ret.= '    </div>';
        
        return $ret;
    }
}
?>