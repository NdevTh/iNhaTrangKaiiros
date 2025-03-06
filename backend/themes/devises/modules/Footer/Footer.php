<?php
use classes\Module as Module;
class Footer extends Module {
    public static function hookFooter($args)
    {
        $ret = "";
        $ret.= "Hook: Footer";
        return $ret;
    }
}
?>