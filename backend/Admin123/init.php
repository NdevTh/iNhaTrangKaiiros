<?php
use classes\WebCore as WebCore;
use classes\Cookie as Cookie;
use classes\Tools as Tools;
use Bundles\classes\XMLFile as  BundlesXMLFile;
use classes\xLang as  xLang;
use classes\myUser as myUser;

$dbtype = _DB_TYPE_;
$dbversion = 'V1.0';
$server = 'Local - Mobile';
if (WebCore::serverAliveOrNot())
{
    $server = 'En Ligne';
}
$classCookie = new Cookie();
$classCookie->setName(Tools::getValue('token'));
$cookie = $classCookie->get();
$user = !empty($cookie)?$cookie[0]:array();
//var_dump($user[0]['id_user']);
if (isset($_GET["logout"])){
    
    $classCookie->delete();
    $_POST["page"] = !empty(Tools::getValue('t'))?Tools::getValue('t'):'home';
    $_POST["log_action"] = !empty(Tools::getValue('act'))?Tools::getValue('act'):'view';
    $_POST["id_user"] = !empty($user['id_user'])?$user['id_user']:'0';
    $_POST["username"] = !empty($user['full_name'])?$user['full_name']:'Sopheaktra ROS';
    $_POST["nav"] = !empty(Tools::getValue('nav'))?Tools::getValue('nav'):(!empty($log->nav)?$log->nav:'no');
    $_POST["url"] = $_SERVER['REQUEST_URI'];
    
    //echo Tools::getValue('nav');
    $core = WebCore::autoSystemCheck()[0];
    
    $_POST['status']     = 0;
    $_POST['date_upd']   = $currentDate;
        
    $fields = array('status','date_upd');
    myUser::updateByFields((isset($user[0]['id_user'])?$user[0]['id_user']:0),$fields);
        
    Tools::redirect("login.php");
    
}
//$cookie = $classCookie->get();
if (!$cookie)
{
    Tools::redirect("login.php");
}
//var_dump($cookie);
$systemrole = $cookie[0]['system_role'];
if ($systemrole == 1)
{
    $AdminUser = $cookie[0];
}else{
    $CusUser = $cookie[0];
}

$log = !empty(BundlesXMLFile::readLast('logs-'.date('d-m-Y', time()),'','config/log'))?BundlesXMLFile::readLast('logs-'.date('d-m-Y', time()),'','config/log')[0]:array();
if(empty($log))
{
    WebCore::autoXMLHistory();
    $log = !empty(BundlesXMLFile::readLast('logs-'.date('d-m-Y', time()),'','config/log'))?BundlesXMLFile::readLast('logs-'.date('d-m-Y', time()),'','config/log')[0]:array();
}
//var_dump($log->url);
recheck:
$theme = array();
$whereClause = '[id_user="'.(!empty($user)?$user["id_user"]:1).'"]';
$query = '//themes/theme';
$themes = !empty(BundlesXMLFile::getXMLByWhere('themes',$query,$whereClause,'../Bundles/xml'))?BundlesXMLFile::getXMLByWhere('themes',$query,$whereClause,'../Bundles/xml'):array();
//var_dump($themes);
if(empty($themes))
{
    //WebCore::initTheme();
    goto recheck;
}
$theme = $themes[0];
//var_dump($theme);
$url = str_replace(dirname($log->url)."/","",$log->url);
//echo $url;
if (strpos($url,'logout') !== false){
    $url = str_replace("&logout=1","",$url);
    //echo $url;
    //Tools::redirect($url);
}
$_POST["page"] = !empty(Tools::getValue('t'))?Tools::getValue('t'):'home';
$_POST["log_action"] = !empty(Tools::getValue('act'))?Tools::getValue('act'):'view';
$_POST["id_user"] = !empty($user['id_user'])?$user['id_user']:'0';
$_POST["username"] = !empty($user['full_name'])?$user['full_name']:'Sopheaktra ROS';
$_POST["nav"] = !empty(Tools::getValue('nav'))?Tools::getValue('nav'):(!empty($log->nav)?$log->nav:'no');
$_POST["url"] = $_SERVER['REQUEST_URI'];

//echo Tools::getValue('nav');
//$core = WebCore::autoSystemCheck()[0];

//var_dump($core);
//echo $core->nav;
//var_dump($user);
//$tables = WebCore::allTables();
//var_dump($tables);
//echo WebCore::getUserIP() . ' ' . $_SERVER['HTTP_CLIENT_IP'];
$defaultCurrency = "€";
$id_lang = !empty(Tools::getValue("lang"))?Tools::getValue("lang"):(!empty($theme)?$theme["id_lang"]:1);
$lang = !empty(xLang::getById($id_lang))?xLang::getById($id_lang):array();
//var_dump($lang);
$taxe_id = 1;
$iso = !empty($lang)?$lang["iso_code"]:'en';//strtolower(Language::getIsoById($cookie->id_lang ? $cookie->id_lang : 1));
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/errors.php');
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/fields.php');
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/admin.php');

?>