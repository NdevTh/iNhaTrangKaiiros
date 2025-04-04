<?php
use classes\Module as Module; 
use Bundles\classes\WebCore as RTTCore;
use classes\Tools as Tools;
use classes\myArticle as myArticle;
use classes\myCompany as myCompany;
use classes\myPurchaseOrder as myPurchaseOrder;
use classes\myArticleCategorie as myArticleCategorie;
use classes\xLang as xLang;
use classes\myBillOfMaterial as myBillOfMaterial;
use classes\myPurchaseDetail as myPurchaseDetail;

class Product extends Module
{
    public static function form()
    {
        global $actions;
        $ret         = '';
        $record      = array();
        $messages    = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id     = 0;
        $bom_id      = 0;
        myArticleCategorie::init();
        myPurchaseOrder::init();
		myPurchaseDetail::init();
		myCompany::init();
        $newCode = 'RM00001';
        //echo RTTCore::l("Test");
		//$ret .= myArticle::getByWhere('id_categorie='._RAW_MATERIAL_)[0]['code'];
        if (myArticle::getByWhere('id_categorie='._RAW_MATERIAL_) && Tools::getValue("id") == 0 )
        {
			$indRow = (count(myArticle::getByWhere('id_categorie='._RAW_MATERIAL_)) - 1);
			$number = ((int)str_replace("RM","",myArticle::getByWhere('id_categorie='._RAW_MATERIAL_)[$indRow]['code']) + 1);
            $newCode = !empty(myArticle::getByWhere('id_categorie='._RAW_MATERIAL_))?('RM'.RTTCore::addZeroBeforeNum($number,5)):$newCode;
        }
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record  = myArticle::getById($list_id)[0];
            $bom_id  = $record['id_bom'];
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            $messages[$btnType] = 'Success';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST["id_categorie"] = _RAW_MATERIAL_;
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            $list_id = myArticle::save();
            $record  = !empty(myArticle::getById($list_id))?myArticle::getById($list_id)[0]:array();
            $btnType = 'Edit';
            Tools::refresh(0,"index.php?".Tools::newURL("id",$list_id));
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
            $_POST["id_categorie"] = _RAW_MATERIAL_;
            $_POST["date_upd"] = $currentDate;
            myArticle::updateById(Tools::getValue('id'));
            $record = myArticle::getById(Tools::getValue('id'))[0];
            //Tools::refresh();
        }
        
        $recordLang = array();
        if (!empty($record) && array_key_exists('id_lang',$record) && $record['id_lang']){
            $recordLang = xLang::getById($record['id_lang']);
        }
        
        $recordBom = array();
        if (!empty($record) && array_key_exists('id_bom',$record) && !empty($record['id_bom'])){
            $recordBom = myBillOfMaterial::getById($record['id_bom'])[0];
        }
        
        $recordCate = array();
        if (!empty($record) && array_key_exists('id_categorie',$record) && !empty($record['id_categorie'])){
            $recordCate = myArticleCategorie::getById($record['id_categorie'])[0];
        }
        
        $recordParent = array();
        if (!empty($record) && array_key_exists('id_parent',$record) && !empty($record['id_parent'])){
            $recordParent = myArticle::getById($record['id_parent'])[0];
        }
        
        //var_dump($recordBom['title']);
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
            $filename = $PNG_TEMP_DIR.$_REQUEST['title'].md5($_REQUEST['title'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
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
        
        //echo $record['active'];
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
        $ret.= '       <span class="frm-title">'.RTTCore::l('Product','admin').'</span>';
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
        $ret.= '<div class="row bg-form">';
        /* Form utils */
        $ret.= '    <div class="row body-form">';
        /* 1st Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Type').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="type" type="text" value="'.(!empty($record['type'])?$record["type"]:RTTCore::l($actions[Tools::getValue('tp')],'admin')).'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Language').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_lang" name="id_lang"  type="hidden" value="'.(!empty($record)?$record['id_lang']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalLang\')" id="txtlang" name="lang"  type="text" value="'.((!empty($record))?$record["lang"]:(!empty($recordLang)?$recordLang['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalLang\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Bill Of Material').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                    <input class="" id="id_bom" name="id_bom"  type="hidden" value="'.(!empty($record)?$record['id_bom']:1).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalBOM\')" id="txtbom" name="bom"  type="text" value="'.(!empty($recordBom)?$recordBom["title"]:"").'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalBOM\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Categories').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_categorie" name="id_categorie"  type="hidden" value="'.(!empty($record)?$record['id_categorie']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalCategorie\')" id="txtcategorie" name="categorie"  type="text" value="'.(!empty($record)?$record["categorie"]:(!empty($recordCate['title'])?$recordCate['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalCategorie\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Code').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="code" type="text" value="'.(!empty($record['code'])?$record["code"]:$newCode).'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Refference').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="refference" type="text" value="'.(!empty($record['refference'])?$record["refference"]:'').'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Parent').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_parent" name="id_parent"  type="hidden" value="'.(!empty($record)?$record['id_parent']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalParent\')" id="txtparent" name="parent"  type="text" value="'.(!empty($record)?$record["parent"]:(!empty($recordParent['title'])?$recordParent['title']:"")).'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalParent\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Status').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_status" name="id_status"  type="hidden" value="'.(!empty($record)?$record['id_status']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalStatus\')" id="txtstatus" name="status"  type="text" value="'.(!empty($record)?$record["title"]:"").'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalStatus\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Site').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_complex" name="id_complex"  type="hidden" value="'.(!empty($record)?$record['id_complex']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalComplex\')" id="txtcomplex" name="complex"  type="text" value="'.(!empty($record)?$record["title"]:"").'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalComplex\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Company').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_company" name="id_company"  type="hidden" value="'.(!empty($record)?$record['id_company']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalCompany\')" id="txtcompany" name="company"  type="text" value="'.(!empty($record)?$record["title"]:"").'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalCompany\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>'.RTTCore::l('Building').' </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield txt-left">';
        $ret.= '                    <input class="" id="id_building" name="id_building"  type="hidden" value="'.(!empty($record)?$record['id_building']:0).'"/>';
        $ret.= '                    <input class="select" onclick="showModal(\'modalBuilding\')" id="txtbuilding" name="building"  type="text" value="'.(!empty($record)?$record["title"]:"").'"/>';
        $ret.= '                    <button type="button" class="btn-select" onclick="showModal(\'modalBuilding\')" id="myBtn"><i class="bx bxs-chevron-down" ></i></button>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 1st Column */
        /* 2nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Title').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="title" type="text" value="'.(!empty($record['title'])?$record["title"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Purchased Price').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="purchased_unit_price" type="text" value="'.(!empty($record['purchased_unit_price'])?$record["purchased_unit_price"]:"").'"/>';
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
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Sale Price').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="sale_unit_price" type="text" value="'.(!empty($record['sale_unit_price'])?$record["sale_unit_price"]:"0.00").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Discount').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="sale_discount_price" type="text" value="'.((!empty($record) && !empty($record['sale_discount_price']))?$record["sale_discount_price"]:"0.00").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Discount in Percent').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="sale_discount_percent" type="text" value="'.((!empty($record) && !empty($record['sale_discount_percent']))?$record["sale_discount_percent"]:"0.00").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Model').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="model" type="text" value="'.((!empty($record) && !empty($record['model']))?$record["model"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Mark').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="mark" type="text" value="'.((!empty($record) && !empty($record['mark']))?$record["mark"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Serial Number').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="serial_number" type="text" value="'.((!empty($record) && !empty($record['serial_number']))?$record["serial_number"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Installed Date').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="installed_date" type="date" value="'.((!empty($record) && !empty($record['installed_date']))?$record["installed_date"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('State').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="state" type="text" value="'.((!empty($record) && !empty($record['state']))?$record["state"]:"").'"/>';
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
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <input onchange="" class="txtUpload" type="text" id="txtUpload" name="img_name" value="'.(!empty($record)?$record['img_name']:'blank.jpg').'" placeholder=""> ';
        $ret.= '                   <label class="upload" >';
        $ret.= '                       <input onchange="getFileName(this,\'txtUpload\',\'imgPreview\',\'images/equ\')" id="file" name="file" type="file"/>';
        $ret.= '                       <span class="btn-form btn-upload txt-center">'.RTTCore::l('Upload','admin').'</span>';
        $ret.= '                   </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="col-1 txt-center">';
        $ret.= '                   <img id="imgPreview" class="img-form" src="images/equ/'.(!empty($record['img_name'])?$record['img_name']:'blank.jpg').'">';
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
        /* End Form utils */
        /* Tabs */
        $ret.= '    <div class="tab">';
        $ret.= '        <button id="idsuppliertab" type="button" class="tablinks defaultTab" onclick="openTab(event, \'suppliertab\')">'.RTTCore::l("Supplier","admin").'</button>';
        $ret.= '        <button id="idassociatedproducttab" type="button" class="tablinks" onclick="openTab(event, \'associatedproducttab\')">'.RTTCore::l("Product Associated","admin").'</button>';
        $ret.= '        <button id="idattachementtab" type="button" class="tablinks" onclick="openTab(event, \'attachementtab\')">'.RTTCore::l("Files","Admin").'</button>';
        $ret.= '        <button type="button" class="tablinks" onclick="openTab(event, \'journaltab\')">'.RTTCore::l("Journal","Admin").'</button>';
        $ret.= '    </div>';
        
        
        /* Supplier Tab */
        $ret.= '   <div id="suppliertab" class="tabcontent bg-tab">';
        $ret.= self::supplierTab($list_id);
        $ret.= '   </div>';
        /* End Supplier */
        
        /* Parent Tab */
        $ret.= '   <div id="associatedproducttab" class="tabcontent bg-tab">';
        $ret.= self::associatedproductTab($bom_id);
        $ret.= '   </div>';
        /* End Parent */
        
        /* Attachment Tab */
        $ret.= '   <div id="attachementtab" class="tabcontent">';
        $dirname = "modules/" . Tools::getValue("t")."/".$list_id."/";
        $ret.= self::attachmentTab($dirname);
        $ret.= '   </div>';
        /* End Attachment Tab */
        
        /* Pièces Tab */
        $ret.= '   <div id="journaltab" class="tabcontent">';
        $ret.= self::journalTab($list_id);
        $ret.= '   </div>';
        /* End Pièces */
        
        /* End Tabs */
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalAttachment" class="modal">';
        /*<!-- Modal content -->*/
        $ret.= self::modalAttachment($dirname);
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* Modal Block */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalLang" class="modal">';
        $ret.= self::modalLang();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalParent" class="modal">';
        $ret.= self::modalParent();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalBOM" class="modal">';
        $ret.= self::modalBOM();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalCategorie" class="modal">';
        $ret.= self::modalCategorie();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* End Modal Block */
        $ret.= '</div>';
        $ret.= '</form>';
        return $ret;
    }
    
    public static function modalParent()
    {
        global $id_lang;
        $table = 'parent';
        $ret = '';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = (myArticle::getByWhere('`id_lang` = '.$id_lang.' AND `active`=1 AND `id_parent`=0') !== null)?myArticle::getByWhere('`id_lang` = '.$id_lang.' AND `active`=1 AND `id_parent`=0'):array();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Parent","admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        //$ret.= '     <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        $ret.= '  </div>';
        
        //$ret.= '   </div>';
        $ret.= '   <div class="modal-body">';
        $fieldsDisplay = array(
           'id_article'          => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'Description'         => RTTCore::l('Decription')
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
        $ret.= '          <th class="bg-orange">Action</th>';
        $ret.= '        </tr>';
        /* body table */
        if ($records)
        {
            $indRow = 0;
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $indRow++;
                $ret.= '        <td class="txt-center"><input data-listId="'.(!empty($record['id_article'])?$record['id_article']:$indRow).'" onchange="changeChecked(\''.$table.'\',this.id)" id="'.$table.''.$indRow.'" type="checkbox" value="'.(!empty($record['title'])?$record['title']:$indRow).'"/></td>';
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
        $ret.= '         <button onclick="hideModal(\'modalParent\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalParent\',\''.$table.'\',\'txt'.$table.'\');" class="btn-form" type="button">'.RTTCore::l("Ok","admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalLang()
    {
        $table = 'lang';
        $ret = '';
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
                $ret.= '           <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_lang"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '           <a  onclick="return confirm(\'Vous êtes sûr?\');" href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_lang"].'&token='.Tools::getValue('token').'"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></a>';
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
    
    public static function modalBOM()
    {
        global $id_lang;
        $table = 'bill_of_material';
        $ret = '';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = (myBillOfMaterial::getByWhere('`id_lang` = '.$id_lang) !== null)?myBillOfMaterial::getByWhere('`id_lang` = '.$id_lang):array();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Bill Of Material","admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
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
        $ret.= '         <button onclick="hideModal(\'modalBOM\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalBOM\',\''.$table.'\',\'txtbom\');" class="btn-form" type="button">'.RTTCore::l("Ok","admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    public static function modalCategorie()
    {
        global $id_lang;
        $table = 'article_categorie';
        $ret = '';
        /*<!-- Modal content -->*/
        $ret.= '  <div class="modal-content">';
        //$ret.= '   <div class="modal-header">';
        //$ret.= '      <span onclick="hideModal(\'myModal1\')" class="mdl-close">&times;</span>';
        //$ret.= '      <h2>Actifs</h2>';
        $records = (myArticleCategorie::getByWhere('`id_lang` = '.$id_lang) !== null)?myArticleCategorie::getByWhere('`id_lang` = '.$id_lang):array();
        //var_dump($records[0]['title']);
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l("Categories","admin").'</span>';
        $ret.= '     <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
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
        $ret.= '         <button onclick="hideModal(\'modalCategorie\')"  class="btn-form" type="button">'.RTTCore::l("Cancel","admin").'</button>';
        $ret.= '         <button onclick="selectValue(\'modalCategorie\',\''.$table.'\',\'txtcategorie\');" class="btn-form" type="button">'.RTTCore::l("Ok","admin").'</button>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Field */
        return $ret;
    }
    
    	public static function modalAttachment($dirname)
    {
        $ret = "";
        $ret.= '         <div class="modal-content">';
        //$ret.= '            Test';
        $ret.= '            <div class="row tools-title">';
        $ret.= '               <span class="tbl-title">'.RTTCore::l("Files","admin").'</span>';
        //$ret.= '               <span class="article">'.count($recordsAsset).' Article(s)</span>';
        //$ret.= '               <input id="inpSearch" onkeyup="myFunction(this,\'asset\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        $ret.= '             <span onclick="printDoc(\'reportDiv\')" class="mdl-close"><img class="tbl-ico32" src="../Bundles/images/advancetools/15525195.png"/></span>';
        $ret.= '            </div>';
        /* Modal Body */
        $ret.= '            <div id="reportDiv" class="modal-body">';
        /* Report */
        $ret.= '               <div class="col-1">';
        $ret.= '                <div class="col-1">';
        $ret.= '                    <input onchange="" style="width:85%" class="" type="text" id="txtUploadAttach" name="img_name_attach" value="" placeholder="">';
        $ret.= '                    <label class="">';
        $ret.= '                       <input onchange="getFileName(this,\'txtUploadAttach\',\'inputAttach\',\''.$dirname.'\')" id="fileAttach" name="file" type="file"/>';
        $ret.= '                       <span class="btn-form btn-upload">'.RTTCore::l("Upload","admin").'</span>';
        $ret.= '                       <input id="inputAttach" name="" type="hidden"/>';
        $ret.= '                    </label>';
        $ret.= '                </div>';
        $ret.= '               </div>';
        /* End Report */
        $ret.= '            </div>';
        /* End Modal Body */
        
        /* Modal Footer */
        $ret.= '           <div class="row modal-footer">';
        $ret.= '                <div class="col-2"';
        $ret.= '                    <h3></h3>';
        $ret.= '                </div>';
        $ret.= '                <div class="col-2 txt-right"';
        $ret.= '                    <button onclick="hideModal(\'modalAttachment\');"  class="txt-black btn-form" type="button">'.RTTCore::l("Close","admin").'</button>';
        //$ret.= '                  <button onclick="selectValue(\'modalAttachment\',\'asset\',\'txtasset\');" class="btn-form" type="button">Ok</button>';
        $ret.= '                </div>';
        $ret.= '            </div>';
        $ret.= '        </div>';
        /* End Modal Footer */
        return $ret;
    }
    
    public static function associatedproductTab($id_parent)
    {
        global $id_lang;
        $table = 'article';
        $records = (myArticle::getByWhere('`id_lang` = '.$id_lang.' AND `id_bom`='.$id_parent) !== null)?myArticle::getByWhere('`id_lang` = '.$id_lang.' AND `id_bom`='.$id_parent):array();
        //var_dump($records);
        $fieldsDisplay = array(
           'code'                => RTTCore::l('Code'),
           'title'               => RTTCore::l('Title'),
           'Description'         => RTTCore::l('Description')
        );
        
        $ret = '';
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Supplier","admin").'</span>';
        $ret.= '         <span class="article">'.count($records).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        $ret.= '      </div>';
        
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="addRow(\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","admin").'\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddQuotationDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        /* Declation Variables */
        $totalDetail  = 0;
        $totalRemise  = 0;
        
        /* End Declation Variables */
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="'.$table.'">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Action").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($records){
            $indRow = 0;
            foreach($records as $record)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\']);" id="quotationdetail'.$indRow.'" type="checkbox" value="'.$recordDetail['id_purchase_detail'].'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if (!empty($record) && array_key_exists($field,$record) && $field == 'title')
                    {
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&tp='.Tools::getValue('tp').'&act=form&id='.$record["id_".$table.""].'&token='.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }else if (!empty($record) && array_key_exists($field,$record) && $field != 'title')
                    {
                        $ret.= '<td>'.$record[$field].'</td>';
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
        
        return $ret;
    }
    
    public static function journalTab($idList)
    {
        global $defaultCurrency;
        $table   = "purchase_detail_tab";
        $record  = isset(myArticle::getById($idList)[0])?myArticle::getById($idList)[0]:array();
        $recordsDetails = array();
        if (!empty($record) && array_key_exists("id_article",$record))
        {
            $recordsDetails = myPurchaseDetail::getByWhere("`id_article`=".$record["id_article"]);
        }
        $fieldsDisplay = array(
           'date_upd'                   => RTTCore::l('Date'),
           'title'                      => RTTCore::l('Title'),
           'refference'                 => RTTCore::l('Refference'),
           'purchased_quantity'         => RTTCore::l('Quantity Purchased'),
           'purchased_unit_price'       => RTTCore::l('Unit Price Purchased'),
           'discount_percent'           => RTTCore::l('Discount in Percent'),
           'discount_amount'            => RTTCore::l('Discount Amount'),
           'purchased_amount_cost'      => RTTCore::l('Amount Cost Purchased'),
           'unit'                       => RTTCore::l('Package'),
           'pay_service'                => RTTCore::l('Pay Service'),
           'pay_account'                => RTTCore::l('Pay Account')
        );
        
        $ret = '';
        
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Supplier","admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsDetails).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        $ret.= '      </div>';
        
        $ret.= '     <div class="tbl-tools">';
        //$ret.= '        <button onclick="addRow(\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","admin").'\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddQuotationDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        /* Declation Variables */
        $totalDetail  = 0;
        $totalRemise  = 0;
        
        /* End Declation Variables */
        
        $ret.= '     <div class="row table">';
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
        if ($recordsDetails)
        {
            $indRow = 0;
            foreach($recordsDetails as $record)
            {
                $ret.= '<tr>';
                $ret.= '  <td class="txt-center"><input id="'.$table.$indRow.'" type="checkbox" value="'.$record['id_'.$table].'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if ( !empty($record) && array_key_exists($field, $record) && $field  == 'img_name')
                    {
                        $ret.= '<td class="txt-center"><img class="tbl-ico32" src="images/equ/'.(!empty($record)?$record['img_name']:'blank.jpg').'"></a></td>';
                    }else if ($field == 'status' OR $field == 'active')
                    {
                        $ret.= '<td class="txt-center stat '.strtolower($statusUser[$record[$field]]).'">'.$actives[$record[$field]].'</td>';
                    }else if ($field == 'title')
                    {
                        $ret.= '<td class="w-50"><a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_".$table].'&token='.Tools::getValue('token').'&tp=1">'.$record[$field].'</a></td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
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
    			/*
        $ret.= '       <table id="'.$table.'">';
        $ret.= '         <tr>';
        $ret.= '         <th onclick="sortTable(0)" class="bg-orange"> <img class="tbl-ico" id="col0'.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; </th>';
        $indCol = 1;
        foreach($fieldsDisplay as $k => $th)
        {
            $ret.= '     <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
            $indCol++;
        }
        $ret.= '         <th class="bg-orange">'.RTTCore::l('Action').'</th>';
        $ret.= '         </tr>';
        /* body table /
        if ($recordsDetails){
            $indRow = 0;
            foreach($recordsDetails as $recordDetail)
            {
                //$indRow++;
                $ret.= ' <tr>';
                //$ret.= '        <td class="txt-center"><input onchange="editRow(this,\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\']);" id="quotationdetail'.$indRow.'" type="checkbox" value="'.$recordDetail['id_purchase_detail'].'"/></td>';
                $ret.= '  <td class="txt-center"><input id="'.$table.$indRow.'" type="checkbox" value="'.$record['id_'.$table].'"/></td>';
            			   foreach($fieldsDisplay as $field => $th)
                {
                    if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'discount_amount')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0,00').' '.(array_key_exists('currency_symbole',$record)?$record['currency_symbole']:$defaultCurrency).'</td>';
                    }else  if (!empty($recordDetail) && array_key_exists('purchased_unit_price',$recordDetail) && $field == 'purchased_unit_price')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0,00').' '.(array_key_exists('currency_symbole',$record)?$record['currency_symbole']:$defaultCurrency).'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'discount_amount_estimated')
                    {
                        $totalAmountDiscount  = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['unit_price_estimated'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['quantity_estimated'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['discount_percent_estimated']))) / 100;
                        $totalRemise += $totalAmountDiscount;
                    
                        $ret.= '<td class="txt-right">'.(!empty($totalAmountDiscount)?RTTCore::dotToComma($totalAmountDiscount):'0,00').' '.(array_key_exists('currency_symbole',$record)?$record['currency_symbole']:$defaultCurrency).'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'amount_cost_estimated')
                    {
                        $totalSub      = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['unit_price_estimated'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['quantity_estimated'])));
                        $totalDetail  += $totalSub;
                        $ret.= '<td class="txt-right">'.(!empty($totalSub)?number_format($totalSub,2):'0,00').' '.$record['currency_symbole'].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'unit_price_purchase')
                    {
                        //$totalDetail  += RTTCore::commaToDot($recordDetail[$field]);
                        $ret.= '<td class="txt-right">'.$recordDetail[$field].' '.$record['currency_symbole'].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'quantity_estimated' OR $field == 'quantity_purchase' OR $field == 'discount_percent_estimated')
                    {
                        $ret.= '<td class="txt-center">'.(!empty($recordDetail[$field])?$recordDetail[$field]:'0').'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'title')
                    {
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idwoj='.$recordDetail["id_".$table.""].'&token'.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field != 'title')
                    {
                        $ret.= '<td>'.$recordDetail[$field].'</td>';
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
    			*/
        $ret.= '     </div>';
        
        return $ret;
    }
    
    public static function attachmentTab($dirtoryName)
    {
        $ret = '';
        $recordsAttachement = RTTCore::scanDirectory($dirtoryName);
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Files","admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsAttachement).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        $ret.= '      </div>';
        //var_dump($recordsAttachement);
        $attachFields = array(
           'name'      => RTTCore::l("Title","Admin"),
           'note'      => RTTCore::l("Note","Admin"),
           'file_type' => RTTCore::l("Type","Admin"),
           'size'      => RTTCore::l("Size","Admin")
           //'preview'   => 'Aperçu'
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="showModal(\'modalAttachment\')" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddWorkForce" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="attach">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'workforce\',[\'checkbox\',\'description\',\'distributed_to\',\'estimated_time\',\'passed_time\',\'result\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($attachFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'workforce\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'workforce" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Actions","admin").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsAttachement){
			
            $indRow = 0;
            foreach($recordsAttachement as $record)
            {
                
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'name\',[\'note\',\'file_type\',\'size\',\'action\']);" id="attach'.$indRow.'" type="checkbox" value="'.$indRow.'"/></td>';
                foreach($attachFields as $field => $th)
                {
                    if ($field != 'name')
                    {
                        $ret.= '<td>'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&token'.Tools::getValue('token').'" >'.$record[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/></a>';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                //$ret.= '<td>'.$record["name"].'</td>';
                $ret.= ' </tr>';
                $indRow++;
            }
            //var_dump($recordsAttachment);
        }else {
            $ret.= '     <tr class="tbl-empty">';
            $ret.= '     </tr>';
        }
        $ret.= '       </table>';
        $ret.= '     </div>';
        return $ret;
    }
	
    public static function supplierTab($idList)
    {
        global $defaultCurrency;
        $table   = "purchase_detail";
        $record  = isset(myArticle::getById($idList)[0])?myArticle::getById($idList)[0]:array();
        $recordsDetails = array();
        if (!empty($record) && array_key_exists("id_article",$record))
        {
            $recordsDetails = myPurchaseDetail::getMaxDateByArticle($record["id_article"]);
        }
        $fieldsDisplay = array(
           'date_upd'                   => RTTCore::l('Date'),
           'id_supplier'                => RTTCore::l('Company'),
           'refference'                 => RTTCore::l('Refference'),
           'purchased_quantity'         => RTTCore::l('Quantity Purchased','admin'),
           'purchased_unit_price'       => RTTCore::l('Unit Price Purchased','admin'),
           'discount_percent'           => RTTCore::l('Discount in Percent','admin'),
           'discount_amount'            => RTTCore::l('Discount Amount','admin'),
           'purchased_amount_cost'      => RTTCore::l('Amount Cost Purchased','admin'),
           'unit'                       => RTTCore::l('Package'),
           'pay_service'                => RTTCore::l('Pay Service','admin'),
           'pay_account'                => RTTCore::l('Pay Account','admin')
        );
        
        $ret = '';
        
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.RTTCore::l("Supplier","admin").'</span>';
        $ret.= '         <span class="article">'.count($recordsDetails).' '.RTTCore::l("Records","admin").'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this,\''.$table.'\')" class="txt-search" type="text" placeholder="'.RTTCore::l("Type Keyword for Searching","admin").'" />';
        $ret.= '      </div>';
        
        $ret.= '     <div class="tbl-tools">';
        //$ret.= '        <button onclick="addRow(\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="return confirm(\''.RTTCore::l("Are you sure?","admin").'\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        //$ret.= '        <button name="btnSubmitAddQuotationDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        /* Declation Variables */
        $totalDetail  = 0;
        $totalRemise  = 0;
        
        /* End Declation Variables */
        
        $ret.= '     <div class="row table">';
        $ret.= '       <table id="'.$table.'">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\''.$table.'\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.''.$table.'" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.RTTCore::l("Action").'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsDetails){
            $indRow = 0;
            foreach($recordsDetails as $recordDetail)
            {
                $indRow++;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\''.$table.'\',[\'checkbox\',\'date_upd\',\'title\',\'refference\',\'purchased_quantity\',\'purchased_unit_price\',\'discount_percent\',\'discount_amount\',\'purchased_amount_cost\',\'unit\',\'pay_service\',\'pay_account\',\'action\']);" id="quotationdetail'.$indRow.'" type="checkbox" value="'.$recordDetail['id_purchase_detail'].'"/></td>';
                foreach($fieldsDisplay as $field => $th)
                {
                    if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'discount_percent')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?number_format($recordDetail[$field],2):'0.00').' %'.'</td>';
                    }else  if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'purchased_unit_price')
                    {
                        $list = myPurchaseOrder::getById($recordDetail['id_purchase_list'])[0];
                        $ret.= '<td class="txt-right">'.(!empty($recordDetail[$field])?number_format($recordDetail[$field],2):'0.00').' '.$list['currency_symbole'].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'discount_amount')
                    {
                        $totalAmountDiscount  = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_unit_price'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_quantity'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['discount_percent']))) / 100;
                        $totalRemise += $totalAmountDiscount;
                    
                        $list = myPurchaseOrder::getById($recordDetail['id_purchase_list'])[0];
                        $ret.= '<td class="txt-right">'.(!empty($totalAmountDiscount)?number_format($totalAmountDiscount,2):'0.00').' '.$list['currency_symbole'].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'purchased_amount_cost')
                    {
                        $totalSub      = ((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_unit_price'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_quantity']))) - (((float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_unit_price'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['purchased_quantity'])) * (float)str_replace(",", "", RTTCore::commaToDot($recordDetail['discount_percent']))) / 100);
                        $totalDetail  += $totalSub;
                        $list = myPurchaseOrder::getById($recordDetail['id_purchase_list'])[0];
                        $ret.= '<td class="txt-right">'.(!empty($totalSub)?number_format($totalSub,2):'0.00').' '.(!empty($list)?$list['currency_symbole']:$defaultCurrency).'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'purchased_unit_price')
                    {
                        //$totalDetail  += RTTCore::commaToDot($recordDetail[$field]);
                        $list = myPurchaseOrder::getById($recordDetail['id_purchase_list'])[0];
                        $ret.= '<td class="txt-right">'.$recordDetail[$field].' '.$record['currency_symbole'].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'purchased_quantity')
                    {
                        $ret.= '<td class="txt-center">'.(!empty($recordDetail[$field])?number_format($recordDetail[$field],2):'0.00').'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field == 'id_supplier')
                    {
                        $ret.= '<td><a href="index.php?t=Supplier&pt='.Tools::getValue('pt').'&act=form&id='.$recordDetail["id_supplier"].'&token='.Tools::getValue('token').'" >'.((myCompany::getById($recordDetail[$field]) !== null)?myCompany::getById($recordDetail[$field])[0]['company']:'Unkown').'</a></td>';
                    }else if (!empty($record) && array_key_exists($field,$record) && $field == 'refference')
                    {
                        $ret.= '<td class="txt-center">'.$record[$field].'</td>';
                    }else if (!empty($recordDetail) && array_key_exists($field,$recordDetail) && $field != 'title')
                    {
                        $ret.= '<td>'.$recordDetail[$field].'</td>';
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
        
        return $ret;
    }
    
    public static function hookProduct($args)
    {
        global $actions,$actives, $statusUser;
        $table = 'article';
        $records = array();
        myArticle::init();
        $records = myArticle::getByWhere('id_categorie='._RAW_MATERIAL_);
        $fieldsDisplay = array(
            'code'       => RTTCore::l('Code'),
            'img_name'   => RTTCore::l('Image'),
            'title'      => RTTCore::l('Title'),
            'description'=> RTTCore::l('Description'),
            'active'     => RTTCore::l('Active'),
            //'last_name'  => RTTCore::l('Last Name'),
        );
        
        $ret = "";
        //$ret.= "Hook: Product";
        
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
            $ret.= '     <option style="background-image:url(\'Bundles/images/advancetools/10629723.png\');" value="'.$k.'"> <img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/>'.RTTCore::l($val,'admin').'</option>';
        }
        $ret.= '     </select>';
        $ret.= '   </div>';
        $ret.= '</div>';
        /* End Tools actions */
        
        $ret.= '<div class="row">';
        $ret.= ' <div class="bg-table">';
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Product','admin').'</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.RTTCore::l('Type Keyword for Searching','admin').'" />';
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
                    if ( !empty($record) && array_key_exists($field, $record) && $field  == 'img_name')
                    {
                        $ret.= '<td class="txt-center"><img class="tbl-ico32" src="images/equ/'.(!empty($record)?$record['img_name']:'blank.jpg').'"></a></td>';
                    }else if ($field == 'status' OR $field == 'active')
                    {
                        $ret.= '<td class="txt-center stat '.strtolower($statusUser[$record[$field]]).'">'.$actives[$record[$field]].'</td>';
                    }else if ($field == 'title')
                    {
                        $ret.= '<td class="w-50"><a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_".$table].'&token='.Tools::getValue('token').'&tp=1">'.$record[$field].'</a></td>';
                    }else{
                        $ret.= '<td>'.$record[$field].'</td>';
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