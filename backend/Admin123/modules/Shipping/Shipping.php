<?php
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as RTTCore;
use classes\myShippingMode as myShippingMode;
use classes\xLang as xLang;

class Shipping extends Module
{
    public static function form($args)
    {
        global $langs,$actions;
        //var_dump($langs);
        myShippingMode::init();
        $record      = array();
        $messages    = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id     = 0;
        if (Tools::getValue('id')){
            $btnType = 'Edit';
            $list_id = Tools::getValue('id');
            $record  = (myShippingMode::getById($list_id) !== null)?myShippingMode::getById($list_id)[0]:array();
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            $messages[$btnType] = 'Success';
            if (isset($_POST['active']))
            {
                $_POST['active'] = 1;
            }
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            $list_id = myShippingMode::save();
            $record  = myShippingMode::getById($list_id)[0];
            $btnType = 'Edit';
            //Tools::refresh(10,"index.php?".Tools::newURL("id",$list_id));
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
            $_POST["date_upd"] = $currentDate;
            myShippingMode::updateById(Tools::getValue('id'));
            $record = myShippingMode::getById(Tools::getValue('id'))[0];
            //Tools::refresh();
        }
        
        $recordLang = array();
        if (array_key_exists('id_lang',$record) && $record['id_lang']){
            $recordLang = xLang::getById($record['id_lang']);
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
        $ret.= '       <span class="frm-title">'.RTTCore::l('Shipping Mode','admin').'</span>';
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
        $ret.= '                   <label>'.RTTCore::l('Type').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="type" type="hidden" value="'.(!empty($record['type'])?$record["type"]:RTTCore::l($actions[Tools::getValue('tp')],'admin')).'"/>';
        $ret.= '                   <input name="actions" type="text" value="'.(!empty($record['action'])?$record["action"]:RTTCore::l($actions[Tools::getValue('tp')],'admin')).'"/>';
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
        /* End 1nd Column */
        /* 2rd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                   <label>'.RTTCore::l('Title').'</label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                   <input name="title" type="text" value="'.(!empty($record['title'])?$record["title"]:"").'"/>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 2nd Column */
        /* 3nd Column */
        $ret.= '       <div class="col-3">';
        
        $ret.= '           <div class="col-1">';
        $ret.= '               <label class="col-1">'.RTTCore::l('Description').'</label><br/>';
        $ret.= '               <div class="col-1">';
        $ret.= '                   <span onclick="tabDes(\'divDes\')" class="btn">'.RTTCore::l('Design').'</span>';
        $ret.= '                   <span onclick="tabDes(\'txtDes\')" class="btn">'.RTTCore::l('HTML').'</span>';
        $ret.= '                   <div id="toolsDes" class="toolsDes">';
        $ret.= '                       <span class="btnTools">'.RTTCore::l('P').'</span>';
        $ret.= '                   </div>';
        $ret.= '                   <div id="divDes" name="description" class="divDes" contenteditable="true">';
        $ret.= '                     '.(!empty($record['description'])?$record["description"]:"");
        $ret.= '                   </div>';
        $ret.= '                   <textarea id="txtDes" name="description" class="txtDes hide">';
        $ret.= '                     '.(!empty($record['description'])?$record["description"]:"");
        $ret.= '                   </textarea>';
        $ret.= '               </div>';
        $ret.= '           </div>';
        
        $ret.= '       </div>';
        /* End 3rd Column */
        $ret.= '    </div>';
        /* End Form utils */
        $ret.= '  </div>';
        /* End Formulaire */
        
        /* Modal Block */
        
        /*<!-- The Modal -->*/
        $ret.= '<div id="modalLang" class="modal">';
        $ret.= self::modalLang();
        $ret.= '</div>';
        /*<!-- End Modal -->*/
        
        /* End Modal Block */
        
        /* Form */
        $ret.= '</form>';
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
    
    public static function hookShipping($args)
    {
        global $actions,$status,$actives;
        $records = array();
        $table = "shipping_mode";
        myShippingMode::init();
        $records = myShippingMode::getRecords();
        
        $fieldsDisplay = array(
            'id_lang'      => RTTCore::l('Language'),
            'code'         => RTTCore::l('Code'),
            'title'        => RTTCore::l('Title'),
            'description'  => RTTCore::l('Description'),
            'active'       => RTTCore::l('Active'),
        );
        $ret = "";
        //$ret.= "Hook: Shipping Mode";
        
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
        $ret.= '     <span class="tbl-title">'.RTTCore::l('Shipping Mode','admin').'</span>';
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
                    if (array_key_exists('active',$record) && $field == 'active')
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