<?php
namespace classes;
use Bundles\classes\XMLFile as XMLFile;
class xTab
{
    public static function getChildTabs($id)
    {
        $whereClause = '[id_parent="'.$id.'"]';
        $query = '//tabs/tab';
        $records = XMLFile::getXMLByWhere('tabs',$query,$whereClause,'../Bundles/xml');
        return $records;
    }
    
    public static function getParentTabsBySystemRole($id)
    {
        $whereClause = '[id_parent="0"][system_role="'.$id.'"]';
        $query = '//tabs/tab';
        $records = XMLFile::getXMLByWhere('tabs',$query,$whereClause,'../Bundles/xml');
        return $records;
    }
    
    public static function getParentTabs()
    {
        $whereClause = '[id_parent="0"]';
        $query = '//tabs/tab';
        $records = XMLFile::getXMLByWhere('tabs',$query,$whereClause,'../Bundles/xml');
        return $records;
    }
}    
?>