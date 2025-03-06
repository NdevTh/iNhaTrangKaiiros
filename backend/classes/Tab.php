<?php
namespace classes;
class Tab extends ObjectModel
{
    protected static $table      = 'tab';
    protected static $identifier = 'id_tab';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_tab','id_parent','class_name','module','position');
    protected static $fieldsSize       = array('class_name' => 64, 'module' =>64);
    protected static $fieldsValidation = array('id_parent' => 'isInt', 'position' => 'isUnsignedInt', 'module' => 'isTabName','id_tab' =>'Primary Key','class_name' => 'Key');
    
    /** @var string Displayed name*/
    public		$name;
    /** @var string Class and file name*/
    public		$class_name;
    public		$module;
    /** @var integer parent ID */
    public		$id_parent;
    /** @var integer position */
    public		$position;
    
    public function __construct() 
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
    }
    public static function getRecords()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        return parent::getRecords();
    }
    
    public static function getById($id)
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        return parent::getRecordById($id);
    }
    
    public static function getFields()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        return parent::getFields();
    }
}
?>