<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\File as  File;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;

class FrontHook extends AdminTab
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
            $id = XMLFile::writeFile('fronthooks',array(),"",_THEME_DIR_."/assets/xml");
            $whereClause = '[@id="'.$id.'"]';
            $query = '//fronthooks/fronthook';
            $hooks = XMLFile::getXMLByWhere('fronthooks',$query,$whereClause,_THEME_DIR_.'/assets/xml');
            $record = $hooks[0];
            
            $whereClause = '[@id="'.$record["id_module"].'"]';
            $query = '//frontmodules/frontmodule';
            $modules = XMLFile::getXMLByWhere('fronthooks',$query,$whereClause,_THEME_DIR_.'/assets/xml');
            $module = $modules[0];
            
            $filename = Tools::getValue('name');
            $content  = '<?php ';
            $content .= 'use classes\Module as Module; ';
            $content .= 'class '.$filename .' extends Module { ';
            $content .= '   public static function hook'.ucfirst(!empty($module['name'])?$module['name']:$filename).'($args)';
            $content .= '   {';
            $content .= '      $ret = "";';
            $content .= '      $ret.= "Hook: ' .ucfirst(!empty($module['name'])?$module['name']:$filename).'";';
            $content .= '      return $ret;';
            $content .= '   }';
            $content .= '} ?>';
            echo $content;
            File::putContent($filename,$content,_THEME_DIR_."/modules/".$filename);
        }
        
        if (Tools::isSubmit('btnSubmitEdit')){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Edit" was successful';
            $ret.= '</div>';
            //var_dump($_POST);
            $query = '//fronthooks/fronthook';
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $updateFields=array(
               'name'         => Tools::getValue('name'),
               'title'        => Tools::getValue('title'),
               'description'  => Tools::getValue('description'),
               'position'     => Tools::getValue('position'),
               'id_module'    => Tools::getValue('id_module')
            );
            XMLFile::update('fronthooks',$whereClause,$updateFields,$query,_THEME_DIR_."/assets/xml");
        }
        
        if (!empty(Tools::getValue('id')))
        {
            $whereClause = '[@id="'.Tools::getValue('id').'"]';
            $query = '//fronthooks/fronthook';
            $hooks = XMLFile::getXMLByWhere('fronthooks',$query,$whereClause,_THEME_DIR_.'/assets/xml');
            $record = $hooks[0];
            $btnType = 'Edit';
            //var_dump($record);
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','module');
        $ret.= '<form action="core.php?'.Tools::newUrl('t','FrontHook').'" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">Front Hook Form</legend>';
        
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
        $ret.= '          <label>Title</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="title" type="text" value="'.(isset($record)?$record['title']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Description</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="description" type="text" value="'.(isset($record)?$record['description']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Position</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="position" type="text" value="'.(!empty($record['position'])?$record['position']:"1").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>ID Module</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="id_module">';
        $ret.= '          <option value="0"> Select option </option>';
        $modules = XMLFile::readFile('frontmodules','',_THEME_DIR_.'/assets/xml');
        if (!empty($modules)){
            foreach($modules as $module)
            {
                $checked = '';
                if ($record["id_module"] == $module["id"])
                {
                    $checked = 'selected';
                }
                $ret.= '<option value="'.$module["id"].'" '.$checked.'> '.$module["name"].' </option>';
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <button name="btnSubmit'.$btnType.'" class="btn flt-right" type="submit">'.$btnType.'</button>';
        $ret.= '    </div>';
        $ret.= '  </fieldset>';
        $ret.= '</form>';
        return $ret;
    }
    public static function viewFrontHook()
    {
        $ret = '';
        //$ret.= 'view Hook';
        if (Tools::getValue('act') == 'del'){
            $ret.= '<div class="msg">';
            $ret.= 'Action "Delete" was successful';
            $ret.= '</div>';
            XMLFile::deleteById('fronthooks',Tools::getValue('id'),_THEME_DIR_.'/assets/xml');
        }
        if (!file_exists(_THEME_DIR_.'/assets/xml/fronthooks.xml'))
        {
            $ret.= 'Initialization FrontHook file...';
            Core::initFrontHook();
        }
        $records = XMLFile::readFile('fronthooks','',_THEME_DIR_.'/assets/xml');
        //var_dump($records);
        if (!empty($records))
        {
            $fields = XMLFile::getFields('fonthooks','xml');
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
                $ret.= '  <a  onclick="return confirm(\'Are you sure?\')" href="core.php?t='.Tools::getValue('t').'&act=del&id='.$record["id"].'"><img class="tbl-ico32" src="images/advancetools/10871842.png"/></a>';
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