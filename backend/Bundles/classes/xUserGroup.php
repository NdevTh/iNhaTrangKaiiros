<?php
namespace Bundles\classes;
use Bundles\classes\XMLFile as BundlesXMLFile;
class xUserGroup
{
    public static function getById($id)
    {
        $whereClause = '[@id="'.$id.'"]';
        $query = '//usergroup/usergrou';
        $records = BundlesXMLFile::getXMLByWhere('usergroup',$query,$whereClause,'xml');
        $record = $records[0];
        return $record;
    }
    public static function getRecords()
    {
        if (!file_exists("xml/usergroup.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('usergroup','','xml');
    }
    public static function init()
    {
        /* create module data */
        $moduleFields = array('id','name','description');
        $moduleData   = array(
           array(
              'name'        => 'Manager',
              'description' => 'Utilisateur sans limite'
           ),
           array(
             'name'        => 'Technicien',
             'description' => 'Limiter certain droit'
           ),
           array(
             'name'        => 'Achat',
             'description' => 'Limiter certain droit'
           ),
           array(
             'name'        => 'Livraison',
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
            BundlesXMLFile::writeFile('usergroup',$moduleFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','usergroup',"xml");
        //var_dump($listModule);
    }
}    
?>