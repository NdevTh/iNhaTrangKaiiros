<?php
namespace classes;
use Bundles\classes\XMLFile as BundlesXMLFile;
class xSystemRole
{
    public static function getById($id)
    {
        $whereClause = '[@id="'.$id.'"]';
        $query = '//systemrole/systemrol';
        $records = BundlesXMLFile::getXMLByWhere('systemrole',$query,$whereClause,'../Bundles/xml');
        $record = $records[0];
        return $record;
    }
    
    public static function getRecords()
    {
        if (!file_exists("Bundles/xml/systemrole.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('systemrole','','../Bundles/xml');
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
            BundlesXMLFile::writeFile('systemrole',$moduleFields,"","../Bundles/xml");
        }
        $listModule = BundlesXMLFile::getList('name','systemrole',"../Bundles/xml");
        //var_dump($listModule);
    }
}    
?>