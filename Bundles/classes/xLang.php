<?php
namespace Bundles\classes;
use Bundles\classes\XMLFile as BundlesXMLFile;
class xLang
{
    public static function getById($id)
    {
        $whereClause = '[@id="'.$id.'"]';
        $query = '//langs/lang';
        $records = BundlesXMLFile::getXMLByWhere('langs',$query,$whereClause,'xml');
        $record = $records[0];
        return $record;
    }
    
    public static function getRecords()
    {
        if (!file_exists("xml/langs.xml"))
        {
            self::init();
        }
        return BundlesXMLFile::readFile('langs','','xml');
    }
    public static function init()
    {
        /* create module data */
        $moduleFields = array('id','id_lang','iso_code','title','active');
        $moduleData   = array(
           array(
                  'id_lang'     => '1',
                  'iso_code'    => 'en',
                  'title'       => 'English (Default)',
                  'active'      => '1'
           ),
           array(
                  'id_lang'     => '2',
                  'iso_code'    => 'fr',
                  'title'       => 'Française (France)',
                  'active'      => '1'
           ),
           array(
                  'id_lang'     => '3',
                  'iso_code'    => 'km',
                  'title'       => 'ខែ្មរ (Khmer)',
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
            BundlesXMLFile::writeFile('langs',$moduleFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('title','langs',"xml");
        //var_dump($listModule);
    }
}    
?>