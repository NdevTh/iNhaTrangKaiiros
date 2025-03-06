<?php
namespace classes;
use Bundles\classes\XMLFile as BundlesXMLFile;
class xCategorieCompany
{
    public static function getRecords()
    {
        if (!file_exists("Bundles/xml/categoriecompany.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('categoriecompany','','Bundles/xml');
    }
    public static function init()
    {
        /* create module data */
        $moduleFields = array('id','name','description');
        $moduleData   = array(
           array(
              'name'        => 'Service',
              'description' => 'Utilisateur sans limite'
           ),
           array(
             'name'        => 'Production',
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
            BundlesXMLFile::writeFile('categoriecompany',$moduleFields,"","Bundles/xml");
        }
        $listModule = BundlesXMLFile::getList('name','categoriecompany',"Bundles/xml");
        //var_dump($listModule);
    }
}    
?>