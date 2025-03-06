<?php
use classes\Dispatcher as Dispatcher;
use classes\Tools as Tools;
use classes\Module as Module;

include('config/config.inc.php');
include('header.inc.php');
$hook = (!empty(Tools::getValue('t'))?Tools::getValue('t'):'Home');
Dispatcher::display(array(
    'header'      => Module::hookExecShop('Header'),
    'home'        => Module::hookExecShop($hook),
    'footer'      => Module::hookExecShop('Footer')
));
//echo "New eShop -  from Tools:  " .Tools::getValue('t')." : From Module: ". Module::hookExecShop($hook);
include('footer.inc.php');
?>