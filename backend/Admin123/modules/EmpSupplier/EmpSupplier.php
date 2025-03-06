<?php
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as RTTCore;
use classes\xLang as xLang;
use classes\myUser as myUser;
use classes\myCompany as myCompany;
use classes\myJob as myJob;

class EmpSupplier extends Module
{
    public static function form($args)
    {
        global $AdminUser, $typeEmployee, $statusUser;
        myJob::init();
        $record      = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id     = 0;
        $_POST['system_role'] = _SUP_SYSTEM_ROLE_;
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record = myUser::getById($list_id)[0];
        }
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
        
        //var_dump($record['status']);
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
        $ret.= '       <span class="frm-title">'.RTTCore::l('Employees','admin').'</span>';
        $ret.= '    </div>';
        /* message Actions */
        if (Tools::isSubmit('btnSubmitAdd')){
            $ret.= '<div class="msg">';
            $ret.= '   Action "Add" was successful';
            $ret.= '</div>';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            $list_id = myUser::save();
            $record  = myUser::getById($list_id)[0];
            Tools::refresh(10,"index.php?".Tools::newURL("id",$list_id));
        }
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= '    Action "Edit" was successful';
            $ret.= '</div>';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            if (isset($_POST['editPwd']))
            {
                $_POST['password'] = md5($_POST['password']);
            }
            //echo $_POST["problem_summary"];
            $_POST["date_upd"] = $currentDate;
            myUser::updateById(Tools::getValue('id'));
            $record = myUser::getById(Tools::getValue('id'))[0];
            //Tools::refresh();
        }
        
        $recordJob = array();
        if (isset($record['id_job']))
        {
            $recordJob = myJob::getById($record['id_job'])[0];
        }
        $recordCompa = array();
        if (isset($record['id_company']))
        {
            $recordCompa = myCompany::getById($record['id_company'])[0];
        }
        
        /* End Message Actions */
        /* Form utils */
        $ret.= '    <div class="row bg-form">';
        /* 1st Column */
        $ret.= '       <div class="col-3">';
        
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
        $ret.= '                   <label>'.RTTCore::l('Function').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                  <input class="" id="id_job" name="id_job"  type="hidden" value="'.(!empty($recordJob['id_job'])?$recordJob["id_job"]:"").'"/>';
        $ret.= '                  <input class="select" onclick="showModal(\'modalJob\')" id="txtjob" name="title"  type="text" value="'.(!empty($record['job'])?$record["job"]:(!empty($recordJob['title'])?$recordJob['title']:"")).'"/>';
        $ret.= '                  <button type="button" class="btn-select" onclick="showModal(\'modalJob\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
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
        
        $ret.= '       </div>';
        /* End 1st Column */
        /* 2nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('First Name').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="first_name" type="text" value="'.(!empty($record['first_name'])?$record["first_name"]:'').'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Last Name').'.</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="last_name" type="text" value="'.(!empty($record['last_name'])?$record["last_name"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Full Name').'.</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="full_name" type="text" value="'.(!empty($record['full_name'])?$record["full_name"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Email').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="email" type="text" value="'.(!empty($record['email'])?$record["email"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Password').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="password" type="password" value="'.(!empty($record['password'])?$record["password"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="">';
        //$ret.= '                   <label>'.RTTCore::l('Modification').'.</label>';
        //$ret.= '               </div>';
        //$ret.= '               <div class="groupfield">';
        $ret.= '                   <label class="chk-container">'.RTTCore::l('Modification').'';
        $ret.= '                       <input name="editPwd" type="checkbox" value="'.(!empty($record['editPwd'])?$record["editPwd"]:"").'" >';
        $ret.= '                       <span class="checkmark"></span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <input onchange="uploadFile(\'emp\')" class="txtUpload" type="text" id="txtUpload" name="img_name" value="'.(isset($record['img_name'])?$record['img_name']:'blank.jpg').'" placeholder=""> ';
        $ret.= '                   <label class="upload txt-center">';
        $ret.= '                       <span class="btn-form btn-upload">'.RTTCore::l('Upload','admin').'</span>';
        $ret.= '                       <input onchange="getFileName(this,\'txtUpload\',\'imgPreview\',\'emp\')" id="file" name="file" type="file"/>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <img id="imgPreview" class="img-form" src="images/emp/'.(!empty($record['img_name'])?$record['img_name']:'blank.jpg').'">';
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
        $ret.= '  </div>';
        
        /* Modal Block */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalJob" class="modal">';
        $ret.= self::modalJob();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCompany" class="modal">';
        $ret.= self::modalCompany($list_id);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* End Modal Block */
        /* Form */
        $ret.= '</form>';
        
        return $ret;
    }
    
    public static function modalJob()
    {
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $recordsJob = myJob::getRecords();
        //var_dump($recordsEquipment);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Function').'</span>';
        $ret.= '     <span class="article">'.count($recordsJob).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'job\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsStatus = array(
           'code'               => RTTCore::l('Code'),
           'title'              => RTTCore::l('Function'),
           'description'        => RTTCore::l('Description')
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRowSelect(\'job\',[\'checkbox\',\'code\',\'title\' ,\'description\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddSchedule" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="job">';
        $ret.= '        <tr>';
        $ret.= '          <th>&nbsp;</th>';
        $indCol = 0;
        foreach($fieldsStatus as $k => $th)
        {
            $ret.= '      <th onclick="sortTable('.$indCol.',\'job\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'job" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '          <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($recordsJob)
        {
            $indRow = 0;
            foreach($recordsJob as $recordJob)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($recordJob['id_job'])?$recordJob['id_job']:$indRow).'" onchange="changeChecked(\'job\',this.id)" id="job'.$indRow.'" type="checkbox" value="'.(!empty($recordJob['title'])?$recordJob['title']:$indRow).'"/></td>';
                foreach($fieldsStatus as $field => $th)
                {
                    if ($field == 'title')
                    {
                        $ret.= '<td class="w-50">'.($recordJob[$field]).'</td>';
                    }else{
                        $ret.= '<td>'.($recordJob[$field]).'</td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_job"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_job"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
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
        $ret.= '         <button onclick="hideModal(\'modalJob\')"  class="btn-form" type="button">'.RTTCore::l('Cancel','admin').'</button>';
        $ret.= '         <button onclick="selectValue(\'modalJob\',\'job\',\'txtjob\');" class="btn-form" type="button">'.RTTCore::l('Ok','admin').'</button>';
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
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
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
    
    public static function hookEmpSupplier($args)
    {
        global $typeEmployee, $statusUser;
        $ret = "";
        $table = "user";
        $records = array();
        //$ret.= "Hook: Employee";
        $fieldsDisplay = array(
            'img_name'   => RTTCore::l('Image'),
            'status'     => RTTCore::l('Status'),
            'full_name'  => RTTCore::l('Full Name'),
            'first_name' => RTTCore::l('First Name'),
            'last_name'  => RTTCore::l('Last Name'),
        );
        myUser::init();
        myCompany::init();
        myJob::init();
        $records = myUser::getByWhere('system_role=' . _SUP_SYSTEM_ROLE_);
        
        /* Tools actions */
        $ret.= '<div class="row tools-title">';
        $ret.= '   <div class="col-2 txt-left">';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>'.RTTCore::l('Return','admin').'</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-2 txt-right">';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&act=form'.'&token='.Tools::getValue('token').'"> XLS</a>';
        //$ret.= '     <a class="btn-form" onclick="return printDoc(\'myTable\');" href=""> <i class="bx bx-printer"></i> Imprimer</a>';
        //$ret.= '     <a class="btn-form" href="index.php?t='.Tools::getValue('t').'&act=form'.'&token='.Tools::getValue('token').'"> <i class="bx bx-plus-circle"></i> Créer</a>';
        $ret.= '     <select class="action" onchange="javascript:action(this.value,\'user\')" >';
        //$ret.= '         <i class="bx bx-list-ul"></i> Options';
        $ret.= '         <option class="txt-center" value="-1"> <i class="bx bx-list-check"></i>'.RTTCore::l('Actions','admin').'</option>';
        foreach($typeEmployee as $k => $val){
            $ret.= '     <option style="background-image:url(\'../Bundles/images/advancetools/10629723.png\');" value="'.$k.'"> <img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/>'.RTTCore::l($val,'admin').'</option>';
        }
        $ret.= '     </select>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Tools actions */
        
        $ret.= '<div class="row">';
        $ret.= ' <div class="bg-table">';
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Supplier','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\'user\')" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '     <span class="article flt-right">'.count($records).' '.RTTCore::l('Records','admin').'</span>';
        $ret.= '  </div>';
        
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="user">';
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
                    if (array_key_exists('status',$record) && $field == 'status')
                    {
                        $ret.= '<td class="txt-center stat '.strtolower($statusUser[$record[$field]]).'">'.$statusUser[$record[$field]].'</td>';
                    }else if (array_key_exists('title',$record) && $field == 'title')
                    {
                        $ret.= '<td class="w-50">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
                    }
                }
                $ret.= '  <td class="txt-center">';
                $ret.= '     <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_".$table].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/></a>';
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