<?php
namespace Bundles\classes;
//use Bundles\classes\Module as  BundlesModule;
use Bundles\classes\XMLFile as  BundlesXMLFile;

class  Module
{
    public static $requiredFields = array('id_module','name','active');
    
    public static function getInstance()
    {
        /* global variables */
        global $dirModule;
        /* check if file exist */
        if (!file_exists($dirModule.'/'.$this->className.'/'.$this->className.'.php'))
        {
            return false;
        }
        
        /* return object */
        return (new $this->className);
    }
    
    public static function getInstanceByName($moduleName,$path="")
    {
        /* global variables */
        
        /* check if file exist */
        $file = 'modules/'.$moduleName.'/'.$moduleName.'.php';
        if (!empty($path))
        {
            $file = $path.'/'.$file;
        }
        //echo "File location: " . $file ."<br/>";
        if (!file_exists($file))
        {
            return false;
        }
        include_once($file);
        /* check if classname exist */
        if (!class_exists($moduleName, false))
        {
            return false;
        }
        /* return object */
        return (new $moduleName);
    }
    
    public static function hookExec($hook_name,$path="")
    {
        $ret = '';
        //return $hook_name;
        try{
            $ret  = '';
            $altern = 0;
            
            //$ret .= $hook_name;
            
            $whereClause = '[name="'.$hook_name.'"]';
            $query = '//modules/module';
            $modules = BundlesXMLFile::getXMLByWhere('modules',$query,$whereClause,'../Bundles/xml');
            $module = isset($modules[0])?$modules[0]:array();
            //echo 'id : '.$module["id"];
            //var_dump($module);
            $hook = array();
            if (!empty($module))
            {
                $whereClause = '[id_module="'.$module["id"].'"]';
                $query = '//hooks/hook';
                $hooks = BundlesXMLFile::getXMLByWhere('hooks',$query,$whereClause,'../Bundles/xml');
                //var_dump($hooks);
            } else {
                $whereClause = '[name="'.$hook_name.'"]';
                $query = '//hooks/hook';
                $hooks = BundlesXMLFile::getXMLByWhere('hooks',$query,$whereClause,'../Bundles/xml');
            }
            //var_dump($hooks);echo '<br/>';
            
            if (!empty($hooks))
            {
                //var_dump($hooks);
                foreach($hooks as $hook =>$module)
                {
                    //$ret.= 'Module: ' .$module["name"];
                    $moduleInstance = self::getInstanceByName($module['name']);
                    if (!$moduleInstance){
                        $ret .= 'ModuleInstance ' . $module['name'] . ' does\'t exist.<br/>';
                        continue;
                    }
                    //$ret .= 'hook'.ucfirst($hook_name);
                    //var_dump($moduleInstance);
                    $action = isset($_GET['act'])?( is_callable(array($moduleInstance, Tools::getValue('act')) )?Tools::getValue('act'): 'hook'.ucfirst($hook_name)) : 'hook'.ucfirst($hook_name);
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