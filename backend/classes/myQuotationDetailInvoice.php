<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;

class myQuotationDetailInvoice extends ObjectModel
{
    protected static $table      = 'quotation_detail_invoice';
    protected static $identifier = 'id_quotation_detail_invoice';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_quotation_detail_invoice','id_quotation_order','id_purchase_order','id_employee','id_customer','id_equipment','id_bom','code','title','description','refference','quantity_estimated','unit_price_estimated','amount_cost_estimated','discount_percent_estimated','discount_amount_estimated','quantity_purchase','unit_price_purchase','amount_cost_purchase','quantity_received','pay_service','purchasing_service','amount','unit','pay_account','discount_percent','discount_amount','type','account_quantity','account_amount','account_amount_total','received_min','received_max','quantity_completed','action_received','tax_categorie','id_tax','label','purchase_account','date_preview','complet','cancel','close','invoice_quantity','invoice_amount','invoice_non_quantity','invoice_non_amount','contract_type','contract_ref','invoice_on','date_add','date_upd');
    protected static $fieldsSize       = array('id_quotation_detail_invoice' => 10,'id_quotation_order'=>10,'id_purchase_order', 'id_employee'=>10,'id_customer'=>10, 'code' => 10,'id_equipment'=>10,'id_bom'=>10,'title' => 255, 'description' => 255, 'refference' =>50, 'quantity_estimated' =>50,'unit_price_estimated'=>10,'amount_cost_estimated'=>50, 'discount_percent_estimated'=>10,'discount_amount_estimated'=>50,'quantity_purchase' =>50,'unit_price_purchase'=>10,'amount_cost_purchase'=>50,'quantity_received'=>50,'pay_service'=>20,'purchasing_service'=>50,'amount'=>50,'unit'=>50,'pay_account'=>50,'discount_percent'=>10,'discount_amount'=>50,'type'=>10,'account_quantity'=>50,'account_amount'=>50, 'account_amount_total'=>150,'received_min'=>20,'received_max'=>20,'quantity_completed'=>50,'action_received'=>3,'tax_categorie'=>10,'id_tax' => 10,'label'=>255,'purchase_account'=>50,'complet'=>3,'cancel'=>3,'close'=>3,'invoice_quantity'=>50,'invoice_amount'=>50,'invoice_non_quantity'=>50,'invoice_non_amount'=>50,'contract_type'=>10,'contract_ref'=>50,'invoice_on'=>50);
    protected static $fieldsValidation = array('id_quotation_detail_invoice' => 'isUnsignedIntAuto', 'id_quotation_order' => 'isUnsignedTinyintDefault0','id_purchase_order' => 'isUnsignedTinyintDefault0','id_employee' => 'isUnsignedTinyintDefault0','id_customer'  => 'isUnsignedTinyintDefault0','id_equipment' => 'isUnsignedTinyintDefault0','id_bom'  => 'isUnsignedTinyintDefault0','code' => 'isVarCharDefaultNull', 'title' => 'isVarCharDefaultNull','description' => 'isVarCharDefaultNull','refference' => 'isVarCharDefaultNull','quantity_estimated' => 'isVarCharDefaultNull','unit_price_estimated' => 'isVarCharDefaultNull','amount_cost_estimated'  => 'isVarCharDefaultNull','discount_percent_estimated'  => 'isVarCharDefaultNull','discount_amount_estimated'  => 'isVarCharDefaultNull','quantity_purchase' => 'isVarCharDefaultNull','unit_price_purchase' => 'isVarCharDefaultNull','amount_cost_purchase'  => 'isVarCharDefaultNull','quantity_received'  => 'isVarCharDefaultNull','pay_service'  => 'isVarCharDefaultNull','purchasing_service'  => 'isVarCharDefaultNull','amount' => 'isVarCharDefaultNull','unit'  => 'isVarCharDefaultNull','pay_account'  => 'isVarCharDefaultNull','discount_percent'  => 'isVarCharDefaultNull','discount_amount'  => 'isVarCharDefaultNull','type'  => 'isUnsignedTinyintDefault0','account_quantity'  => 'isVarCharDefaultNull','account_amount'  => 'isVarCharDefaultNull','account_amount_total'  => 'isVarCharDefaultNull','received_min'  => 'isVarCharDefaultNull','received_max'  => 'isVarCharDefaultNull','quantity_completed'  => 'isVarCharDefaultNull','action_received'  => 'isVarCharDefaultNull','tax_categorie'  => 'isVarCharDefaultNull','id_equipment' => 'isUnsignedTinyintDefault0','label'  => 'isVarCharDefaultNull','purchase_account'  => 'isVarCharDefaultNull' ,'date_preview'=>'isDate','complet'  => 'isUnsignedTinyintDefault0','cancel'  => 'isUnsignedTinyintDefault0','close'  => 'isUnsignedTinyintDefault0','invoice_quantity'  => 'isVarCharDefaultNull','invoice_amount'  => 'isVarCharDefaultNull','invoice_non_quantity'  => 'isVarCharDefaultNull','invoice_non_amount'  => 'isVarCharDefaultNull','contract_type'  => 'isVarCharDefaultNull' ,'contract_ref'  => 'isVarCharDefaultNull','invoice_on'  => 'isVarCharDefaultNull','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_quotation_detail_invoice');
    
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