<?php
use classes\Module as Module;
use classes\WebCore as RTTCore;
use classes\Tools as Tools;
use classes\xFrontTab as xFrontTab;

class Header extends Module {
    public static function hookHeader($args)
    {
        global $defaultBackground,$cookie;
        $ret = "";
        //$ret.= "Hook: Head";
        //<header>
        $ret.= '<h1>i NhaTrang</h1>';
        $ret.= '<h2>Sous le signe de la dégustation</h2>';
        $ret.= '<p>Tous les samedis au marché de Weyersheim</p>';
        //</header>

        return $ret;
    }
}
?>