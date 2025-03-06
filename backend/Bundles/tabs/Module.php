<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;

class Module extends AdminTab
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
            XMLFile::writeFile('modules',array(),"","xml");
        }
        
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Edit" was successful';
            $ret.= '</div>';
            //var_dump($_POST);
            $query = '//modules/module';
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $updateFields=array(
               'name'   => Tools::getValue('name'),
               'active' => Tools::getValue('active')
            );
            XMLFile::update('modules',$whereClause,$updateFields,$query,"xml");
        }
        
        if (!empty(Tools::getValue('id')))
        {
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $query = '//modules/module';
            $modules = XMLFile::getXMLByWhere('modules',$query,$whereClause,'xml');
            $record = $modules[0];
            $btnType = 'Edit';
            //var_dump($record);
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','module');
        $ret.= '<form action="core.php?'.Tools::newUrl('t','module').'" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">Module Form</legend>';
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
    public static function viewModule()
    {
        $ret = '';
        //$ret.= 'view Module';
        if (Tools::getValue('act') == 'del'){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Delete" was successful';
            $ret.= '</div>';
            XMLFile::deleteById('modules',Tools::getValue('id'),'xml');
        }
        if (!file_exists('xml/modules.xml'))
        {
            $ret.= 'Initialization Module file...';
            Core::initModule();
        }
        
        $records = XMLFile::readFile('modules','','xml');
        $folders = Tools::scanFolder('../modules');
        //var_dump($folders);
        //var_dump($records);
        if (!empty($records))
        {
            $fields = XMLFile::getFields('modules','xml');
            $ret.= '<div class="row">';
            $ret.= '  <a class="" href="core.php?t='.Tools::getValue('t').'&act=form"><img class="tbl-ico32" src="images/advancetools/1828819.png"/></a>';
            $ret.= '</div>';
            $ret.= '<table>';
            $ret.= '<tr>';
            $ret.= '<th class="tbl-id">ID</th>';
            foreach ($fields as $field){
                $ret.= '<th>'.strtoupper(str_replace("_"," ",$field)) .'</th>';
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