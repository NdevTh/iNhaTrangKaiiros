<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;
use Bundles\classes\xUserGroup as xUserGroup;
use Bundles\classes\xSystemRole as xSystemRole;

class Lang extends AdminTab
{
    public static function form()
    {
        $ret = '';
        $btnType = 'Add';
        $record = array();
        if (Tools::isSubmit('btnSubmitAdd')){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Add" was successful';
            $ret.= '</div>';
            //var_dump($_POST);
            XMLFile::writeFile('langs',array(),"","xml");
        }
        
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Edit" was successful';
            $ret.= '</div>';
            //var_dump($_POST);
            $query = '//langs/lang';
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $updateFields=array(
               'id_tax'       => Tools::getValue('id_tax'),
               'id_lang'      => Tools::getValue('id_lang'),
               'id_state'     => Tools::getValue('id_state'),
               'id_zone'      => Tools::getValue('id_zone'),
               'title'        => Tools::getValue('title'),
               'iso_code'     => Tools::getValue('iso_code')
            );
            XMLFile::update('langs',$whereClause,$updateFields,$query,"xml");
        }
        
        if (!empty(Tools::getValue('id')))
        {
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $query = '//langs/lang';
            $tabs = XMLFile::getXMLByWhere('langs',$query,$whereClause,'xml');
            $record = $tabs[0];
            $btnType = 'Edit';
            //var_dump($record);
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','tab');
        $ret.= '<form action="" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">Tax Form</legend>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Title</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="title" type="text" value="'.(isset($record)?$record['title']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>ISO</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="iso_code" type="text" value="'.(isset($record)?$record['iso_code']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        /*
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>ID Tax</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="id_tax">';
        $ret.= '          <option value="0"> Select option </option>';
        $whereClause = '[id_parent="0"]';
        $query = '//tabs/tab';
        $gRecords = XMLFile::getXMLByWhere('taxs',$query,$whereClause,'xml');
            
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($gRecords)){
            foreach($gRecords as $gRecord)
            {
                $checked = '';
                if ($record["id_tax"] == $gRecord["id"])
                {
                    $checked = 'selected';
                }
                $ret.= '<option value="'.$gRecord["id"].'" '.$checked.'> '.$gRecord["name"].' </option>';
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        */
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>id_lang</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="id_lang" type="text" value="'.(isset($record)?$record['id_lang']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>id_state</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="id_state" type="text" value="'.(isset($record)?$record['id_state']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>id_tax</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="id_tax" type="text" value="'.(isset($record['id_tax'])?$record['id_tax']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>id_zone</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="id_zone" type="text" value="'.(isset($record['id_zone'])?$record['id_zone']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        /*
        $usergroups = xUserGroup::getRecords();
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Group User</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="user_group">';
        $ret.= '          <option value="0"> Select option </option>';
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($usergroups)){
            foreach($usergroups as $usergroup)
            {
                if ($usergroup['id'] == $record['user_group']){
                    $ret.= '<option value="'.$usergroup["id"].'" selected> '.$usergroup["id"].' - '.$usergroup["name"].' </option>';
                }else{
                    $ret.= '<option value="'.$usergroup["id"].'" > '.$usergroup["id"].' - '.$usergroup["name"].' </option>';
                }
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $systemroles = xSystemRole::getRecords();
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>System Role</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="system_role">';
        $ret.= '          <option value="0"> Select option </option>';
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($systemroles)){
            foreach($systemroles as $systemrole)
            {
                if ($systemrole['id'] == $record['system_role'] ){
                    $ret.= '<option value="'.$systemrole["id"].'" selected> ' .$systemrole['id'].' - '.$systemrole["name"].' </option>';
                }else {
                    $ret.= '<option value="'.$systemrole["id"].'" > '.$systemrole['id'].' - '.$systemrole["name"].' </option>';
                }
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        */
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Active</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $active = '';
        if (Tools::getValue('active') == '1' OR $record['active'] == 'on' OR $record['active'] == '1')
        {
            $active = 'checked';
        }
        $ret.= '          <input name="active" '.$active.' type="checkbox"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <button name="btnSubmit'.$btnType.'" class="btn flt-right" type="submit">'.$btnType.'</button>';
        $ret.= '    </div>';
        $ret.= '  </fieldset>';
        $ret.= '</form>';
        return $ret;
    }
    public static function viewLang()
    {
        $ret = '';
        //$ret.= 'view tab';
        if (Tools::getValue('act') == 'del'){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Delete" was successful';
            $ret.= '</div>';
            XMLFile::deleteById('langs',Tools::getValue('id'),'xml');
        }
        if (!file_exists('xml/langs.xml'))
        {
            $ret.= 'Initialization Tab file...';
            Core::initLang();
        }
        
        $records = XMLFile::readFile('langs','','xml');
        //$folders = Tools::scanFolder('taxs');
        //var_dump($folders);
        //var_dump($records);
        if (!empty($records))
        {
            $fields = XMLFile::getFields('langs','xml');
            $ret.= '<div class="row">';
            $ret.= '  <a class="" href="core.php?t='.Tools::getValue('t').'&act=form"><img class="tbl-ico32" src="images/advancetools/1828819.png"/></a>';
            $ret.= '</div>';
            $ret.= '<table id="myTable">';
            $ret.= '<tr>';
            $ret.= '<th class="tbl-id" > ID</th>';
            $indCol = 1;
            foreach ($fields as $field){
                $ret.= '<th onclick="sortTable('.$indCol.')" > <img class="tbl-ico" id="col'.$indCol.'" src="images/advancetools/4340090.png"/>'.strtoupper(str_replace("_"," ",$field)) .'</th>';
                $indCol++;
            }
            $ret.= '<th class="tbl-act">ACTION</th>';
            $ret.= '</tr>';
            foreach($records as $record)
            {
                $ret.= '<tr>';
                $ret.= '<td class="txt-center">'.$record["id"] .'</td>';
                foreach ($fields as $field){
                    $ret.= '<td class="txt-center">'.$record[$field] .'</td>';
                }
                $ret.= '<td class="txt-center">';
                $ret.= '  <a href="core.php?t='.Tools::getValue('t').'&act=form&id='.$record["id"].'"><img class="tbl-ico32" src="images/advancetools/10629723.png"/></a>';
                $ret.= '  <a onclick="return confirm(\'Are you sure?\')" href="core.php?t='.Tools::getValue('t').'&act=del&id='.$record["id"].'"><img class="tbl-ico32" src="images/advancetools/10871842.png"/></a>';
                $ret.= '</td>';
                $ret.= '</tr>';
            }
            $ret.= '</table>';
        }
        //$ret.= '</div>';
        return $ret;
    }
}
?>