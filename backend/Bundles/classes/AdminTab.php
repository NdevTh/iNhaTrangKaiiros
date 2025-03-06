<?php
namespace Bundles\classes;
//use Bundles\classes\AdminTab as  BundlesAdminTab;
use Bundles\classes\Tools as Tools;
    
class  AdminTab
{
    public function __construct(){}
    public static function loadTab($tab = 'home')
    {
        global $dirAdmin;
        $altern = 0;
        $output = '';
        //$output .= $module['module_name'] . "<br/>";
        $moduleInstance = AdminTab::getInstanceByName($tab);
        if (!$moduleInstance){
            //$output .= 'ModuleInstance ' . $module['module_name'] . ' does\'t exist.<br/>';
            //continue;
            return false;
        }
        //$output.= $tab;
        $action = isset($_GET['act'])?(is_callable(array($moduleInstance, Tools::getValue('act')))?Tools::getValue('act'): 'view'.ucfirst($tab)) : 'view'.ucfirst($tab);
        if (is_callable(array($moduleInstance, $action)))
        {
            //$output .= $module['module_name'] . "<br/>";
            $hookArgs['altern'] = ++$altern;
            $output .= call_user_func(array($moduleInstance, $action ), $hookArgs);
        }
        return $output;
    }
    
    public static function getInstanceByName($tabName)
    {
        /* global variables */
        global $dirModule;
        /* check if file exist */
        if (!file_exists('tabs/'.$tabName.'.php'))
        {
            return false;
        }
        include_once('tabs/'.$tabName.'.php');
        /* check if classname exist */
        if (!class_exists($tabName, false))
        {
            return false;
        }
        /* return object */
        return (new $tabName);
    }
}