<?php
namespace classes;
use classes\xObjectModel as xObjectModel;
class xModule extends xObjectModel
{
    public static function getWhereTagName($whereClause='',$query='//modules/module')
    {
        $fieldsRequired = array();
        $records = parent::getXMLByWhere('modules',$query,$whereClause);
        return $records;
    }
}    
?>