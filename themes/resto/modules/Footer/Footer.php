<?php
use classes\Module as Module;
class Footer extends Module {
    public static function hookFooter($args)
    {
        global $defaultBackground;
        $ret = "";
        //$ret.= "Hook: Footer";
        $ret.= '<p>Design by A&T KAIIROS</p>';
        return $ret;
    }
}
?>