<?php
use classes\Module as Module;
use classes\xTab as xTab;
use classes\xLang as xLang;
use Bundles\classes\WebCore as  WebCore;
use classes\Tools as Tools;
use classes\myCompany as myCompany;

class Header extends Module {
    
    public function __construct()
    {
        //$this->className = "Header";
        //parent::__construct();
    }
    
    static function hookHeader($args)
    {
        //echo "Home module\n";//return "HomeShow";
        $ret = '';
        global $currentTimeZone, $server,$CusUser,$AdminUser,$user,$theme,$cookie, $systemrole;
        $imgCom = "";
        $recordCom = array();
        $langs = !empty(xLang::getRecords())?xLang::getRecords():array();
        if (!empty($AdminUser)){
            //echo $user['system_role'];
            if (myCompany::getById($AdminUser['id_company'])){
                $recordCom = myCompany::getById($AdminUser['id_company'])[0];
                $imgCom = $recordCom['img_name'];
            }
        }else{
            if (myCompany::getById($CusUser['id_company'])){
                $recordCom = myCompany::getById($CusUser['id_company'])[0];
                $imgCom = $recordCom['img_name'];
            }
        }
        //$ret.= '<div class="col-2">';
        $ret.= '<div class="col-2 logo"><img class="logo-header" src="'.(!empty($imgCom)?("images/s/".$imgCom):'../Bundles/images/logo_text_169x40.png').'" alt="eShop" tooltip="eShop" /></div>';
        $ret.= '<input type="hidden" id="id_sidebar" value="'.(!empty($theme)?$theme["nav"]:"").'"/>';
        $ret.= '<input type="hidden" id="id_user" value="'.(!empty($user)?$user["id_user"]:0).'"/>';
        $ret.= '<div style="" class="col-2 txt-right" >';
        $selected = "selected";
        $currentLangId = !empty(Tools::getValue("lang"))?Tools::getValue("lang"):(!empty($theme)?$theme["id_lang"]:1);
        if($langs)
        {
            /*
            $ret.= '<select onchange="javascript:reloadNewURL(\'lang\',this.value);" id="drop-down-example" name="hello_world" data-label="Select any currency" data-width="50%" data-imgpos="right" data-fusion="0">';
            foreach($langs as $lang)
            {
                if($lang["id"] == $currentLangId)
                {
                    $ret.= '<option style="background:url(images/langs/'.$lang["id"].'.png) no repeat;" value="'.$lang["id"].'"' .$selected.'><img src="images/langs/'.$lang["id"].'.png" /> '.$lang["title"].'</option>';
                }else{
                    $ret.= '<option value="usd" data-img="https://pluginus.net/wp-content/uploads/2021/03/united_states_of_america.gif" data-text="United States" value="'.$lang["id"].'"><img src="images/langs/'.$lang["id"].'.png" /> '.$lang["title"].'</option>';
                }
            }
            $ret.= '</select>';
            */
            $ret.= '<div class="col-4" style="background: none; width:68%;margin-top:-30px;">';
            $ret.= '<select onchange="" id="lang" name="lang" data-label="Select any currency" data-width="50%" data-imgpos="left" data-fusion="0">';
            foreach($langs as $lang)
            {
                if($lang["id"] == $currentLangId)
                {
                    $ret.= '    <option value="'.$lang["id"].'" data-img="images/langs/'.$lang["id"].'.png" data-text="'.$lang["iso_code"].'"'.$selected.'>'.$lang["title"].'</option>';
                }else{
                    $ret.= '    <option value="'.$lang["id"].'" data-img="images/langs/'.$lang["id"].'.png" data-text="'.$lang["iso_code"].'">'.$lang["title"].'</option>';
                }
                //$ret.= '    <option value="'.$lang["id"].'" data-img="images/langs/'.$lang["id"].'.png" data-text="'.$lang["isi_code"].'">'.$lang["title"].'</option>';
            }
            //$ret.= '    <option value="usd" data-img="https://pluginus.net/wp-content/uploads/2021/03/united_states_of_america.gif" data-text="United States">USD</option>';
            //$ret.= '    <option value="eur" data-img="https://pluginus.net/wp-content/uploads/2021/03/european_union.gif" data-text="Euro union">EUR</option>';
            //$ret.= '    <option value="gbp" data-img="https://pluginus.net/wp-content/uploads/2021/03/united_kingdom.gif" data-text="Great Britain">GBP</option>';
            $ret.= '</select>';
            //$ret.='Selected value: <span id="selector3-value" style="font-weight: bold;">-</span><br />';
            $ret.= '</div>';
        }
        
        $ret.= '<div class=""><span style="margin-top:-15px" id="clock"></span><a href="index.php?'.Tools::newURL('logout','1').'" class="logout"><i class="bx bx-power-off" ></i></a></div></div>';
        //$ret.= '<div class="flt-right">Time Zone: '.$currentTimeZone.'</div>';
        return $ret;
    }
}
?>