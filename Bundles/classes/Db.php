<?php
namespace Bundles\classes;
use Bundles\classes\MySQL as MySQL;
if (file_exists('../config/settings.inc.php')){
    //echo 'File exist';
    include_once('../config/settings.inc.php');
}
/*
$currentDir = 'Bundles/';
echo basename(dirname(__FILE__));
if (basename(dirname(__FILE__)) == 'classes')
{
    $currentDir = '';
}
include_once($current.'classes/MySQL.php');
*/
/*
 * connect to MySQL direct to CRUD
 */
abstract class Db{
    	/** @var string Server (eg. localhost) */
    protected static $_server;
    /** @var string Database user (eg. root) */
    protected static $_user;
    /** @var string Database password (eg. can be empty !) */
    protected static $_password;
    /** @var string Database type (MySQL, PgSQL) */
    protected static $_type;
    /** @var string Database name */
    protected static $_database;
    /** @var mixed Ressource link */
    protected static $_link;
    /** @var mixed SQL cached result */
    protected static $_result;
    /** @var mixed ? */
    protected static $_db;
    /** @var mixed Object instance for singleton */
    private static $_instance;
    
    /**
     * Get Db object instance (Singleton)
     *
     * @return object Db instance
     */
    public static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new MySQL();
        }
        return self::$_instance;
    }
    
    public function __destruct()
    {
        $this->disconnect();
    }
    
    /**
     * Build a Db object
     */
    public function __construct()
    {
        self::$_server = _DB_SERVER_;
        self::$_user = _DB_USER_;
        self::$_password = _DB_PASSWD_;
        self::$_type = _DB_TYPE_;
        self::$_database = _DB_NAME_;
        static::connect();
    }
    
    public static function autoExecute($table, $values, $type, $where = false, $limit = false)
    {
        
    }
    public static function initTable($sql){
        
    }
    /*********************************************************
     * ABSTRACT METHODS
     *********************************************************/
    
    /**
     * Open a connection
     */
    abstract public static function connect();
    
    /**
     * Close a connection
     */
    abstract public static function disconnect();
    
    /**
     * Fetches a row from a result set
     */
    abstract public static function Execute ($query);
    
    /**
     * Fetches an array containing all of the rows from a result set
     */
    abstract public static function ExecuteS($query, $array = true);
	
}
?>