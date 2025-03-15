<?php 
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as RTTCore;
use classes\xLang as xLang;
use classes\myAbout as myAbout;

class About extends Module {
    public static function hookAbout($args)
    {   
        global $actions,$status,$actives;
        $records = array();
        $table = "about";
        myAbout::init();
        $records = myAbout::getRecords();
        
        $fieldsDisplay = array(
            'id_lang'      => RTTCore::l('Language'),
            'code'         => RTTCore::l('Code'),
            //'id_bom'       => RTTCore::l('Bill Of Material'),
            'title'        => RTTCore::l('Title'),
            'description'  => RTTCore::l('Description'),
            //'img_name'     => RTTCore::l('Image'),
            'active'       => RTTCore::l('Active'),
        );
        $ret = "";      
        //$ret.= "Hook: About";
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
        $ret.= '     <span class="tbl-title">'.RTTCore::l('About Us','admin').'</span>';
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