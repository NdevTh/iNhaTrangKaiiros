<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;

class myProject extends ObjectModel
{
    protected static $table      = 'project';
    protected static $identifier = 'id_project';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    /* title = prestation nature */
    protected static $fieldsRequired   = array('id_project','id_lang','id_company','id_article','id_supplier','code','title','tacit_renewal','amount_ht','billing_rhythm','termination_notice','description','id_status','date_start','date_end','active','date_add','date_upd');
    protected static $fieldsSize       = array('id_project' => 10, 'id_lang' =>10,'id_company' =>10,'id_article' =>10,'id_supplier' =>10,'code' => 10,  'title' => 255, 'amount_ht' => 100,'billing_rhythm' => 25,'tacit_renewal' =>3, 'termination_notice' => 255,'description' => 255, 'id_status' =>3,'active'=>3);
    protected static $fieldsValidation = array('id_project' => 'isUnsignedIntAuto', 'id_lang' =>'isUnsignedTinyIntDefault0','id_company' =>'isUnsignedTinyIntDefault0','id_article' =>'isUnsignedTinyIntDefault0','id_supplier' =>'isUnsignedTinyIntDefault0','code' => 'isVarCharDefaultNull', 'title' => 'isVarCharDefaultNull','tacit_renewal' =>'isUnsignedTinyIntDefault0','termination_notice' => 'isVarCharDefaultNull','billing_rhythm' => 'isVarCharDefaultNull','amount_ht' => 'isVarCharDefaultNull', 'description' => 'isVarCharDefaultNull','id_status' => 'isUnsignedTinyintDefault0','date_start'=>'isDate','date_end'=>'isDate','active'=>'isUnsignedTinyIntDefault0','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_project');
    
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
    
    public static function deleteById($id)
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = '`'. self::$identifier.'` = "' .$id .'"';;
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