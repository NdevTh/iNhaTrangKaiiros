<?php
namespace Bundles\classes;
/* 
 * prepare the SQL string 
 */
abstract class ObjectModel
{
    /** @var integer Object id */
    public static $id;
    public static $prefix;
    public static $whereClause = '';
    public static $groupby = '';
    public static $sumfield = 'quantity';
    public static $sortClause = 'ORDER BY id ASC';
    public static $indCol = 0;
    public static $id_list;
    /** @var string SQL Table name */
    protected static $table      = NULL;
    /** @var string SQL Table identifier */
    protected static $identifier = NULL;
    /** @var array tables */
    protected static $tables     = array();
    
    /** @var array Required fields for admin panel forms */
    protected static $fieldsRequired   = array();
    /** @var array Maximum fields size for admin panel forms */
    protected static $fieldsSize       = array();
    /** @var array Fields validity functions for admin panel forms */
    protected static $keysValidate     = array();
    /** @var array Fields validity functions for admin panel forms */
    protected static $fieldsValidate   = array();
    /** @var array table difintion for create table */
    protected static $fieldsDefinition = array();
    
    /** @var array Multilingual required fields for admin panel forms */
    protected $fieldsRequiredLang = array();
    /** @var array Multilingual maximum fields size for admin panel forms */
    protected $fieldsSizeLang     = array();
    /** @var array Multilingual fields validity functions for admin panel forms */
    protected $fieldsValidateLang = array();
    
    public static function registergroup(){
        $id  = 0;
        $sql = self::postProcess('groupinsert');
        //echo $sql;
        //var_dump(self::getFieldType('id_tab')->fetch_field()->type);
        Db::getInstance()->Execute($sql);
        $id  = Db::getInstance()->insertId();
        return $id;
    }
    
    public static function register(){
        $id  = 0;
        $sql = self::postProcess('insert');
        //echo $sql;
        //var_dump(self::getFieldType('id_tab')->fetch_field()->type);
        Db::getInstance()->Execute($sql);
        $id  = Db::getInstance()->insertId();
        return $id;
    }
    
    public static function postProcess($type)
    {
        if (strtolower($type) == 'groupinsert'){
            return self::arrInsert();
        }
        if (strtolower($type) == 'insert'){
            $ret    = 'INSERT INTO';
            $ret   .= ' `'.self::$prefix. self::$table.'`(';
            $fields = self::$fieldsRequired;
            $strFields = '';
            //var_dump($fields);
            foreach($fields as $field)
            {
                if (self::$identifier != $field)
                {
                    $strFields .= '`'.$field.'`,';
                }
            }
            $ret .= substr($strFields,0,-1).') VALUES(';
            $strValues = '';
            foreach($fields as $field)
            {
                /*
                if (strtolower(self::getFieldType($field) ) == 'int')
                {
                    //echo strtolower(self::getFieldType($field));
                }
                */
                if (self::$identifier != $field)
                {
                    $strValues .= '"'.addslashes($_POST[$field]).'",';
                }
            }
            $ret .= substr($strValues,0,-1).');';
            
            return $ret;
        }
        if (strtolower($type) == 'update'){
            $ret    = 'UPDATE';
            $ret   .= ' `'.self::$prefix. self::$table.'` SET ';
            $fields = self::$fieldsRequired;
            $strSet = '';
            //var_dump($fields);
            foreach($fields as $field)
            {
                if (self::$identifier != $field)
                {
                    $strSet .= '`'.$field.'` = ' . '"'.$_POST[$field].'",';
                }
            }
            $ret.= substr($strSet,0,-1);
            $ret.= ' WHERE ' . self::$whereClause;
            return $ret;
        }
        if (strtolower($type) == 'delete'){
            $ret.= 'DELETE FROM ';
            $ret.= '`'.self::$prefix. self::$table.'`';
            $ret.= ' WHERE ' . self::$whereClause;
            return $ret;
        }
    }
    
    public static function arrInsert()
    {
        $ret    = 'INSERT INTO';
        $ret   .= ' `'.self::$prefix. self::$table.'`(';
        $fields = self::$fieldsRequired;
        $strFields = '';
        //var_dump($fields);
        foreach($fields as $field)
        {
            if (self::$identifier != $field)
            {
                $strFields .= '`'.$field.'`,';
            }
        }
        $ret .= substr($strFields,0,-1).') VALUES';
        $strValues = '';
        $table = array();
        foreach($fields as $field)
        {
            //echo 'Field: ' .$field.' value:'.$_POST[$field].'<br/>';
            array_push($table,$_POST['arr'.$field]);
        }
        //var_dump($table);
        $newTable = array();
        //$nbCol = count($table[0]);
        $nbCol = count($fields);
        $nbRow = count($table[self::$indCol]);
        /*
        //echo 'Nb Row: ' .$nbRow.' Nb Col: '.$nbCol.' description: '.$_POST['description'].'<br/>';
        for ($row = 0; $row < $nbRow; $row++)
        {
            //echo 'Row '.$row.' : ';
            $strValues.= '(';
            for($col=1; $col< $nbCol; $col++)
            {
                $strValues.= '"'.$table[$col][$row].'",';
            }
            $strValues =  substr($strValues,0,-1).'),';
            //echo '<br/>';
        }
        $ret.= substr($strValues,0,-1);
        */
        $str = '';
        for ($row = 0; $row < $nbRow; $row++)
        {
            $str.='(';
            foreach($fields as $field)
            {
                if ($field == self::$id_list ){
                    $str.= addslashes($_POST[self::$id_list]).',';
                }else if (self::$identifier != $field) {
                    $str.= '"'.addslashes($_POST['arr'.$field][$row]).'",';
                }
            }
            $str = substr($str,0,-1). '),';
        }
        $ret.= substr($str,0,-1);
        return $ret;
    }
    public static function getFieldType($col_name){
        $sql = 'SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = "'.self::$table.'" AND COLUMN_NAME = "'.$col_name.'"';
        if (!is_bool($res = Db::getInstance()->Execute($sql))){
            return $res->fetch_field()->type;
        }else {
            return self::$fieldsValidation[$col_name];
        }
    }
    public static function update(){
        $sql = self::postProcess('update');
        //return $sql;
        $id = Db::getInstance()->Execute($sql);
        return $id;
    }
    
    public static function delete(){
        $sql = self::postProcess('delete');
        //echo $sql;
        $id = Db::getInstance()->Execute($sql);
        return $id;
    }
    
    public static function getFields()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SHOW COLUMNS FROM `' .self::$prefix. self::$table .'`';
            $res = Db::getInstance()->Execute($sql);
        
            while($row = $res->fetch_assoc())
            {
                $ret[] = $row['Field'];
            }
            return $ret;
        } else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getRecordById($id)
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'` WHERE ' . self::$identifier .'='.$id;
            //echo $sql;
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getRecordByWhereClause()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'` WHERE ' . self::$whereClause;
            //echo $sql;
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getSumByGroup()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT *, sum('.self::$sumfield.') FROM `' .self::$prefix. self::$table .'` GROUP BY ' . self::$groupby;
            //echo $sql;
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getDistinctRecordByWhereClause()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT t1.* FROM `' .self::$prefix. self::$table .'` t1 INNER JOIN (SELECT id_supplier, MAX(date_upd) AS max_order FROM `' .self::$prefix. self::$table .'` WHERE ' . self::$whereClause .' GROUP BY ' . self::$groupby .') t2 ON t1.' . self::$groupby .' = t2.' . self::$groupby .' AND t1.date_upd = t2.max_order';
            //$sql = 'SELECT *, min(date_upd) FROM `' .self::$prefix. self::$table .'` WHERE ' . self::$whereClause .' GROUP BY `id_supplier`';
            //echo $sql;
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getRecordByJointClause()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'` ' . self::$whereClause;
            echo $sql;
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getDistinctRecords()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT DISTINCT * FROM `' .self::$prefix. self::$table .'`';
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getRecords()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'`';
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getFirst()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'` LIMIT 1 ORDER BY `' . self::$identifier .'` ASC';
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function getLast()
    {
        $ret = array();
        if (self::isTableExist()){
            getRecords:
            $sql = 'SELECT * FROM `' .self::$prefix. self::$table .'` ORDER BY `' . self::$identifier .'` DESC';
            $result = Db::getInstance()->ExecuteS($sql);
            return $result;
        }else{
            self::initTable();
            goto getRecords;
        }
        return $ret;
    }
    
    public static function executeS($sql)
    {
        //$sql = 'SELECT * FROM `' .self::$prefix. self::$table .'`';
        $result = Db::getInstance()->Execute($sql);
        return $result;
    }
    
    public static function isTableExist()
    {
        $ret = false;
        
        return $ret;
    }
    
    public static function initTable()
    {
        $sql    = '';
        $fields = '';
        $key    = '';
        //Db::getInstance();
        if (self::$fieldsRequired){
            //var_dump(self::$fieldsRequired);
            foreach( self::$fieldsRequired as $field )
            {
                $attr    = '';
                $chkType = '';
                if (self::$fieldsValidate){
                    $chkType = isset(self::$fieldsValidate[$field])?self::$fieldsValidate[$field]:(is_array($field)?'key':'');
                    if (strtolower($chkType) == 'isint')
                    {
                        $attr = 'INT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').')';
                    } 
                    elseif (strtolower($chkType) == 'isunsignedint' )
                    {
                        $attr = 'INT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') unsigned NOT NULL';
                    }
                    elseif (strtolower($chkType) == 'isunsignedvarchar' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') unsigned NOT NULL';
                    }
                    elseif (strtolower($chkType) == 'isvarcharnotnull' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') NOT NULL';
                    }
                    elseif (strtolower($chkType) == 'istimestampnotnulldefault' )
                    {
                        $attr = 'timestamp NOT NULL default CURRENT_TIMESTAMP';
                    }
                    elseif (strtolower($chkType) == 'isdatedefaultnull' )
                    {
                        $attr = 'date default NULL';
                    }
                    elseif (strtolower($chkType) == 'isdate' )
                    {
                        $attr = 'date';
                    }
                    elseif (strtolower($chkType) == 'isdatetimedefault' )
                    {
                        $attr = 'datetime default NULL';
                    }
                    elseif (strtolower($chkType) == 'isdatetime' )
                    {
                        $attr = 'datetime';
                    }
                    elseif (strtolower($chkType) == 'isdatetimenotnull' )
                    {
                        $attr = 'datetime NOT NULL';
                    }
                    elseif (strtolower($chkType) == 'isvarcharnotnulldefault-1' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') NOT NULL default \'-1\'';
                    }
                    elseif (strtolower($chkType) == 'isvarchardefaultnotnull' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') NOT NULL default \'-1\'';
                    }
                    elseif (strtolower($chkType) == 'isvarchardefaultnull' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') default NULL';
                    }
                    elseif (strtolower($chkType) == 'isvarcharnulldefault0' )
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') NULL default \'0\'';
                    }
                    elseif (strtolower($chkType) == 'isunsignedintauto' )
                    {
                        $attr = 'INT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') unsigned NOT NULL auto_increment';
                    }
                    elseif (strtolower($chkType) == 'isunsignedtinyintdefault0' )
                    {
                        $attr = 'TINYINT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'1').') unsigned NOT NULL default \'0\'';
                    }
                    elseif (strtolower($chkType) == 'isunsignedtinyint' )
                    {
                        $attr = 'TINYINT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') unsigned NOT NULL';
                    }
                    else if (!empty($chkType) && $field != self::$identifier)
                    {
                        $attr = 'VARCHAR('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').')';
                    }
                        elseif (!empty($chkType) && $field != self::$identifier)
                    {
                        $attr = 'INT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').')';
                    }else{
                        $attr = 'VARCHAR'.(isset(self::$fieldsSize[$field])?'('.self::$fieldsSize[$field].')':'');
                    }
                    if ($field == self::$identifier )
                    {
                        $attr = 'INT('.(isset(self::$fieldsSize[$field])?self::$fieldsSize[$field]:'10').') unsigned NOT NULL auto_increment';
                    }
                    
                    
                    $fields .= ' `'. $field. '` '.$attr. ',';
                }
            }
        }
        /* ADD KEY TYPE */
        if (self::$keysValidate)
        {
            foreach( self::$keysValidate as $k => $values )
            {
                if (is_array($values))
                {
                    $key .= 'KEY `'.$k.'`';
                    $vKey = ' (';
                    foreach($values as $vK => $vals)
                    {
                        //foreach($vals as $val)
                        //{
                            $vKey .= '`'.$vals.'`,';
                        //}
                    }
                    //$vKey = substr($vKey,0,-1);
                    $vKey =  substr($vKey,0,-1) .'),';
                    $key .= substr($vKey,0,-1).',';
                } else {
                    $key .= strtoupper($k).' `'.self::$table.'_'.$values.'` (`'.$values.'`)'.',';
                }
            }
        }
        $fields .= ' '.$key;
        $sql = 'CREATE TABLE IF NOT EXISTS`'.self::$prefix .self::$table.'` ('. substr($fields,0,-1).") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        return self::executeS($sql);
        //return 'SQL: '.$sql.'<br/>identifer: '.self::$identifier;
        //echo substr("Hello world",0,-1)."<br>";

    }
    
}
?>