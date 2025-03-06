<?php
namespace Bundles\classes;
//use Bundles\classes\Tools as  BundlesTools;
class Tools
{
    
    static public function refresh($url, $sec=0)
    {
        header("Refresh:".$sec."; url=".$url);
    }
    /**
     * Encrypt password
     *
     * @param object $object Object to display
     */
    static public function encrypt($passwd)
    {
        return md5(_COOKIE_KEY_.$passwd);
    }
    /**
     * Get token to prevent CSRF
     *
     * @param string $token token to encrypt
     */
    public static function getToken($page = true)
    {
        global $cookie,$user;
        if ($page === true){
            return (self::encrypt(self::getValue('email').self::getValue('pwd').$_SERVER['SCRIPT_NAME']));
        }else{
            return (self::encrypt(self::getValue('email').self::getValue('pwd').$page));
        }
    }
    public static function array_sort($array, $on, $order="SORT_ASC")
    {
        $new_array = array();
        $sortable_array = array();
        
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            
            switch ($order) {
                case "SORT_ASC":
                   asort($sortable_array);
                   break;
                case "SORT_DESC":
                   arsort($sortable_array);
                   break;
            }
            
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        
        return $new_array;
    }
    
    public static function getArrayKey($value,$arr)
    {
        $ret = '';
        foreach($arr as $k => $val)
        {
            $ret.= $k . ' Val: ' .$val;
            if ($value != $val)
            {
                //return $k;
            }
        }
        return $ret;
    }
    
    public static function newUrl($key,$newValue)
    {
        // parse the url
        $pathInfo = parse_url($_SERVER['REQUEST_URI']);
        $queryString = $pathInfo['query'];
        // convert the query parameters to an array
        parse_str($queryString, $queryArray);
        // add the new query parameter into the array
        $queryArray[$key] = $newValue;
        // build the new query string
        $newQueryStr = http_build_query($queryArray);
        // construct new url
        return $newQueryStr;
    }
    public static function scanFolder($dir='../../modules')
    {
		
        //echo $dir;
        if (!file_exists($dir))
        {
            $dir = "tabs";
        }
        $a = array_diff(scandir($dir),array('.','..'));
        if (!empty($a))
        {
            $records = array();
            $row = 0;
            foreach($a as $folder){
                $fullPath = $dir.'/'.$folder ;
                if (is_dir($fullPath))
                {
                    //echo $folder.' - '.date ("F d Y H:i:s.", filemtime($fullPath) ). '<br/>';
                    //return $folder.' - '.date ("d/m/Y H:i:s.", filemtime($fullPath) ). '<br/>';
                    $records[$row]["folder"] = $folder;
                    $records[$row]["created_at"] = date ("d/m/Y H:i:s.", filemtime($fullPath) );
                    //echo "$filename was last modified: " . date ("F d Y H:i:s.", filemtime($fullPath))
                }
            }
            return $a;
        }
    }
    public static function getValue($key){
        return isset($_POST[$key])?$_POST[$key]:(isset($_GET[$key])?$_GET[$key]:(isset($_SESSION[$key])?$_SESSION[$key]:''));
    }
    
    public static function isSubmit($key){
        return isset($_POST[$key])?true:(isset($_GET[$key])?true:false);
    }
    
    public static function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }
    
    public static function displayError($string = 'Hack attempt', $htmlentities = true)
    {
        global $_ERRORS;
        if (!is_array($_ERRORS)){
            return str_replace('"', '&quot;', $string);
        }
        $key = md5(str_replace('\'', '\\\'', $string));
        $str = (isset($_ERRORS) AND is_array($_ERRORS) AND key_exists($key, $_ERRORS)) ? ($htmlentities ? htmlentities($_ERRORS[$key], ENT_COMPAT, 'UTF-8') : $_ERRORS[$key]) : $string;
        return str_replace('"', '&quot;', stripslashes($str));
    }
    
    public static function makeDir($arrDir = array(),$path = '')
    {
        foreach ($arrDir as $dir){
            if (!file_exists($path.$dir)) {
                mkdir($path.$dir, 0777, true);
            }
        }
    }
}
?>