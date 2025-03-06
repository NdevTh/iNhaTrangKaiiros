<?php
use classes\Module as Module;
class Header extends Module {
    public static function hookHeader($args)
    {
        $ret = "";
        $ret.= "Hook: Head";
        return $ret;
    }
}
?>