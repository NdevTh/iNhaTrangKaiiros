<?php
    date_default_timezone_set("Europe/Paris");
    use classes\Dispatcher as Dispatcher;
    use classes\Module as Module;
    use classes\Tools as Tools;
    use Bundles\classes\WebCore as WebCore;

    //include files
    include('config/config.inc.php');
    include('header.inc.php');
    include "phpqrcode/qrlib.php"; 
    
    //echo Tools::getValue('t');
    global $AdminUser, $CusUser;
    $hook = !empty(Tools::getValue('t'))?Tools::getValue('t'):(!empty($AdminUser)?'home':'CusDashboard');
    Dispatcher::display(array(
       'header'      => Module::hookExec('header'),
       'nav'         => Module::hookExec('nav'),
       'home'        => Module::hookExec($hook),
       //'footer'      => Module::hookExec('footer')
    ));

    WebCore::includeJS(array('js/tools.js?v'.rand(),'js/selectflag.js?v'.rand()));
?>