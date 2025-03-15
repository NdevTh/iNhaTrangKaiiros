<?php
use classes\Module as Module;
use classes\myArticle as myArticle;

class Product extends Module 
{    
    public static function hookProduct($args)
    {
        $records = array();
        $ret = "";
        //$ret.= "Hook: Product";
        $records = myArticle::getByWhere('id_categorie='._PRODUCT_SALE_);
        if($records)
        {
            foreach($records as $record)
            {
                $ret.= '<div class="menu-item">';
                $ret.= '   <img src="'._ADMIN_BASE_DIR_.'/images/equ/'.(!empty($record['img_name'])?$record['img_name']:'nems.jpg').'" alt="Nems au porc">';
                $ret.= '   <h3>'.(!empty($record['title'])?$record['title']:'P1. Nems au porc').'</h3>';
                $ret.= '   <p>'.(!empty($record['description'])?$record['description']:'Porc haché, vermicelles de riz, carotte, oignon, galettes de riz, ciboulette').'</p>';
                $ret.= '   <p class="price">'.(!empty($record['sale_unit_price'])?$record['sale_unit_price']:'1,6€ / pc').'</p>';
                $ret.= '</div>';
            }
        }
        return $ret;
    }
}
?>