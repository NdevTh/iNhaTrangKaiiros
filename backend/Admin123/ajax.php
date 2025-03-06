<?php
include('config/config.inc.php');
include('../config/settings.inc.php');
use classes\Tools as Tools;
use classes\myEquipment as myEquipment;

$response = array();
$response['table'] = $_POST['table'];
$response['table_group'] = $_POST['tablegroup'];
$response['table_sub']   = $_POST['tablesub'];

$currentDate = date('Y-m-d H:i:s',time());
$response['result'] = array();
$response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
$response['message'] = 'Table: '.$response['table'];

/*
//try {
    
    if ($response['table'] == 'equipment' && !empty($_POST['title']))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $_POST['date_add'] = $currentDate;
        $_POST['date_upd'] = $currentDate;
        $id = myEquipment::save();
        $_POST['result'] = json_encode(myEquipment::getRecords());
    }
/*
} catch(Error $e) {
    $response['message'] = $e->getMessage();
}

//$response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
//var_dump(myEquipment::getRecords());
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
exit();
*/
/*
define("_DB_USER_", "root");
define("_DB_SERVER_", "0.0.0.0");
define("_DB_PASSWD_", "root");
//define("SERVER_NAME", "localhost");
//define("PASSWORD", "");
define("_DB_NAME_", "eshop");
define("TABLE", "rtt_" . $_POST['table']);
*/
define("TABLE", _DB_PREFIX_ . $_POST['table']);

header('Content-Type: text/html; charset=UTF-8');
//$mysqli = @mysqli_connect(SERVER_NAME, USERNAME, PASSWORD);
$mysqli = new mysqli(_DB_SERVER_,_DB_USER_,_DB_PASSWD_,_DB_NAME_);
//Connecting to database
//$mysqli = mysqli_connect(SERVER_NAME, USERNAME, PASSWORD, DATABASE);
$mysqli->set_charset("utf8");

//Check database connection
if ($mysqli === false) {
    die ("\nCould not connect:  " . mysqli_connect_error());
}
function isHTML($string){
    if($string != strip_tags($string)){
        // is HTML
        return true;
    }else{
        // not HTML
        return false;
    }
}
function isSpecialChar($string)
{
    
    //$string = preg_split('//u', $chars, -1, PREG_SPLIT_NO_EMPTY);
    if (preg_match('/'.preg_quote('^\'£$%^&*()}{@#~?><,@|-=-_+-¬', '/').'/', $string))
    {
        return true;
    }else{
        return false;
    }
    
    //return true;
}
function postSQL($string)
{
    if (isHTML($string))
    {
        $string = strip_tags($string);
    }
    if (isSpecialChar($string))
    {
        $string = addslashes($string);
    }
    return $string;
}
function prepareQuery($fields = [])
{
    $sql = 'INSERT INTO `'.TABLE.'`(';
    $strFields = '';
    foreach($fields as $field)
    {
        $strFields .= '`'.$field.'`,';
    }
    $sql .= substr($strFields,0,-1).') VALUES(';
    $strValues = '';
    foreach($fields as $field)
    {
        //$mysqli->real_escape_string(
        if ( $field != 'date_add' OR $field != 'date_upd' OR $field != 'installed_date' OR $field != 'date_start' OR $field != 'date_end' )
        {
            $strValues .= '"'. postSQL($_POST[$field]).'",';
        }else{
            $strValues .= postSQL($_POST[$field]).',';
        }
    }
    $sql .= substr($strValues,0,-1).');';
            
    return $sql;
}

function insertQuery($sql_query, $mysqli)
{
    return mysqli_query($mysqli, $sql_query);
}

//Function to execute database queries
function executeQuery($sql_query, $mysqli)
{
    //mysql_set_charset('utf8', $mysqli);
    $result = mysqli_query($mysqli, $sql_query);
    if ($result) {
        //You have to run mysqli_fetch_array to get real data as array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

/* Equipment */
$fieldsRequired   = array('code','refference','img_name','img_group','img_qr','title','description','id_status', 'id_complex', 'id_company', 'id_categorie','id_parent','site', 'model','mark','serial_number','installed_date','state','date_add','date_upd');
if ($response['table'] == 'equipment' && !empty($_POST['title']))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}
$fieldsRequired   = array('code','bom_title','id_model','id_mark','id_bom','description','date_add','date_upd');
if ($response['table'] == 'bill_of_material' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    //$_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}

/* Equipment Status */
$fieldsRequired   = array('code','equipment_status','description','date_add','date_upd');
if ($response['table'] == 'equipment_status' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}


/* Job */
$fieldsRequired   = array('code','title','description','date_add','date_upd');
if ($response['table'] == 'job' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
        //$response['message'] = $_POST['title'];
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}


/* Equipment Categorie */
$fieldsRequired   = array('id_lang','code','title','description','date_add','date_upd');
if ($response['table'] == 'article_categorie' && !empty($_POST))
{
    $_POST['id_lang']  = 1;
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    //$_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réussite!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}

/* Company */
$fieldsRequired   = array('img_name','siren','siret','tva_number','company','code','principal_contact','telephone_principal','telephone_seconde','fax','website','email_principal','email_secondaire','categorie_company','system_role','id_address','active','deleted','date_add','date_upd','sync');
if ($response['table'] == 'company' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
        
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}
/* Project */
$fieldsRequired   = array('code','title','description','status','date_start','date_end','date_add','date_upd');
if ($response['table'] == 'project' && !empty($_POST['title']))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
        
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}

/* Account */
$fieldsRequired   = array('code','account_title','description','status','date_start','date_end','date_add','date_upd');
if ($response['table'] == 'account' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
        
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}

//after getting the result send output to brower with json encode and then from your ajax response
// You can handle json data easily.
// And for json response you can't just write echo from everywhere in script. Send either die on failure or result on success
//$response['result'] = executeQuery($sql_query, $mysqli);

function prepareInsertQuery($tableName, $fields = [],$values=[])
{
    $sql = 'INSERT INTO `'.$tableName.'`(';
    $strFields = '';
    foreach($fields as $field)
    {
        $strFields .= '`'.$field.'`,';
    }
    $sql .= substr($strFields,0,-1).') VALUES(';
    $strValues = '';
    foreach($fields as $k=>$field)
    {
        if(strtotime($_POST[$field]))
        {
            $strValues .= '"'.$_POST[$field].'",';
        }else if ($field !== 'date_add' OR $field !== 'date_upd' OR $field !== 'installed_date' OR $field !== 'date_start' OR $field != 'date_end' )
        {
            $strValues .='"'.(!empty($values[$field])?$values[$field]:$_POST[$field]).'",';
        }else{
            $strValues .=  ''.($_POST[$field]).',';
        }
    }
    $sql .= substr($strValues,0,-1).');';
            
    return $sql;
}
$fieldsRequired   = array('id_task_group','id_task','id_workorder','id_equipment','estimated_time','real_time','description','suggestion','message_error','messure','customer_criteria','result','note','active','deleted','date_add','date_upd','sync');
if ($response['table_group'] == 'task_group' && !empty($_POST['act']))
{
    $sql_query = "SELECT * FROM " . TABLE . ' WHERE `id_task_group`=' . $_POST['groupid'];
    $response['result'] = executeQuery($sql_query, $mysqli);
    $records = executeQuery($sql_query, $mysqli);
    if ($records)
    {
        foreach($records as $record)
        {
            $_POST['id_workorder'] = $_POST['subid'];
            $_POST['id_equipment'] = $_POST['equid'];
            $_POST['date_add'] = $currentDate;
            $_POST['date_upd'] = $currentDate;
            $table = "rtt_".$_POST['tablesub'];
            $sql_query = prepareInsertQuery($table,$fieldsRequired,$record);
            $response['sql'] = $sql_query;
            insertQuery($sql_query, $mysqli);
        }
        $sql_query = "SELECT t.*, e.title FROM " . $table ." AS t LEFT JOIN rtt_equipment AS e ON t.id_equipment = e.id_equipment WHERE t.id_workorder=".$_POST['id_workorder'];
        $response['result'] = executeQuery($sql_query, $mysqli);
    }
}

$fieldsRequired   = array('id_supplier','id_equipment','code','title','description','refference','quantity_order','unit_price','amount_cost','quantity_received','pay_service','purchasing_service','amount','unit','pay_account','discount_percent','id_customer','discount_amount','type','account_quantity','account_amount','received_min','received_max','quantity_completed','action_received','tax_categorie','label','purchase_account','date_preview','complet','cancel','close','invoice_quantity','invoice_amount','invoice_non_quantity','invoice_non_amount','contract_type','contract_ref','invoice_on','date_add','date_upd');
if ($response['table_group'] == 'supplier' && !empty($_POST['act']))
{
    $sql_query = "SELECT * FROM " . TABLE . ' WHERE `id_company`=' . $_POST['groupid'];
    $response['result'] = executeQuery($sql_query, $mysqli);
    $records = executeQuery($sql_query, $mysqli);
    if ($records)
    {
        foreach($records as $record)
        {
            $_POST['id_workorder'] = $_POST['subid'];
            $_POST['id_equipment'] = $_POST['equid'];
            $_POST['id_supplier']  = $_POST['groupid'];
            $_POST['date_add'] = $currentDate;
            $_POST['date_upd'] = $currentDate;
            $table = "rtt_".$_POST['tablesub'];
            $sql_query = prepareInsertQuery($table,$fieldsRequired,$record);
            $response['sql'] = $sql_query;
            insertQuery($sql_query, $mysqli);
        }
        $sql_query = "SELECT pd.*, sup.company, sup.website FROM " . $table ." AS pd LEFT JOIN rtt_company AS sup ON pd.id_supplier = sup.id_company WHERE pd.id_equipment=".$_POST['id_equipment'];
        $response['result'] = executeQuery($sql_query, $mysqli);
        
    }
}

/* WorkOrder Status */
$fieldsRequired   = array('code','status','description','date_add','date_upd');
if ($response['table'] == 'work_order_status' && !empty($_POST))
{
    $_POST['date_add'] = $currentDate;
    $_POST['date_upd'] = $currentDate;
    $_POST['installed_date'] = date('Y-m-d',time());
    
    $sql_query = prepareQuery($fieldsRequired);
    $response['message'] = $sql_query;
    
    if (insertQuery($sql_query, $mysqli))
    {
        $response['message'] = 'Action : ' . $_POST['act'] . ' réusi!';
        $sql_query = "SELECT * FROM " . TABLE;
        $response['result'] = executeQuery($sql_query, $mysqli);
    }else{
        $response['message'] = 'Action : something went wrong! ' .$mysqli->error ;
    }
    
}
/* Order Detail */
$fieldsRequired   = array('id_order_detail','id_order_list','id_purchase_order','id_supplier','id_customer','id_article','id_bom','code','title','description','refference','order_quantity','sale_unit_price','sale_amount_cost','sale_discount_percent','sale_discount_amount','purchased_quantity','purchased_unit_price','purchased_amount_cost','purchased_quantity_received','pay_service','purchased_service','purchased_amount','unit','pay_account','discount_percent','discount_amount','id_type','account_quantity','account_amount','received_min','received_max','quantity_completed','action_received','tax_categorie','id_tax','label','purchase_account','date_preview','complet','cancel','close','invoice_quantity','invoice_amount','invoice_non_quantity','invoice_non_amount','contract_type','contract_ref','invoice_on','date_add','date_upd');
if ($response['table_group'] == 'article' && !empty($_POST['act']))
{
    $sql_query = "SELECT * FROM " . TABLE . ' WHERE `id_'.$response['table_group'].'`=' . $_POST['groupid'] .' OR `id_parent`=' . $_POST['groupid'];
    $response['result'] = executeQuery($sql_query, $mysqli);
    //$response['result'] = $sql_query;
    $records = executeQuery($sql_query, $mysqli);
    if ($records)
    {
        foreach($records as $record)
        {
            $_POST['id_order_list'] = $_POST['subid'];
            $_POST['id_customer']   = $_POST['equid'];
            $_POST['date_add']      = $currentDate;
            $_POST['date_upd']      = $currentDate;
            $table = "rtt_".$_POST['tablesub'];
            $sql_query = prepareInsertQuery($table,$fieldsRequired,$record);
            $response['sql'] = $sql_query;
            insertQuery($sql_query, $mysqli);
        }
        $sql_query = "SELECT t.*, e.title FROM " . $table ." AS t LEFT JOIN rtt_article AS e ON t.id_article = e.id_article WHERE t.id_order_list=".$_POST['id_order_list'];
        $response['result'] = executeQuery($sql_query, $mysqli);
    }
}

/* Payment Detail */
$fieldsRequired   = array('id_payment_detail','id_order_list','id_purchase_order','id_supplier','id_customer','id_payment_mode','id_bom','code','title','description','refference','order_quantity','sale_unit_price','sale_amount_cost','sale_discount_percent','sale_discount_amount','purchased_quantity','purchased_unit_price','purchased_amount_cost','purchased_quantity_received','pay_service','purchased_service','purchased_amount','unit','pay_account','discount_percent','discount_amount','id_type','account_quantity','account_amount','received_min','received_max','quantity_completed','action_received','tax_categorie','id_tax','label','purchase_account','date_preview','complet','cancel','close','invoice_quantity','invoice_amount','invoice_non_quantity','invoice_non_amount','contract_type','contract_ref','invoice_on','date_add','date_upd');
if ($response['table_group'] == 'payment_mode' && !empty($_POST['act']))
{
    $sql_query = "SELECT * FROM " . TABLE . ' WHERE `id_'.$response['table_group'].'`=' . $_POST['groupid'] .'';
    $response['result'] = executeQuery($sql_query, $mysqli);
    //$response['result'] = $sql_query;
    $records = executeQuery($sql_query, $mysqli);
    if ($records)
    {
        foreach($records as $record)
        {
            $_POST['id_order_list'] = $_POST['subid'];
            $_POST['id_customer']   = $_POST['equid'];
            $_POST['date_add']      = $currentDate;
            $_POST['date_upd']      = $currentDate;
            $table = "rtt_".$_POST['tablesub'];
            $sql_query = prepareInsertQuery($table,$fieldsRequired,$record);
            $response['sql'] = $sql_query;
            insertQuery($sql_query, $mysqli);
        }
        $sql_query = "SELECT t.*, e.title AS mode_title FROM " . $table ." AS t LEFT JOIN rtt_payment_mode AS e ON t.id_payment_mode = e.id_payment_mode WHERE t.id_order_list=".$_POST['id_order_list'];
        $response['result'] = executeQuery($sql_query, $mysqli);
    }
}

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
exit();

//echo json_encode($response);
//echo json_encode($_POST);
?>