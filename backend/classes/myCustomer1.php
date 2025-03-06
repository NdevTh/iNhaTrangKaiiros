<?php
namespace classes;
//use Bundles\classes\ObjectModel as ObjectModel;
use Bundles\classes\ObjectModel as ObjectModel;

class myCustomer1 extends ObjectModel
{
    protected static $table      = 'customer';
    protected static $identifier = 'id_customer';
    public static $prefix        = 'rtt_';
    
    protected static $fieldsDefinition = array('class_name','position');
    protected static $fieldsRequired   = array('id_customer','id_gender','secure_key','email','passwd','last_passwd_gen','birthday','lastname','newsletter','ip_registration_newsletter','newsletter_date_add','optin','firstname','dni','active','deleted','date_add','date_upd');
    protected static $fieldsSize       = array('id_customer' => 10, 'id_gender' =>10,'secure_key'=>32,'email'=>128,'passwd'=>32,'lastname'=>32,'newsletter'=>1,'ip_registration_newsletter'=>15,'optin'=>1,'firstname'=>32,'dni'=>16);
    protected static $fieldsValidation = array('id_customer' => 'isUnsignedIntAuto', 'id_gender' => 'isUnsignedInt','secure_key'=>'isVarCharNotNullDefault-1','email'=>'isVarCharNotNull','passwd'=>'isVarCharNotNull','last_passwd_gen'=>'isTimestampNotNullDefault','birthday'=>'isDateDefault','lastname'=>'isVarCharNotNull','newsletter'=>'isUnsignedTinyintDefault0','ip_registration_newsletter'=>'isVarCharDefaultNull','newsletter_date_add'=>'isDateTimeDefault','optin'=>'isUnsignedTinyintDefault0','firstname'=>'isVarCharNotNull','dni'=>'isVarCharDefaultNull','active'=>'isUnsignedTinyIntDefault0','deleted'=>'isUnsignedTinyIntDefault0','date_add'=>'isDateTimeNotNull','date_upd'=>'isDateTimeNotNull');
    protected static $keysValidation   = array('Primary Key' =>'id_customer','Unique Key' =>'email','customer_login'=>array('email','passwd'),'id_customer_passwd'=>array('id_customer','passwd'));
    
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
    
    public static function getRecords()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
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
        parent::$whereClause      = self::$identifier. ' = ' .$id;
        parent::$prefix           = self::$prefix;
        
        return parent::getRecordById($id);
    }
    
    public static function getFields()
    {
        //ObjectModel::$fieldsDefinition = Tab::$fieldsDefinition;
        return parent::getFields();
    }
}
?>