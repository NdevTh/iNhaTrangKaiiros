<?php
/* Debug only */
@ini_set('display_errors', 'off');
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

ob_start();

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

try{
    Autoloader::register();
    /* Directories */
    define('_RTT_ROOT_DIR_',             basename(getcwd()));
    define('_RTT_TRANSLATIONS_DIR_','Bundles/translations/');
    //echo _RTT_TRANSLATIONS_DIR_ ;
    define('_ADMIN_BASE_DIR_','Admin123');
    define('_THEME_DIR_','themes/devise');
    
}catch(Error $e) {
    echo $e->getMessage();
}
?>