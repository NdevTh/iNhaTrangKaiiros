<?php
use classes\Module as Module;
use classes\Tools as Tools;

use classes\WebCore as RTTCore;
use classes\myMessage as myMessage;
use classes\myProject as myProject;
use classes\myQuotationOrder as myQuotationOrder;
use classes\myPurchaseOrder as myPurchaseOrder;

class Home extends Module
{
    public static function hookHome($args)
    {
        //Select * from mytable where STR_TO_DATE(concat(year,"-",month,"-01"),'%Y-%m-%d')>date_sub(curdate(),Interval 3 month) ;
        $ret = "";
        $nbNote = count(myMessage::getByWhere('`readed`="0"'));
        $currentDate = date('Y-m-d H:i:s',time());
        $year  = date("Y",time());
        $month = date('m', strtotime("+3 month"));
        $whereDate = $year.'-'.$month.'-01';
        //echo $whereDate;
        
        //echo STR_TO_DATE(concat(year,"-",month,"-01"));
        $nbProj = count(myProject::getByWhere('`date_end` <= "'.$whereDate.'" AND '.'`date_end` >= "'.$currentDate.'"'));
        //var_dump($nbProj);
        
        $nbQuot  = count(myQuotationOrder::getByWhere('`id_status` = "0"'));
        $nbQuotP = count(myQuotationOrder::getByWhere('`id_status` = "1"'));
        $nbQuotV = count(myQuotationOrder::getByWhere('`id_status` = "2"'));
        
        $nbPurchO = count(myPurchaseOrder::getByWhere('`id_status` = "0"'));
        $nbPurchC = count(myPurchaseOrder::getByWhere('`id_status` = "1"'));
        
        //$ret.= "Hook: Home";
        $ret.= '<div class="row">';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('New Messages','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbNote;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('Contracts Fin ( < 3 months)','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbProj;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('Quotations Pending','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbQuotP;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $ret.= '</div>';
        
        $ret.= '<div class="row">';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('Quotation Validating','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbQuotV;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('Purchase Opening','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbPurchO;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $ret.= '   <div class="col-3">';
        $ret.= '       <div class="card">';
        $ret.= '          <div class="title-card">';
        $ret.= '            <h3>'.RTTCore::l('Purchase Canceling','admin').'</h3>';
        $ret.= '          </div>';
        $ret.= '          <div class="ctn-card">';
        $ret.= '            '.$nbPurchC;
        $ret.= '          </div>';
        $ret.= '       </div>';
        $ret.= '   </div>';
        
        $string = "Identification Of Document";
        $key = md5(str_replace('\'', '\\\'', $string));
        $str = str_replace(" ","_",$string);
        $ret.= $str.'_'.$key;
        
        $ret.= '</div>';
        return $ret;
    }
}
?>