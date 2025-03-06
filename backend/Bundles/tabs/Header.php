<?php
use Bundles\classes\AdminTab as AdminTab;
class Header extends AdminTab
{
    public static function viewHeader()
    {
        $ret = '';
        $ret.= '<div class="row">';
        $ret.= '   <div class="col-4">';
        $ret.= '     <img class="logo32x32" src="images/logo_text_169x40.png"/>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-8 txt-right">';
        $ret.= '     Core';
        $ret.= '   </div>';
        $ret.= '</div>';
        return $ret;
    }
}
?>