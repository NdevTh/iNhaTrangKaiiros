<?php
/* Debug only */
@ini_set('display_errors', 'on');

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
class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            //echo str_replace('Bundles\\','',$class);
            
            $file = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('Bundles\\','',$class)).'.php';
			//$file = '../'.str_replace('\\', '/', $class).'.php';
			//echo '<br>' . $file.'<br>';
            if (file_exists($file)) {
				//echo '<br>' . $file.' exist <br>';
                require $file;
                return true;
            }
            return false;
        });
    }
}

try {
    /* Directories */
    define('_RTT_ROOT_DIR_',             basename(getcwd()));
    define('_RTT_TRANSLATIONS_DIR_',_RTT_ROOT_DIR_.'/translations/');
    //echo _RTT_TRANSLATIONS_DIR_ ;
				   define('_THEME_DIR_','../themes/devise');
    
    /* Parameters */
    define('_ADM_SYSTEM_ROLE_',1);
    define('_EMP_SYSTEM_ROLE_',2);
    define('_CUS_SYSTEM_ROLE_',3);
    define('_SUP_SYSTEM_ROLE_',4);
    
    $ConsommableCategories = array('Instrument','Matériel','Pièce');
    $MaintenanceCategories = array('Maintenance Préventive','Maintentance Corrective');
    $priorities = array('Urgent','Medium','Normal');
    $typeQuotation = array('Devis','Commandé','Réception');
    $statusQuotation = array('Normal','Approuvé','Commandé');
    $typePurchaseDetail = array('Normal','Approuvé','Commandé','Reçue','Fermé');
    
    //throw new Error("Some error message");
    Autoloader::register();
    $_POST["page"] = !empty(Tools::getValue('tab'))?Tools::getValue('tab'):'home';
    $_POST["log_action"] = !empty(Tools::getValue('act'))?Tools::getValue('act'):'view';
    $_POST["user_id"] = !empty(Tools::getValue('user_id'))?Tools::getValue('user_id'):'0';
    
    WebCore::autoSystemCheck();
    //WebCore::mkCore();
    /*
    $txt = '<?php<br>';
    $txt.= 'echo "test";';
    $txt.= '?>';
    */
    //BundlesFile::write('test.mdl',$txt);
    //BundlesFile::read('test.mdl');
    WebCore::includeCSS(array('css/style.css?v2','css/zingchart.min.css'));
    //$requiredFields = array('page','username','log_time','log_action','log_name','user_id','id');
    
} catch(Error $e) {
    echo $e->getMessage();
}
?>