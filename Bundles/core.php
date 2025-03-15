<?php
    include('config/config.inc.php');
    include('header.inc.php');
    use Bundles\classes\Tools as Tools;
    use Bundles\classes\AdminTab as  AdminTab;
    use Bundles\classes\Dispatcher as  Dispatcher;
    use Bundles\classes\WebCore as WebCore;
    
    //echo 'Bundles';
    //echo Tools::getValue('t');
    $tab = !empty(Tools::getValue('t'))?Tools::getValue('t'):'module';
    //echo AdminTab::loadTab('module');
    Dispatcher::display(array(
        'header'     => AdminTab::loadTab('header'),
        'container'  => AdminTab::loadTab('menu') .'<div class="main">'. AdminTab::loadTab($tab) .'</div>',
        'footer'     => AdminTab::loadTab('footer')
    ));
    WebCore::includeJS(array('js/table.js?v'.rand()));
?>