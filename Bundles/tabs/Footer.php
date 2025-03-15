<?php
use Bundles\classes\AdminTab as AdminTab;
class Footer extends AdminTab
{
    public static function viewFooter()
    {
        $ret = '';
        $ret.= 'PHP Version: ' . phpversion();
        return $ret;
    }
}
?>