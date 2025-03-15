<?php
namespace Bundles\classes;
use Bundles\classes\Db as Db;
class MySQL extends Db
{
    public static function version()
    {
        return parent::$_link -> server_info;
    }
    public static function connect()
    {
        if (!defined('_RTT_DEBUG_SQL_')){
            define('_RTT_DEBUG_SQL_', false);
        }
        parent::$_link = @mysqli_connect(parent::$_server, parent::$_user, parent::$_password, parent::$_database);
        //parent::$_link->set_charset("utf8");
        //parent::$_link->set_charset("utf8");
        if (mysqli_connect_errno()) {
            //die(Tools::displayError('The database selection cannot be made.'));
            return false;
        } else {
            //die( Tools::displayError('Link to database cannot be established.'));
        }
        //parent::$_link->query("SET GLOBAL sql_mode='', SESSION sql_mode=''");

		       return parent::$_link;
    }
    
    public static function disconnect()
    {
        if ($this->_link){
            @mysqli_close($this->_link);
        }
        $this->_link = false;
    }
    
    public static function Execute($query)
    {
        self::$_result = false;
        if (self::$_link)
        {
            self::$_result = mysqli_query(self::$_link, $query);
            //var_dump($this->_result);
            if (_RTT_DEBUG_SQL_)
            {
                self::displayMySQLError($query);
            }
            //return self::$_result;
            if (!is_bool(self::$_result)) {
                //You have to run mysqli_fetch_array to get real data as array
                return mysqli_fetch_all(self::$_result, MYSQLI_ASSOC);
            }
        }
        return false;
    }
    
    public static function insertId()
    {
        return mysqli_insert_id(self::$_link);
    }
    
    public static function ExecuteS($query, $array = true)
    {
        parent::$_result = false;
        if (parent::$_link && parent::$_result = mysqli_query(parent::$_link, $query))
        {
            //var_dump($query);
            if (_RTT_DEBUG_SQL_)
            {
                self::displayMySQLError($query);
            }
            if (!$array)
            {
                return parent::$_result;
            }
            $resultArray = array();
            //var_dump($this->_result);
            while ($row = mysqli_fetch_assoc(parent::$_result)){
                $resultArray[] = $row;
            }
            return $resultArray;
        }
        if (_RTT_DEBUG_SQL_)
        {
            self::$displayMySQLError($query);
        }
        return false;
    }
    
    public static function displayMySQLError($query = false)
    {
        if (_RTT_DEBUG_SQL_ AND mysqli_errno(self::$_link) )
        {
            if ($query) die(Tools::displayError( mysqli_error(self::$_link) .'<br /><br /><pre>'.$query.'</pre>'));
            die(Tools::displayError(mysqli_error(self::$_link)) );
        }
    }
    
    public static function getTables()
    {
        $tableList = array();
        $res = @mysqli_query(self::$_link,"SHOW TABLES");
        while($cRow = @mysqli_fetch_array($res))
        {
            $tableList[] = $cRow[0];
        }
        return $tableList;
    }
    
}
?>