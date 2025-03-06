<?php
use classes\Module as Module;
use classes\WebCore as RTTCore;
class Home extends Module {
    public static function hookHome($args)
    {
        $ret = "";
        //$ret.= "Hook: Home";
        $ret.= '<div class="row txt-center">';
        $ret.= '    <p>';
        $ret.= '    '.RTTCore::l("Welcome to eShop System!");
        $ret.= '    </p>';
        $ret.= '</div>';
        return $ret;
    }
}
?>