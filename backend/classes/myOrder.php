<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\Db as Db;

class myOrder extends ObjectModel
{
    protected static $table      = 'order_list';
    protected static $identifier = 'id_order_list';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_order_list','id_customer','id_type','id_lang','id_shipping_mode','id_place','code','title','description','id_payment','created_by','received_by','currency','currency_symbole','total_detail','total_remise','total_exonere','total_soumise','total_tva','total_ttc', 'active','date_add','date_upd');
    protected static $fieldsSize       = array('id_order_list' => 10, 'id_customer' =>10,'id_type' =>10,'id_lang' =>10,'id_shipping_mode' =>10,'id_place' =>10,'code' => 10,'title' => 200, 'description' => 255,'id_payment' =>10,'created_by' =>10,'received_by' =>10,'currency'=>50,'currency_symbole' =>3,'total_detail' => 100,'total_remise' => 100,'total_exonere' => 100,'total_soumise' => 100,'total_tva' => 100,'total_ttc' => 100,'active'=>3);
    protected static $fieldsValidation = array('id_order_list' => 'isUnsignedIntAuto', 'id_customer' =>'isUnsignedTinyIntDefault0', 'id_type' =>'isUnsignedTinyIntDefault0', 'id_lang' =>'isUnsignedTinyIntDefault0','id_shipping_mode' =>'isUnsignedTinyIntDefault0','id_place' =>'isUnsignedTinyIntDefault0', 'code' => 'isVarCharDefaultNull','title' => 'isVarCharDefaultNull','description' => 'isVarCharDefaultNull','id_payment' =>'isUnsignedTinyIntDefault0','created_by' =>'isUnsignedTinyIntDefault0','received_by' =>'isUnsignedTinyIntDefault0','currency' => 'isVarCharDefaultNull', 'currency_symbole' => 'isVarCharDefaultNull', 'total_detail' => 'isVarCharDefaultNull','total_remise' => 'isVarCharDefaultNull','total_soumise' => 'isVarCharDefaultNull','total_tva' => 'isVarCharDefaultNull','total_ttc' => 'isVarCharDefaultNull','active'=>'isUnsignedTinyIntDefault0','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_order_list');
    
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
    
    public static function getByDate($currentDate) 
    {
        $sql = "SELECT * FROM rtt_order_list WHERE DATE(date_add) = '" .$currentDate ."'";
        //$sql = "SELECT art.*, sum(od.order_quantity) as order_quantity FROM rtt_article as art LEFT OUTER JOIN rtt_order_detail as od ON art.id_article = od.id_article WHERE op.date_add = ".date('Y-m-d H:i:s',time())." GROUP BY id_article";
        return Db::getInstance()->Execute($sql);
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