<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;
use Bundles\classes\xUserGroup as xUserGroup;
use Bundles\classes\xSystemRole as xSystemRole;

class Country extends AdminTab
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
            XMLFile::writeFile('tabs',array(),"","xml");
        }
        
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Edit" was successful';
            $ret.= '</div>';
            //var_dump($_POST);
            $query = '//countries/countries';
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $updateFields=array(
               'name'       => Tools::getValue('name'),
               'id_lang'    => Tools::getValue('id_lang'),
               'url'        => Tools::getValue('url'),
               'image'      => Tools::getValue('image'),
               'position'   => Tools::getValue('position'),
               'user_group' => Tools::getValue('user_group'),
               'system_role'=> Tools::getValue('system_role'),
               'active'     => Tools::getValue('active'),
            );
            XMLFile::update('countries',$whereClause,$updateFields,$query,"xml");
        }
        
        if (!empty(Tools::getValue('id')))
        {
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $query = '//countries/countrie';
            $tabs = XMLFile::getXMLByWhere('countries',$query,$whereClause,'xml');
            $record = $tabs[0];
            $btnType = 'Edit';
            //var_dump($record);
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','tab');
        $ret.= '<form action="core.php?'.Tools::newUrl('t','tab').'" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">Tab Form</legend>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Name</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="name" type="text" value="'.(isset($record)?$record['name']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>ID Module</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="id_parent">';
        $ret.= '          <option value="0"> Select option </option>';
        $whereClause = '[id_parent="0"]';
        $query = '//tabs/tab';
        $gRecords = XMLFile::getXMLByWhere('tabs',$query,$whereClause,'xml');
            
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($gRecords)){
            foreach($gRecords as $gRecord)
            {
                $checked = '';
                if ($record["id_parent"] == $gRecord["id"])
                {
                    $checked = 'selected';
                }
                $ret.= '<option value="'.$gRecord["id"].'" '.$checked.'> '.$gRecord["name"].' </option>';
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>URL</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="url" type="text" value="'.(isset($record)?$record['url']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Image</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="image" type="text" value="'.(isset($record)?$record['image']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Position</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="position" type="text" value="'.(isset($record['position'])?$record['position']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
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
    public static function viewCountry()
    {
        $ret = '';
        //$ret.= 'view tab';
        if (Tools::getValue('act') == 'del'){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Delete" was successful';
            $ret.= '</div>';
            XMLFile::deleteById('countries',Tools::getValue('id'),'xml');
        }
        if (!file_exists('xml/countries.xml'))
        {
            $ret.= 'Initialization Country file...';
            Core::initCountry();
        }
        
        $records = XMLFile::readFile('countries','','xml');
        //$folders = Tools::scanFolder('tabs');
        //var_dump($folders);
        //var_dump($records);
        if (!empty($records))
        {
            $fields = XMLFile::getFields('tabs','xml');
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
                    $ret.= '<td>'.$record[$field] .'</td>';
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