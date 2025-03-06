<?php
namespace classes;
use Bundles\classes\XMLFile as BundlesXMLFile;

class xLang
{
    public static function getById($id)
    {
        $whereClause = '[@id="'.$id.'"]';
        $query = '//langs/lang';
        $records = BundlesXMLFile::getXMLByWhere('langs',$query,$whereClause,'../Bundles/xml');
        $record = ($records !== null)?$records[0]:array();
        return $record;
    }
    
    public static function getRecords()
    {
        if (!file_exists("../Bundles/xml/langs.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('langs','','../Bundles/xml');
    }
    public static function init()
    {
        /* create module data */
        $moduleFields = array('id_lang','iso_code','title','active');
        $moduleData   = array(
           array(
                  'id_lang'     => '1',
                  'iso_code'    => 'en',
                  'title'        => 'English (Default)',
                  'active'      => '1'
           ),
           array(
                  'id_lang'     => '2',
                  'iso_code'    => 'fr',
                  'title'        => 'Française (France)',
                  'active'      => '1'
           ),
           array(
                  'id_lang'     => '3',
                  'iso_code'    => 'km',
                  'title'        => 'ខែ្មរ (Khmer)',
                  'active'      => '1'
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
            BundlesXMLFile::writeFile('langs',$moduleFields,"","Bundles/xml");
        }
        $listModule = BundlesXMLFile::getList('name','langs',"Bundles/xml");
        //var_dump($listModule);
    }
}    
?>