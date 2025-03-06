<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\Db as Db;

class myArticle extends ObjectModel
{
    protected static $table      = 'article';
    protected static $identifier = 'id_article';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_article','id_lang','code','refference','img_name','img_group','img_qr','title','purchased_unit_price','sale_unit_price','sale_discount_percent','sale_discount_price','description','id_status', 'id_complex', 'id_building', 'id_company', 'id_categorie','id_bom','id_parent','site', 'model','mark','serial_number','installed_date','state','active','date_add','date_upd');
    protected static $fieldsSize       = array('id_article' => 10,'id_lang' =>10, 'code' => 25, 'refference' => 25, 'img_name' => 255, 'img_group' => 25,'img_qr' => 100, 'title' => 255,'purchased_unit_price' => 50, 'sale_unit_price' => 50, 'sale_discount_percent' => 50, 'sale_discount_price' => 50, 'description' => 255, 'id_status' =>10, 'id_complex' =>10, 'id_building' =>10, 'id_categorie'=>10, 'id_bom'=>10,'id_parent' => 10,'id_company' => 10,'site'=>125, 'model'=>125,'mark'=>125,'serial_number'=>125,'id_supplier'=>10,'state'=>10,'active'=>3);
    protected static $fieldsValidation = array('id_article' => 'isUnsignedIntAuto','id_lang' =>'isUnsignedTinyIntDefault0', 'code' => 'isVarCharDefaultNull', 'refference' => 'isVarCharDefaultNull',  'img_name' => 'isVarCharDefaultNull', 'img_group' => 'isVarCharDefaultNull', 'img_qr' => 'isVarCharDefaultNull', 'title' => 'isVarCharDefaultNull', 'purchased_unit_price' => 'isVarCharDefaultNull','sale_unit_price' => 'isVarCharDefaultNull','sale_discount_percent' => 'isVarCharDefaultNull','sale_discount_price' => 'isVarCharDefaultNull','description' => 'isVarCharDefaultNull','id_status' => 'isUnsignedTinyintDefault0','id_complex' => 'isUnsignedTinyintDefault0','id_building' =>'isUnsignedTinyintDefault0', 'id_company' => 'isUnsignedTinyintDefault0','id_categorie' =>'isUnsignedTinyintDefault0', 'id_bom' =>'isUnsignedTinyintDefault0', 'id_parent' => 'isUnsignedTinyintDefault0','site' => 'isVarCharDefaultNull','model' =>'isVarCharDefaultNull','mark' =>'isVarCharDefaultNull','serial_number' =>'isVarCharDefaultNull','id_supplier'=>'isVarCharDefaultNull','state'=>'isVarCharDefaultNull','installed_date'=>'isDate','active'=>'isUnsignedTinyIntDefault0','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_article');
    
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
    
    public static function getByAdjustmentStockGroup() 
    {
        $sql = "SELECT com.*, pl.currency_symbole, sum(pl.total_ttc) as sum_total_ttc, sum(pl.total_soumise) as sum_paid FROM rtt_company as com LEFT OUTER JOIN rtt_purchase_list as pl ON pl.id_account = com.id_company WHERE com.system_role = 4 GROUP BY id_company";
        //$sql = "SELECT art.*, sum(od.order_quantity) as order_quantity FROM rtt_article as art LEFT OUTER JOIN rtt_order_detail as od ON art.id_article = od.id_article WHERE op.date_add = ".date('Y-m-d H:i:s',time())." GROUP BY id_article";
        return Db::getInstance()->Execute($sql);
    }
    
    public static function getByStock() 
    {
        $sql = "SELECT equ.*, sum(pd.purchased_amount_cost) as sum_purchased_amount, sum(pd.sale_amount_cost) as sum_sale_amount, sum(pd.purchased_quantity_received) as sum_quantity_received, sum(con.quantity_consommable) as sum_quantity_destock  FROM rtt_article as equ LEFT OUTER JOIN rtt_purchase_detail as pd ON equ.id_article = pd.id_article LEFT OUTER JOIN rtt_consommable_detail as con ON equ.id_article = con.id_article WHERE equ.id_parent = 0 AND equ.id_categorie = "._RAW_MATERIAL_." GROUP BY id_article";
        //$sql = "SELECT art.*, sum(od.order_quantity) as order_quantity FROM rtt_article as art LEFT OUTER JOIN rtt_order_detail as od ON art.id_article = od.id_article WHERE op.date_add = ".date('Y-m-d H:i:s',time())." GROUP BY id_article";
        return Db::getInstance()->Execute($sql);
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
    
    public static function getLastRecord()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return isset(parent::getLast()[0])?parent::getLast()[0]:array();
    }
    
    public static function getFirstRecord()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return isset(parent::getFirst()[0])?parent::getFirst()[0]:array();;
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
    public static function updateByFields($id,$fields = array('sender'))
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