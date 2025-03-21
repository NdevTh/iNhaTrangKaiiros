<?php
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as RTTCore;
use classes\myArticle as myArticle;

class AdjustmentStockGroup extends Module
{
    public static function hookAdjustmentStockGroup($args)
    {
        global $actions, $defaultCurrency;
        $table   = 'company';
        $records = array();
        $records = myArticle::getByAdjustmentStockGroup();
        $ret     = "";
        //$ret.= "Hook: AdjustmentStockGroup";
        $fieldsDisplay = array(
            'code'                  => RTTCore::l('Code'),
            'img_name'              => RTTCore::l('Image'),
            'company'               => RTTCore::l('Supplier'),
            'description'           => RTTCore::l('Description'),
            'sum_total_ttc'         => RTTCore::l('Purchased Amount'),
            'sum_paid'              => RTTCore::l('Paid Amount'),
            'active'                => RTTCore::l('Active'),
        );
        
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
                    if (!empty($record) && array_key_exists($field, $record) && $field == 'sum_paid')
                    {
                        $ret.= '<td class="txt-right">'.number_format($record[$field],2).' '.(!empty($record['currency_symbole'])?$record['currency_symbole']:$defaultCurrency).'</td>';
                    }else if (!empty($record) && array_key_exists($field, $record) && $field == 'sum_total_ttc')
                    {
                        $ret.= '<td class="txt-right">'.number_format($record[$field],2).' '.(!empty($record['currency_symbole'])?$record['currency_symbole']:$defaultCurrency).'</td>';
                    }else if ( !empty($record) && array_key_exists($field, $record) && $field  == 'img_name')
                    {
                        $ret.= '<td class="txt-center"><img class="tbl-ico32" src="images/s/'.(!empty($record)?$record['img_name']:'blank.jpg').'"></a></td>';
                    }else if (!empty($record) && array_key_exists($field, $record) && $field == 'status')
                    {
                        $ret.= '<td class="txt-center stat '.strtolower($statusUser[$record[$field]]).'">'.$statusUser[$record[$field]].'</td>';
                    }else if (!empty($record) && array_key_exists($field, $record) && $field == 'company')
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