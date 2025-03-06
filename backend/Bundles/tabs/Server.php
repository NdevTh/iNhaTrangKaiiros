<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;

class Server extends AdminTab
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
            $query = '//servers/server';
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $updateFields=array(
               'db_server' => Tools::getValue('db_server'),
               'db_user'   => Tools::getValue('db_user'),
               'db_passwd' => Tools::getValue('db_passwd'),
               //'image'     => Tools::getValue('image'),
               'db_type'   => Tools::getValue('db_type'),
               'db_name'   => Tools::getValue('user_group'),
               'db_prefix' => Tools::getValue('system_role'),
               'active'    => Tools::getValue('active'),
            );
            XMLFile::update('servers',$whereClause,$updateFields,$query,"xml");
        }
        
        if (!empty(Tools::getValue('id')))
        {
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $query = '//servers/server';
            $tabs = XMLFile::getXMLByWhere('servers',$query,$whereClause,'xml');
            $record = $tabs[0];
            $btnType = 'Edit';
            //var_dump($record);
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','tab');
        $ret.= '<form action="core.php?'.Tools::newUrl('t','tab').'" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">Server Form</legend>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>IP Server</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_server" type="text" value="'.(isset($record)?$record['db_server']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Username</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_user" type="text" value="'.(isset($record)?$record['db_user']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Password</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_passwd" type="text" value="'.(isset($record)?$record['db_passwd']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>DB Type</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_type" type="text" value="'.(isset($record)?$record['db_type']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        /*
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Position</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="position" type="text" value="'.(isset($record['position'])?$record['position']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        */
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>DB prefix</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_prefix" type="text" value="'.(isset($record['db_prefix'])?$record['db_prefix']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>DB Name</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="db_name" type="text" value="'.(isset($record['db_name'])?$record['db_name']:"1").'"/>';
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
        $ret.= '    <div class="row">';
        $ret.= '       <button name="btnSubmit'.$btnType.'" class="btn flt-right" type="submit">'.$btnType.'</button>';
        $ret.= '    </div>';
        $ret.= '  </fieldset>';
        $ret.= '</form>';
        return $ret;
    }
    public static function viewServer()
    {
        $ret = '';
        //$ret.= 'view tab';
        if (Tools::getValue('act') == 'del'){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Delete" was successful';
            $ret.= '</div>';
            XMLFile::deleteById('servers',Tools::getValue('id'),'xml');
        }
        if (!file_exists('xml/servers.xml'))
        {
            $ret.= 'Initialization Tab file...';
            Core::initServer();
        }
        
        $records = XMLFile::readFile('servers','','xml');
        $folders = Tools::scanFolder('servers');
        //var_dump($folders);
        //var_dump($records);
        if (!empty($records))
        {
            $fields = XMLFile::getFields('servers','xml');
            $ret.= '<div class="row">';
            $ret.= '  <a class="" href="core.php?t='.Tools::getValue('t').'&act=form"><img class="tbl-ico32" src="images/advancetools/1828819.png"/></a>';
            $ret.= '</div>';
            $ret.= '<table>';
            $ret.= '<tr>';
            $ret.= '<th class="tbl-id">ID</th>';
            $indCol = 0;
            foreach ($fields as $field){
                $ret.= '<th onclick="sortTable('.$indCol.')" > <img class="tbl-ico" id="col'.$indCol.'" src="images/advancetools/4340090.png"/>'.strtoupper(str_replace("_"," ",$field)) .'</th>';
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