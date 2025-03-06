<?php
/* Debug only */
@ini_set('display_errors', 'on');
define('_RTT_DEBUG_SQL_', true);

/* Error handle */
error_reporting(E_ALL & ~E_NOTICE); // Error/Exception engine, always use E_ALL
//error_reporting(E_WARNING);
ini_set('ignore_repeated_errors', TRUE); // always use TRUE
ini_set('display_errors', FALSE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment
ini_set('display_startup_errors', FALSE);
ini_set('log_errors', TRUE); // Error/Exception file logging engine.
ini_set('error_log', 'config/errors.log'); // Logging file path
//error_log("Email this error to someone!", 1, "someone@mydomain.com");

//
use Bundles\classes\WebCore as WebCore;
use Bundles\classes\Tools as Tools;
use Bundles\classes\File as  BundlesFile;

ob_start();
date_default_timezone_set("Europe/Paris");
$currentTimeZone = date_default_timezone_get();
$currentDate = date('Y-m-d h:i:s', time());
    
//date_default_timezone_set("Asia/Bangkok");
class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
			//echo $class;
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			//$file = '../'.str_replace('\\', '/', $class).'.php';
			//echo '<br>' . $file.'<br>';
            if (file_exists('../'.$file)) {
				//echo '<br>' . $file.' exist <br>';
                require '../'.$file;
                return true;
            }
            return false;
        });
    }
}

try {
    include_once('../config/settings.inc.php');
    global $user;
    //throw new Error("Some error message");
    Autoloader::register();
    /*
    $_POST["page"] = !empty(Tools::getValue('t'))?Tools::getValue('t'):'home';
    $_POST["log_action"] = !empty(Tools::getValue('act'))?Tools::getValue('act'):'view';
    $_POST["id_user"] = !empty($user['id_user'])?$user['id_user']:'0';
    $currentDate = date('Y-m-d h:i:s', time());
        
    WebCore::autoSystemCheck();
    //WebCore::mkCore();
    /*
    $txt = '<?php<br>';
    $txt.= 'echo "test";';
    $txt.= '?>';
    */
    //BundlesFile::write('test.mdl',$txt);
    //BundlesFile::read('test.mdl');
    //WebCore::includeCSS(array('css/admin.css?v3','css/zingchart.min.css'));
    //$requiredFields = array('page','username','log_time','log_action','log_name','user_id','id');
    /* Directories */
    define('_RTT_ROOT_DIR_',             basename(getcwd()));
    define('_RTT_TRANSLATIONS_DIR_','Bundles/translations/');
    //echo _RTT_TRANSLATIONS_DIR_ ;
    /* Parameters */
    define('_ADM_SYSTEM_ROLE_',1);
    define('_EMP_SYSTEM_ROLE_',1);
    define('_CUS_SYSTEM_ROLE_',3);
    define('_SUP_SYSTEM_ROLE_',4);
				   /* Product Category */
    define('_RAW_MATERIAL_',1);
    define('_PRODUCT_SALE_',2);
    
    $actions = array('New','Edit','Onservice');
    define('_PREPA_',2);
    $actives = array('Desactive','Active');
    $status  = array('Offline','Online');
    
    $ArticleCategories = array('Purchase','Sale');
    $MaintenanceCategories = array('Maintenance Préventive','Maintentance Corrective');
    
    $typeWorkOrder =  array('Demandé','Affecté','Fermeture');
    $statusWorkOrder   = array('Ouvert','Affecté','Fermer');
    $priorities = array('Urgent','Medium','Normal');
    /* Quotation Declaration */
    $typeQuotation = array('Quotation','Order','Invoice');
    $statusQuotation = array('Create','Open','Aprouve','Close');
    $categorieQuotation = array('Internal','External');
    define('_INTERNAL_QUOTATION_',1);
    define('_EXTERNAL_QUOTATION_',2);
	
    $typePurchase = array('Commander','Approuver','Récevoir','Payer');
    $statusPurchase = array('Création','Ouverture','Approuvé','Commandé','Reçue','Fermé');
    
    $typeEmployee = array('New','Edit');
    $statusUser   = array('Offline','Online');
    $activeUser   = array('Desactive','Active');
    $systemUser   = array('Ownee','Employee','Customer','Supplier','Shipper');
	$gender = array("Female","Male","Neutre");
    $string = 'Normal';
    $key = md5(str_replace('\'', '\\\'', $string));
    //echo $string.'_'.$key;
    $defaultCurrency = "€";
} catch(Error $e) {
    echo $e->getMessage();
}
?>