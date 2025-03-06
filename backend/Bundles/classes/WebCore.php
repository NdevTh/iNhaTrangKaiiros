<?php
namespace Bundles\classes;
//use Bundles\classes\WebCore as  BundlesWebCore;
use Bundles\classes\XMLFile as  BundlesXMLFile;
use Bundles\classes\Db as Db;

class WebCore
{
    public static $js = array();
    public static $css = array();
    
    private static $api_domain  = 'google.com';
	
	public static function scanDirectory($dirname)
	{
		/*
		if (!empty($dirname))
        {
           mkdir($dirname, 0755);
        }
		//$dirname = '/path/to/my/dirname';
		*/
		if (!file_exists($dirname)) {
			
			//return $scanned_directory;
			mkdir($dirname, 0755);
			//echo "The directory $dirname was successfully created.";
			//return array();
		}
		$files = array_diff(scandir($dirname), array('..', '.'));
		$records = array();
		if(!empty($files))
		{
			foreach($files as $file)
			{
				$file_path = $dirname.$file;
				$file_info = pathinfo($file_path);
				$record = array();
				$record["name"] = $file;
				$record["note"] = "";
				$record["file_type"] = strtoupper($file_info["extension"]);
				$record["size"] = (filesize($file_path)/1024) . " M";
				//$records[] = $record;
				array_push($records, $record);
			}
		}
		return $records;
	}
    public static function serverAliveOrNot()
    {
        if($pf = @fsockopen(self::$api_domain, 443))
        {
            fclose($pf);
            $_SESSION['serverAliveOrNot'] = true;
            return true;
        } else {
            $_SESSION['serverAliveOrNot'] = false;
            return false;
        }
    }
    
    public static function commaToDot($string)
    {
        return str_replace(",",".",$string);
    }
    
    public static function dotToComma($string)
    {
        return str_replace(".",",",$string);
    }
    
    public static function strToInt($string)
    {
        return (int)$string;
    }
    
    public static function strToFloat($string)
    {
        return (float)$string;
    }
    
    public static function addZeroBeforeNum($string,$nFixed)
    {
        $newString = '';
        if (strlen($string) < $nFixed){
            $n = $nFixed - strlen($string);
            for ($i = 0; $i < $n; $i++)
            {
                $newString .= '0';
            }
        }
        $newString .= $string;
        return $newString;
    }
    
    
    public static function getList($field,$records = array())
    {
        $List = array();
        if ($records)
        {
            foreach($records as $record)
            {
                array_push($List,$record[$field]);
            }
        }
        return $List;
    }
    public static function getNumberOnly($string) {
        return preg_replace("/[^0-9\.]/", '', $string);
    }
    
    public static function getWeekday($date) {
        return date('w', strtotime($date));
    }
    
    public static function allTables()
    {
        return;
    }
    public static function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }else{
            $ip = $remote;
        }
            return $ip;
    }
    public static function user_agent(){
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
        file_put_contents('config/log.txt',$_SERVER['HTTP_USER_AGENT']);
        if($iPad||$iPhone||$iPod){
            return 'ios';
        }else if($android){
            return 'android';
        }else{
            return 'pc';
        }
    }
    
    public static function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    
    public static function l($string = "na",$type="field", $addslashes = FALSE, $htmlentities = TRUE)
    {
        global $_LANGADM, $_ERRORS, $_FIELDS;
        $key = md5(str_replace('\'', '\\\'', $string));
		$str = str_replace(" ","_",$string);
		
        if (strtolower($type) === 'field'){
        	    $str = array_key_exists($str.'_'.$key, $_FIELDS)?$_FIELDS[$str.'_'.$key]:$string;
        }else if (strtolower($type) === 'error'){
            $str =  array_key_exists($str.'_'.$key, $_ERRORS)?$_ERRORS[$str.'_'.$key]:$string;//(key_exists(get_class($this).$key, $_LANGADM)) ? $_LANGADM[get_class($this).$key] : ((key_exists($class.$key, $_LANGADM)) ? $_LANGADM[$class.$key] : $string);
        }else {
			
            $str =  array_key_exists($str.'_'.$key, $_LANGADM)?$_LANGADM[$str.'_'.$key]:$string;//(key_exists(get_class($this).$key, $_LANGADM)) ? $_LANGADM[get_class($this).$key] : ((key_exists($class.$key, $_LANGADM)) ? $_LANGADM[$class.$key] : $string);
        }
        $str = $htmlentities ? htmlentities($str, ENT_QUOTES, 'utf-8') : $str;
        return str_replace('"', '&quot;', ($addslashes ? addslashes($str) : stripslashes($str)));
    }
    
    public static function setCSS($arrCSS = array()){
        self::$css = $arrCSS;
    }
    public static function includeCSS($arrCSS = array()){
        //var_dump(self::$js);
        $ret = '';
        if (!empty($arrCSS))
        {
            self::$css = $arrCSS;
        }
        if (!empty(self::$css))
        {
            foreach(self::$css as $file)
            {
                //echo 'File src = ' .$file;
                $ret .= '<link rel="stylesheet" href="'.$file.'">';
            }
        }
        echo $ret;
    }
    
    public static function setJS($arrJS = array()){
        self::$js = $arrJS;
    }
    public static function includeJS($arrJS = array()){
        //var_dump(self::$js);
        $ret = '';
        
        if (!empty($arrJS))
        {
            self::$js = $arrJS;
        }
        
        if (!empty(self::$js))
        {
            foreach(self::$js as $file)
            {
                $ret .= '<script src="'.$file.'"></script>';
            }
        }
        echo $ret;
    }
    
    public static function mkCore()
    {
        try{
            /* create file WebCore */
            $coreFields = array('id','loc','lastmod','changefreq','priority');
            $coreData   = array(
               array(
                  'loc'        =>'Bundles',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'classes',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'config',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'css',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'images',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'js',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               ),
               array(
                  'loc'        =>'modules',
                  'lastmod'    => date('d-m-y h:i:s'),
                  'changefreq' => 'never',
                  'priority'   => '1'
               )
            );
            foreach($coreData as $c => $record)
            {
                foreach($coreFields as $k => $field)
                {
                    if ($field != 'id')
                    {
                        $_POST[$field] = $record[$field];
                    }
                }
                BundlesXMLFile::writeFile('configs',$coreFields);
            }
            
            /* create directory */
            $listURL = BundlesXMLFile::getList('url','configs');
            if (!empty($listURL))
            {
                Tools::makeDir($listURL);
                /*
                foreach($listURL as $dir)
                {
                    echo '<br>'.$dir;
                }*/
            }
            
            
            
        } catch(Error $e) {
        //} catch(Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public static function autoSystemCheck()
    {
        try{
            //throw new Error("Some error message");
            //echo '<h1>Auto System Checking ... !</h1>';
            $records = BundlesXMLFile::readFile('configs','url');
            //var_dump($records);
            $listURL = BundlesXMLFile::getList('loc','configs');
            //var_dump($listURL);
            return self::autoXMLHistory();
        } catch(Error $e) {
        //} catch(Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    public static function autoXMLHistory()
    {
        try{
            $requiredFields = array('id','page','username','log_time','log_action','log_name','id_user','ip','nav','url');
            if (!isset($_POST['page'])){
                $_POST['page']        = 'test';
            }
            if (!isset($_POST['username'])){
                $_POST['username']    = 'dev';
            }
            if (!isset($_POST['log_time'])){
                $_POST['log_time']    = date('d-m-Y H:i:s');
            }
            if (!isset($_POST['log_action'])){
                $_POST['log_action']  = 'test';
            }
            if (!isset($_POST['log_name'])){
                $_POST['log_name']    = 'test';
            }
            if (!isset($_POST['id_user'])){
                $_POST['id_user']     = 'test';
            }
            if (!isset($_POST['nav'])){
                $_POST['nav']     = 'no';
            }
            if (!isset($_POST['url'])){
                $_POST['url']     = 'index.php?t=home';
            }else {
                $_POST['url']     = htmlspecialchars($_POST['url'], ENT_XML1, 'UTF-8');
            }
            //if (!isset($_POST['username'])){
            // if user from the share internet
            $ip = '';
            if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
                $ip = $_SERVER['HTTP_CLIENT_IP'];   
            }//if user is from the proxy   
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }//if user is from the remote address   
            else{   
                $ip = $_SERVER['REMOTE_ADDR'];   
            }   
            
            $_POST['ip']          = $ip;
            BundlesXMLFile::writeFile('logs-'.date('d-m-Y', time()),$requiredFields,'','config/log');
            return BundlesXMLFile::readLast('logs-'.date('d-m-Y', time()),'','config/log');
        } catch(Error $e) {
        //} catch(Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
	    
    public static function initTheme()
    {
        /* create hooks data */
        $hookFields = array('id','title','id_user','id_lang','bg_color','nav');
        $hookData   = array(
           array(
                  'title'       => 'default',
                  'id_user'     => '1',
                  'id_lang'     => '1',
                  'bg_color'    => 'bg-orange',
                  'nav'         => 'close'
               )
        );
        foreach($hookData as $c => $record)
        {
            foreach($hookFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('themes',$hookFields,"","../Bundles/xml");
        }
        $listHook = BundlesXMLFile::getList('title','themes',"../Bundles/xml");
        //var_dump($listHook);
    }
    public static function initHook()
    {
        /* create hooks data */
        $hookFields = array('id','name','title','description','position','id_module');
        $hookData   = array(
           array(
                  'name'        => 'header',
                  'title'       => 'Header',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '1'
               ),
               array(
                  'name'        => 'home',
                  'title'       => 'Home',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '2'
               ),
               array(
                  'name'        => 'footer',
                  'title'       => 'Footer',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '3'
               )
        );
        foreach($hookData as $c => $record)
        {
            foreach($hookFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('hooks',$hookFields,"","xml");
        }
        $listHook = BundlesXMLFile::getList('name','hooks',"xml");
        //var_dump($listHook);
    }
	    
	    public static function initFrontModule()
    {
        
        /* create module data */
        $moduleFields = array('id','name','active');
        $moduleData   = array(
               array(
                  'name'        => 'header',
                  'active'      => '1'
               ),
               array(
                  'name'        => 'home',
                  'active'      => '1'
               ),
               array(
                  'name'        => 'footer',
                  'active'      => '1'
               )
        );
        foreach($moduleData as $c => $record)
        {
            foreach($moduleFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('frontmodules',$moduleFields,"",_THEME_DIR_."/assets/xml");
        }
        $listModule = BundlesXMLFile::getList('name','frontmodules',_THEME_DIR_."/assets/xml");
        //var_dump($listModule);
            
    }
	    
	    public static function initFrontHook()
    {
        /* create hooks data */
        $hookFields = array('id','name','title','description','position','id_module');
        $hookData   = array(
           array(
                  'name'        => 'header',
                  'title'       => 'Header',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '1'
               ),
               array(
                  'name'        => 'home',
                  'title'       => 'Home',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '2'
               ),
               array(
                  'name'        => 'footer',
                  'title'       => 'Footer',
                  'description' => 'never',
                  'position'    => '1',
                  'id_module'   => '3'
               )
        );
        foreach($hookData as $c => $record)
        {
            foreach($hookFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('fronthooks',$hookFields,"",_THEME_DIR_."/assets/xml");
        }
        $listHook = BundlesXMLFile::getList('name','fronthooks',_THEME_DIR_."/assets/xml");
        //var_dump($listHook);
    }
	    
    public static function initTab()
    {
        
        /* create module data */
        $tabFields = array('id','name','id_parent','url','image','position','group_user','system_role','active');
        $tabData   = array(
               array(
                  'name'        => 'contrat',
                  'id_parent'   => '0',
                  'url'         => 'contract',
                  'image'       => '',
                  'position'    => '1',
                  'user_group'  => '1',
                  'system_role' => '1',
                  'active'      => 'on'
               ),
               array(
                  'name'        => 'Devis',
                  'id_parent'   => '0',
                  'url'         => 'quotation',
                  'image'       => '',
                  'position'    => '1',
                  'user_group'  => '1',
                  'system_role' => '1',
                  'active'      => 'on'
               ),
               array(
                  'name'        => 'Facture',
                  'id_parent'   => '0',
                  'url'         => 'invoice',
                  'image'       => '',
                  'position'    => '1',
                  'user_group'  => '1',
                  'system_role' => '1',
                  'active'      => 'on'
               )
        );
        foreach($tabData as $c => $record)
        {
            foreach($tabFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('tabs',$tabFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','tabs',"xml");
        //var_dump($listModule);
            
    }
    
    public static function initTax()
    {
        
        /* create module data */
        $tabFields = array('id','id_tax','id_lang','id_state','id_zone','name','rate');
        $tabData   = array(
               array(
                  'id_tax'      => '1',
                  'id_lang'     => '1',
                  'id_state'    => '1',
                  'id_zone'     => '1',
                  'name'        => 'TVA',
                  'rate'        => '20'
               )
        );
        foreach($tabData as $c => $record)
        {
            foreach($tabFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('taxs',$tabFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','taxs',"xml");
        //var_dump($listModule);
            
    }
    
    public static function initLang()
    {
        
        /* create module data */
        $tabFields = array('id','id_lang','active','name','iso_code');
        $tabData   = array(
               array(
                  'id_lang'     => '1',
                  'iso_code'    => 'fr',
                  'name'        => 'Fançaise',
                  'active'      => '1'
               )
        );
        foreach($tabData as $c => $record)
        {
            foreach($tabFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('langs',$tabFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','taxs',"xml");
        //var_dump($listModule);
            
    }
    
    public static function initCountry()
    {
        
        /* create module data */
        $tabFields = array('id','id_lang','name','id_country','id_zone','iso_code','active','contains_states','need_identification_number');
        $tabData   = array(
               array(
                  'id_country'                 => '1',
                  'id_zone'                    => '1',
                  'contains_states'            => '1',
                  'id_zone'                    => '1',
                  'need_identification_number' => '1',
                  'id_lang'                    => '1',
                  'name'                       => 'France',
                  'active'                     => '1'
               )
        );
        foreach($tabData as $c => $record)
        {
            foreach($tabFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('countries',$tabFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','countries',"xml");
        //var_dump($listModule);
            
    }
    
    public static function initModule()
    {
        
        /* create module data */
        $moduleFields = array('id','name','active');
        $moduleData   = array(
               array(
                  'name'        => 'header',
                  'active'      => '1'
               ),
               array(
                  'name'        => 'home',
                  'active'      => '1'
               ),
               array(
                  'name'        => 'footer',
                  'active'      => '1'
               )
        );
        foreach($moduleData as $c => $record)
        {
            foreach($moduleFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('modules',$moduleFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('name','modules',"xml");
        //var_dump($listModule);
            
    }
    
    public static function initServer()
    {
        
        /* create module data */
        $moduleFields = array('id','db_server','db_user','db_passwd','db_type','db_name','db_prefix','active');
        $moduleData   = array(
               array(
                  'db_server'        => '0.0.0.0',
                  'db_user'          => 'root',
                  'db_passwd'        => 'root',
                  'db_type'          => 'MySQL',
                  'db_name'          => 'gmao',
                  'db_prefix'        => 'rrt',
                  'active'           => '1'
               ),
               array(
                  'db_server'        => '127.0.0.1',
                  'db_user'          => 'root',
                  'db_passwd'        => '',
                  'db_type'          => 'MySQL',
                  'db_name'          => 'gmao',
                  'db_prefix'        => 'rrt',
                  'active'           => '1'
               )
        );
        foreach($moduleData as $c => $record)
        {
            foreach($moduleFields as $k => $field)
            {
                if ($field != 'id')
                {
                    $_POST[$field] = $record[$field];
                }
            }
            BundlesXMLFile::writeFile('servers',$moduleFields,"","xml");
        }
        $listModule = BundlesXMLFile::getList('db_server','servers',"xml");
        //var_dump($listModule);
            
    }
    
}
?>