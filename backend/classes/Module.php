<?php
namespace classes;
use Bundles\classes\Module as BundlesModule;
use Bundles\classes\XMLFile as  BundlesXMLFile;

class Module extends BundlesModule
{
    public static function hookExecShop($hook_name,$path="")
    {
        $ret = '';
        //return $hook_name;
        try{
            $ret  = '';
            $altern = 0;
            
            //$ret .= $hook_name;
            //echo $hook_name;
            $whereClause = '[name="'.$hook_name.'"]';
            $query = '//frontmodules/frontmodule';
            $modules = BundlesXMLFile::getXMLByWhere('frontmodules',$query,$whereClause,_THEME_DIR_.'/assets/xml');
            $module = !empty($modules[0])?$modules[0]:array();
            //echo 'id : '.$module["id"];
            //var_dump($module);
            $hook = array();
            if (!empty($module))
            {
                $whereClause = '[id_module="'.$module["id"].'"]';
                $query = '//fronthooks/fronthook';
                $hooks = BundlesXMLFile::getXMLByWhere('fronthooks',$query,$whereClause,_THEME_DIR_.'/assets/xml');
                //var_dump($hooks);
            } else {
                $whereClause = '[name="'.$hook_name.'"]';
                $query = '//fronthooks/fronthook';
                $hooks = BundlesXMLFile::getXMLByWhere('fronthooks',$query,$whereClause,_THEME_DIR_.'/assets/xml');
            }
            //var_dump($hooks);echo '<br/>';
            
            if (!empty($hooks))
            {
                //var_dump($hooks);
                foreach($hooks as $hook =>$module)
                {
                    //$ret.= 'Module: ' .$module["name"] ."<br/>";
                    $moduleInstance = self::getInstanceByName($module['name'],_THEME_DIR_);
                    if (!$moduleInstance){
                        $ret .= 'ModuleInstance ' . $module['name'] . ' does\'t exist.<br/>';
                        continue;
                    }
                    //$ret .= 'hook'.ucfirst($hook_name);
                    //var_dump($moduleInstance);
                    $action = !empty($_GET['act'])?( is_callable(array($moduleInstance, Tools::getValue('act')) )?Tools::getValue('act'): 'hook'.ucfirst($hook_name)) : 'hook'.ucfirst($hook_name);
                    //$ret.= 'action: ' .$action . " ";
                    if (is_callable(array($moduleInstance, $action)))
                    {
                        //$ret .= $module['name'] . " action:" .$action . " <br/>";
                        $hookArgs['altern'] = ++$altern;
                        $ret .= call_user_func(array($moduleInstance, $action ), $hookArgs);
                    }
                }
            }
            return $ret;
        } catch (Error $e){
            return $e->getMessage();
        }
    }
}    
?>