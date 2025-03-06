<?php
use classes\Module as Module;
use classes\Tools as Tools;
use classes\WebCore as WebCore;
use classes\myArticle as myArticle;
use classes\myOrder as myOrder;
use classes\myOrderDetail as myOrderDetail;

class Preparation extends Module
{
    public static function hookPreparation($args)
    {
        $records     = array();
        $messages    = array();
        $btnType     = 'Add';
        $currentDate = date('Y-m-d H:i:s',time());
        $list_id     = 0;
        myOrder::init();
        myOrderDetail::init();
        
        if (Tools::getValue('id')){
            $_POST["id_type"]  = _PREPA_;
            $_POST["date_upd"] = $currentDate;
            $fields = array("id_type","date_upd");
            myOrder::updateByFields(Tools::getValue('id'),$fields);
        }
        $ret = "";
        //$ret.= "Hook: Preparation";
        $ret.= '<form action="index.php?'.Tools::newUrl('t',Tools::getValue('t')).'" method="POST" class="">';
        /* Tools Bar */
        $ret.= '   <div class="row tools-title">';
        $ret.= '     <div class="col-2 txt-left">';
        //$ret.= '        <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&token='.Tools::getValue('token').'"><i class="bx bx-arrow-to-left"></i>Retourner</a>';
        $ret.= '     </div>';
        $ret.= '     <div class="col-2 txt-right">';
        $ret.= '        <button name="btnSubmit'.$btnType.'" type="submit" class="btn-form"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/>&ensp;'.WebCore::l('Save','admin').'</button>';
        $ret.= '     </div>';
        $ret.= '   </div>';
        /* Tools Bar */
        /* Formulaire */
        $ret.= '  <div class="form">';
        /* Form Title */
        $ret.= '    <div class="row">';
        $ret.= '       <span class="frm-title">'.WebCore::l('Preparation','admin').'</span>';
        $ret.= '    </div>';
        /* message Actions */
        if ($messages)
        {
            $ret.= '<div class="msg">';
            foreach($messages as $k => $message)
            {
                $ret.= '<span class="'.$message.'">' . WebCore::l('Operation:  "'.$k.'" succesful') . '</span><br/>';
            }
            $ret.= '</div>';
        }
        
        /* End Message Actions */
        /* Form utils */
        $ret.= '    <div class="row bg-form">';
        
        /* Tab */
        $ret.= '    <div class="tab">';
        $ret.= '      <button type="button" class="tablinks defaultTab" onclick="openTab(event, \'detailtab\')" id="defaultTab">'.WebCore::l('By Order','admin').'</button>';
        $ret.= '      <button id="idarticletab" type="button" class="tablinks" onclick="openTab(event, \'articletab\')">'.WebCore::l('By Article','admin').'</button>';
        $ret.= '    </div>';
        
        /* General Tab */
        $ret.= '    <div id="detailtab" class="tabcontent">';
        $ret.= self::detailTab($list_id);
        $ret.= '    </div>';
        /* End General Tab */
        
        /* Payment Tab */
        $ret.= '    <div id="articletab" class="tabcontent">';
        $ret.= self::articleTab($list_id);
        $ret.= '    </div>';
        /* End Payment Tab */
        
        /* End Tab */
        
        $ret.= '    </div>';
        /* End Form utils */
        
        $ret.= '  </div>';
        /* End Formulaire */
        
        /* Modal Block */
        
        /* End Modal Block */
        
        /* Form */
        $ret.= '</form>';
        return $ret;
    }
    
    public static function detailTab($idWorkOrder)
    {
        $table = 'order_list';
        $currentDate = date('Y-m-d',time());
        if (Tools::getValue('date')){
            $currentDate = Tools::getValue('date');
        }
        //echo $currentDate;
        //$recordsList = myOrder::getByDate($currentDate);
        $recordsList = myOrder::getByWhere("DATE(date_add) = '".$currentDate."' AND `id_type` < "._PREPA_);
        //var_dump($recordsList);
        $ret.= '<div class="row">';
        $ret.= '     <div class="col-3">';
        $ret.= '          <div class="col-1">';
        $ret.= '               <div class="groupform">';
        $ret.= '                    <label>Date </label>';
        $ret.= '               </div>';
        $ret.= '               <div class="groupfield">';
        $ret.= '                    <input onchange="window.location=\'index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').''.'&token='.Tools::getValue('token').'&date=\'+this.value" name="current_date" type="date" value="'.date('Y-m-d',strtotime($currentDate)).'"/>';
        $ret.= '               </div>';
        $ret.= '          </div>';
        $ret.= '     </div>';
        $ret.= '</div>';
        
        $ret.= '<div class="row">';
        if (!empty($recordsList)){
            foreach($recordsList as $record)
            {
                //$ret.= $record['id_order_list'].' '.$record['title'].'<br/>';
                $recordsDetail = myOrderDetail::getByWhere("`id_order_list`=".$record["id_order_list"]);
                $ret.= '   <div class="col-3">';
                $ret.= '       <div class="card">';
                $ret.= '          <div class="title-card">';
                $ret.= '          <h3><a class="" href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_".$table].'&token='.Tools::getValue('token').'&tp=1"><img class="tbl-ico32" src="../Bundles/images/advancetools/10629723.png"/>'.$record['title'].'</a></h3>';
                $ret.= '          </div>';
                if (!empty($recordsDetail)){
                    foreach ($recordsDetail as $recordDetail ){
                        $article = myArticle::getById($recordDetail['id_article'])[0];
                        $ret.= '          <p >';
                        $ret.= '            <span class="p-card">'.$article['title'].'</span><span class="span-val">'.$recordDetail['order_quantity'].'</span>';
                        $ret.= '          </p>';
                    }
                }
                $ret.= '       </div>';
                $ret.= '   </div>';
        
            }
        }
        $ret.= '</div>';
        return $ret;
    }
    
    public static function articleTab($idWorkOrder)
    {
        $table = "order_detail";
        $currentDate = date('Y-m-d',time());
        $record  = myOrder::getById($idWorkOrder)[0];
        $ret = '';
        $recordsJournal = array();
        /*
        if ($record["id_order_list"]){
            $recordsJournal = myOrderDetail::getByArticleGroup();
        }*/
        $recordsJournal = myOrderDetail::getByArticleDate($currentDate);
        $ret.= '      <div class="row tools-title">';
        $ret.= '         <span class="tbl-title">'.WebCore::l('Order Detail','admin').'</span>';
        $ret.= '         <span id="'.$table.'_records" class="article">'.count($recordsJournal).' '.WebCore::l('Records','admin').'</span>';
        $ret.= '         <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search" type="text" placeholder="'.WebCore::l('Type Keyword for Searching','admin').'" />';
        $ret.= '      </div>';
        
        $journalFields = array(
           'code'                   => WebCore::l('Code'),
           'title'                  => WebCore::l('Title'),
           'order_quantity'         => WebCore::l('Quantity'),
           /*'sale_unit_price'        => WebCore::l('Unit Price'),
           'sale_discount_percent'  => WebCore::l('Discount (%)'),
           'sale_discount_amount'   => WebCore::l('Total Discount'),
           'sale_amount_cost'       => WebCore::l('Total Detail')*/
        );
        $ret.= '     <div class="tbl-tools">';
        $ret.= '        <button onclick="showModal(\'modalArticle\')" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1090923.png"/></button>';
        $ret.= '        <button onclick="addRow(\'workorder_journal\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\'])" type="button" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/1828819.png"/></button>';
        //$ret.= '        <button onclick="addRow(\'myTable\','.$taskFields.')" type="button" class="tbl-btn"><img class="tbl-ico32" src="Bundles/images/advancetools/1828819.png"/></button>';
        $ret.= '        <button onclick="return confirm(\'Are you sure?\')"  name="btnSubmitDelTask" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/10871842.png"/></button>';
        $ret.= '        <button name="btnSubmitAddOrderDetail" type="submit" class="tbl-btn"><img class="tbl-ico16" src="../Bundles/images/advancetools/3067443.png"/></button>';
        $ret.= '     </div>';
        
        $ret.= '     <div class="row table bg-tbl">';
        $ret.= '       <table id="order_detail">';
        $ret.= '         <tr>';
        $ret.= '            <th><input onchange="checkAll(this,\'article_group\',[\'checkbox\',\'code\',\'title\',\'quantity\',\'unit_price\',\'discount_percent\',\'discount_price\',\'total_detail\',\'action\']);"  id="chkAll" type="checkbox"></th>';
        $indCol = 0;
        foreach($journalFields as $k => $th)
        {
            $indCol++;
            $ret.= '        <th onclick="sortTable('.$indCol.',\'article_group\')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'article_group" src="../Bundles/images/advancetools/4340090.png"/> &nbsp; '.$th.'</th>';
        }
        $ret.= '            <th class="bg-orange">'.WebCore::l('Action').'</th>';
        $ret.= '         </tr>';
        /* body table */
        if ($recordsJournal){
            $indRow = 0;
            foreach($recordsJournal as $recordJournal)
            {
                $indRow++;
                $discount_amount = 0;
                $ret.= ' <tr>';
                $ret.= '        <td class="txt-center"><input onchange="editRow(this,\'order_detail\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" id="order_detail'.$indRow.'" type="checkbox" value="'.$recordJournal['id_order_detail'].'"/></td>';
                foreach($journalFields as $field => $th)
                {
                    if ($field == 'order_quantity')
                    {
                        $ret.= '<td class="txt-right">'.(!empty($recordJournal[$field])?$recordJournal[$field]:0).'</td>';
                    }else if ($field == 'sale_unit_price' OR $field == 'sale_discount_percent')
                    {
                        $ret.= '<td class="txt-right">'.number_format((float)$recordJournal[$field],2).'</td>';
                    }else if ($field == 'sale_discount_amount')
                    {
                        $discount_amount = (number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2)) * ( number_format((float)$recordJournal['sale_discount_percent'],2) / 100 );
                        $totalRemise += $discount_amount;
                        $ret.= '<td class="txt-right">'.number_format((float)$discount_amount,2).'</td>';
                    }else if ($field == 'sale_amount_cost')
                    {
                        $order_amount = number_format((float)$recordJournal['order_quantity'],2) * number_format((float)$recordJournal['sale_unit_price'],2);
                        $totalDetail  += $order_amount;
                        $ret.= '<td class="txt-right">'.number_format((float)($order_amount - $discount_amount ),2).'</td>';
                    }elseif ($field != 'title')
                    {
                        $ret.= '<td class="">'.$recordJournal[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=list&idor='.$recordJournal["id_order_detail"].'&token'.Tools::getValue('token').'" >'.$recordJournal[$field].'</a></td>';
                    }
                }
                $ret.= '        <td class="txt-center">';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/></a>';
                //$ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_installation"].'"><img class="tbl-ico32" src="Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '        </td>';
                $ret.= ' </tr>';
            }
        }else {
            $ret.= '     <tr class="tbl-empty">';
            $ret.= '     </tr>';
        }
        $ret.= '       </table>';
        $ret.= '     </div>';
        
        $totalExonere =  (float)$totalDetail - (float)$totalRemise ;
        $tva          = 20;
        $totalTVA     =  ((float)$totalExonere * $tva)/100;
        $totalTTC     = (float)$totalExonere + (float)$totalTVA;
        $totalSoumise = '0';
        
        $ret.= '<script type="text/javascript">';
        $ret.= '     document.getElementById("total_detail").innerText  = "'.number_format($totalDetail,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_remise").innerText  = "'.number_format($totalRemise,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_exonere").innerText = "'.number_format($totalExonere,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_soumise").innerText = "'.number_format($totalSoumise,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_tva").innerText     = "'.number_format($totalTVA,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '     document.getElementById("total_ttc").innerText     = "'.number_format($totalTTC,2).' " + document.getElementById("currency_symbole").value;';
        $ret.= '</script>';
            
        $ret.= '<input class="" id="total_detail" name="total_detail"  type="hidden" value="'.number_format($totalDetail,2).'"/>';
        $ret.= '<input class="" id="total_remise" name="total_remise"  type="hidden" value="'.number_format($totalRemise,2).'"/>';
        $ret.= '<input class="" id="total_exonere" name="total_exonere"  type="hidden" value="'.number_format($totalExonere,2).'"/>';
        $ret.= '<input class="" id="total_tva" name="total_tva"  type="hidden" value="'.number_format($totalTVA,2).'"/>';
        $ret.= '<input class="" id="total_ttc" name="total_ttc"  type="hidden" value="'.number_format($totalTTC,2).'"/>';
        $ret.= '<input class="" id="total_soumise" name="total_soumise"  type="hidden" value="'.number_format($totalSoumise,2).'"/>';
            
        return $ret;
    }
    
}
?>