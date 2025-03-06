<?php
use classes\Module as Module;
class Login extends Module {
    public static function hookLogin($args)
    {
        $ret = "";
        //$ret.= "Hook: Login";
        $ret.= '<div class="form-login">';
        $ret.= '    <div class="bg-form">';
        $ret.= '    </div>';
        $ret.= '</div>';
        return $ret;
    }
}
?>