<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;

class myMeterReading extends ObjectModel
{
    protected static $table      = 'meter_reading';
    protected static $identifier = 'id_meter_reading';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_meter_reading','id_asset','id_workorder','code','title','description','last_messured','actuel_messured','hours_used','date_add','date_upd');
    protected static $fieldsSize       = array('id_meter_reading' => 10, 'id_asset' => 10, 'id_workorder' => 10, 'code' => 10,  'title' => 255, 'description' => 255, 'last_messured' =>50,'actuel_messured' =>50,'hours_used' =>50);
    protected static $fieldsValidation = array('id_meter_reading' => 'isUnsignedIntAuto', 'id_asset' => 'isUnsignedTinyintDefault0', 'id_workorder' => 'isUnsignedTinyintDefault0','code' => 'isVarCharDefaultNull', 'title' => 'isVarCharDefaultNull','description' => 'isVarCharDefaultNull','last_messured' => 'isVarCharDefaultNull','actuel_messured'=>'isVarCharDefaultNull','hours_used'=>'isVarCharDefaultNull','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_meter_reading');
    
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
        parent::$prefix           = self::$prefix;
        
    }
    
    public static function init() 
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::initTable();
    }
    public static function deleteByWhere($whereClause)
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = $whereClause;
        parent::$prefix           = self::$prefix;
        
        return parent::delete();
    }
    
    public static function getByWhere($whereClause)
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = $whereClause;
        parent::$prefix           = self::$prefix;
        
        return parent::getRecordByWhereClause();
    }
    
    
    public static function getByField($field,$val)
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = '`'.$field.'` = "' .$val .'"';
        parent::$prefix           = self::$prefix;
        
        return parent::getRecordByWhereClause();
    }
    
    public static function getRecords()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::getRecords();
    }
    
    public static function getById($id)
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::getRecordById($id);
    }
    
    public static function getFields()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::getFields();
    }
    
    public static function save()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::register();
    }
    public static function updateByFields($id, $fields = array('sender'))
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = $fields;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = self::$identifier. ' = ' .$id;
        parent::$prefix           = self::$prefix;
        
        return parent::update();
    }
    
    public static function updateById($id)
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = self::$identifier. ' = ' .$id;
        parent::$prefix           = self::$prefix;
        
        return parent::update();
    }
}
?>