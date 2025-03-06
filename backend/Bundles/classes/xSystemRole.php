<?php
namespace Bundles\classes;
use Bundles\classes\XMLFile as BundlesXMLFile;
class xSystemRole
{
    public static function getById($id)
    {
        $whereClause = '[@id="'.$id.'"]';
        $query = '//systemrole/systemrol';
        $records = BundlesXMLFile::getXMLByWhere('systemrole',$query,$whereClause,'xml');
        $record = $records[0];
        return $record;
    }
    
    public static function getRecords()
    {
        if (!file_exists("xml/systemrole.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('systemrole','','xml');
    }
    public static function init()
    {
        /* create module data */
        $moduleFields = array('id','name','description');
        $moduleData   = array(
           array(
              'name'        => 'Adminstrator',
              'description' => 'Utilisateur sans limite'
           ),
           array(
             'name'        => 'Employee',
             'description' => 'Limiter certain droit'
           ),
           array(
             'name'        => 'Customer',
             'description' => 'Limiter certain droit'
           )
        );
        foreach($moduleData as $c => $record)
        {
            foreach($moduleFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('systemrole',$moduleFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','systemrole',"xml");
        //var_dump($listModule);
    }
}    
?>