<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;

class myPaymentDetail extends ObjectModel
{
    protected static $table      = 'payment_detail';
    protected static $identifier = 'id_payment_detail';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_payment_detail','id_order_list','id_purchase_order','id_supplier','id_customer','id_payment_mode','id_bom','code','title','description','refference','order_quantity','sale_unit_price','sale_amount_cost','sale_discount_percent','sale_discount_amount','purchased_quantity','purchased_unit_price','purchased_amount_cost','purchased_quantity_received','pay_service','purchased_service','purchased_amount','unit','pay_account','discount_percent','discount_amount','id_type','account_quantity','account_amount','received_min','received_max','quantity_completed','action_received','tax_categorie','id_tax','label','purchase_account','date_preview','complet','cancel','close','invoice_quantity','invoice_amount','invoice_non_quantity','invoice_non_amount','contract_type','contract_ref','invoice_on','date_add','date_upd');
    protected static $fieldsSize       = array('id_payment_detail' => 10,'id_order_list'=>10,'id_purchase_order', 'id_supplier'=>10,'id_customer'=>10, 'code' => 10,'id_payment_mode'=>10,'id_bom'=>10,'title' => 255, 'description' => 255, 'refference' =>50, 'order_quantity' =>50,'sale_unit_price'=>10,'sale_amount_cost'=>50, 'sale_discount_percent'=>10,'sale_discount_amount'=>50,'purchased_quantity' =>50,'purchased_unit_price'=>10,'purchased_amount_cost'=>50,'purchased_quantity_received'=>50,'pay_service'=>20,'purchased_service'=>50,'purchased_amount'=>50,'unit'=>50,'pay_account'=>50,'discount_percent'=>10,'discount_amount'=>50,'id_type'=>10,'account_quantity'=>50,'account_amount'=>50,'received_min'=>20,'received_max'=>20,'quantity_completed'=>50,'action_received'=>3,'tax_categorie'=>10,'id_tax' => 10,'label'=>255,'purchase_account'=>50,'complet'=>3,'cancel'=>3,'close'=>3,'invoice_quantity'=>50,'invoice_amount'=>50,'invoice_non_quantity'=>50,'invoice_non_amount'=>50,'contract_type'=>10,'contract_ref'=>50,'invoice_on'=>50);
    protected static $fieldsValidation = array('id_payment_detail' => 'isUnsignedIntAuto', 'id_order_list' => 'isUnsignedTinyintDefault0','id_purchase_order' => 'isUnsignedTinyintDefault0','id_supplier' => 'isUnsignedTinyintDefault0','id_customer'  => 'isUnsignedTinyintDefault0','id_payment_mode' => 'isUnsignedTinyintDefault0','id_bom'  => 'isUnsignedTinyintDefault0','code' => 'isVarCharDefaultNull', 'title' => 'isVarCharDefaultNull','description' => 'isVarCharDefaultNull','refference' => 'isVarCharDefaultNull','order_quantity' => 'isVarCharDefaultNull','sale_unit_price' => 'isVarCharDefaultNull','sale_amount_cost'  => 'isVarCharDefaultNull','sale_discount_percent'  => 'isVarCharDefaultNull','sale_discount_amount'  => 'isVarCharDefaultNull','purchased_quantity' => 'isVarCharDefaultNull','purchased_unit_price' => 'isVarCharDefaultNull','purchased_amount_cost'  => 'isVarCharDefaultNull','purchased_quantity_received'  => 'isVarCharDefaultNull','pay_service'  => 'isVarCharDefaultNull','purchased_service'  => 'isVarCharDefaultNull','purchased_amount' => 'isVarCharDefaultNull','unit'  => 'isVarCharDefaultNull','pay_account'  => 'isVarCharDefaultNull','discount_percent'  => 'isVarCharDefaultNull','discount_amount'  => 'isVarCharDefaultNull','id_type'  => 'isUnsignedTinyintDefault0','account_quantity'  => 'isVarCharDefaultNull','account_amount'  => 'isVarCharDefaultNull','received_min'  => 'isVarCharDefaultNull','received_max'  => 'isVarCharDefaultNull','quantity_completed'  => 'isVarCharDefaultNull','action_received'  => 'isVarCharDefaultNull','tax_categorie'  => 'isVarCharDefaultNull','id_equipment' => 'isUnsignedTinyintDefault0','label'  => 'isVarCharDefaultNull','purchase_account'  => 'isVarCharDefaultNull' ,'date_preview'=>'isDate','complet'  => 'isUnsignedTinyintDefault0','cancel'  => 'isUnsignedTinyintDefault0','close'  => 'isUnsignedTinyintDefault0','invoice_quantity'  => 'isVarCharDefaultNull','invoice_amount'  => 'isVarCharDefaultNull','invoice_non_quantity'  => 'isVarCharDefaultNull','invoice_non_amount'  => 'isVarCharDefaultNull','contract_type'  => 'isVarCharDefaultNull' ,'contract_ref'  => 'isVarCharDefaultNull','invoice_on'  => 'isVarCharDefaultNull','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_payment_detail');
    
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
    
    public static function getDistinctByWhere($whereClause,$groupby)
    {
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$whereClause      = $whereClause;
        parent::$groupby          = $groupby;
        parent::$prefix           = self::$prefix;
        
        return parent::getDistinctRecordByWhereClause();
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
    
    public static function getDistRecords()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        parent::$table            = self::$table;
        parent::$identifier       = self::$identifier;
        parent::$fieldsRequired   = self::$fieldsRequired;
        parent::$keysValidate     = self::$keysValidation;
        parent::$fieldsValidate   = self::$fieldsValidation;
        parent::$fieldsSize       = self::$fieldsSize;
        parent::$prefix           = self::$prefix;
        
        return parent::getDistinctRecords();
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