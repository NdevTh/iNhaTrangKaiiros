<?php
use classes\Module as Module;
class CTA extends Module
{
    public static function hookCTA($args)
    {
        $ret = "";
        $ret.= "Hook: CTA";
        return $ret;
    }
}
?>