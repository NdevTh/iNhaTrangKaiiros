<?php
function sendMail($subjectMail,$contentHtml,$fromMail="contact.eshop.kh@gmail.com",$toMail="contact.eshop.kh@gmail.com"){
    //$fromMail = 'set your from mail';
    $boundary = str_replace(" ", "", date('l jS \of F Y h i s A'));
    //$subjectMail = "New design submitted by " . $userDisplayName;

    /*
    $contentHtml = '<div>Dear Admin<br /><br />The following design is submitted by '. $userName .'.<br /><br /><a href="'.$sdLink.'"><b>Click here</b></a> to check the design.</div>';
    $contentHtml .= '<div><a href="'.$imageUrl.'"><img src="'.$imageUrl.'" width="250" height="95" border="0" alt="my picture"></a></div>';
    $contentHtml .= '<div>Name : '.$name.'<br />Description : '. $description .'</div>';
    */
    
    $headersMail = '';
    $headersMail .= 'From: ' . $fromMail . "\r\n" . 'Reply-To: ' . $fromMail . "\r\n";
    $headersMail .= 'Return-Path: ' . $fromMail . "\r\n";
    $headersMail .= 'MIME-Version: 1.0' . "\r\n";
    $headersMail .= "Content-Type: multipart/alternative; boundary = \"" . $boundary . "\"\r\n\r\n";
    $headersMail .= '--' . $boundary . "\r\n";
    $headersMail .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
    $headersMail .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
    $headersMail .= rtrim(chunk_split(base64_encode($contentHtml)));
    
    try {
        if (mail($toMail, $subjectMail, "", $headersMail)) {
            $status = 'success';
            $msg = 'Mail sent successfully.';
        } else {
            $status = 'failed';
            $msg = 'Unable to send mail.';
        }
    } catch(Exception $e) {
        $msg = $e->getMessage();
    }
}
function subTab()
{
    $output = '';
    $tabs = array();
    $subtabs = array();
    
    if (isset($_GET['tab']))
    {
        $whereClause=  '[name="'.ucfirst(Tools::getValue('tab')).'"]';
        $tabs = xTab::getWhereTagName($whereClause);
        if (!$tabs){
            $whereClause=  '[url="'.(Tools::getValue('tab')).'"]';
            $tabs = xTab::getWhereTagName($whereClause);
        }
        $output .= '<a href="index.php?tab='. (!empty($tabs[0]['url'])?$tabs[0]['url']:$tabs[0]['name']).'">'.(!empty($tabs[0]['image'])?('<img src="../images/advancetools/'.$tabs[0]['image'].'"/> '):"") .l($tabs[0]['name']).'</a>';
        //var_dump($tabs);
    }
    if (isset($_GET['sub']))
    {
        //var_dump($tabs);
        $whereClause=  '[url="'.Tools::getValue('sub').'"]';
        $subtabs = xTab::getWhereTagName($whereClause);
        $output .= '  >>  <a href="index.php?tab='. (!empty($tabs[0]['url'])?$tabs[0]['url']:$tabs[0]['name']).'&sub='.(isset($subtabs[0]['url'])?$subtabs[0]['url']:$subtabs[0]['name']).'">'.(!empty($subtabs[0]['image'])?('<img src="../images/advancetools/'.$subtabs[0]['image'].'"/> '):"") .l($subtabs[0]['name']).'</a>';
        //var_dump($subtabs);
    }
    return $output;
}


function l($str,$type='Admin')
{
    global $_LANGADM, $_ERRORS, $_FIELDS;
    //var_dump($_LANGADM);
    if (strtolower($type) == 'admin'){
        $key = 'admin'.md5($type.$str);
        //echo $str . ' : ' . $key;
        return isset($_LANGADM[$key])?$_LANGADM[$key]:$str;
    }
}

function getHeader()
{
    global $theme, $cookie;
    $tabs = xTab::getParentTabs();
    //var_dump($tabs);
    $output = '';
    
        $output .= '<nav class="navbar bg-'.$theme.'">';
        $output .= '   <!-- LOGO -->';
        $output .= '   <div class="logo"><img class="logo-img" src="../images/s/logo.png" alt="eShop" tooltip="eShop" /></div>';
    
        $output .= '   <!-- NAVIGATION MENU -->';
        $output .= '   <ul class="nav-links">';
        
        $output .= '     <!-- USING CHECKBOX HACK -->';
        $output .= '     <input type="checkbox" id="checkbox_toggle" />';
        $output .= '     <label for="checkbox_toggle" class="hamburger">&#9776;</label>';
        
        $output .= '     <!-- NAVIGATION MENUS -->';
        $output .= '     <div class="menu bg-'.$theme.'">';
        
        $output .= '       <li class="user-info services">';
        $output .= '           <a href="#"> <img src="../images/advancetools/employee.gif"/> '.(isset($cookie)?strtoupper($cookie["username"]):'Connexion').'</a>';
        
        $output .= '           <!-- DROPDOWN MENU -->';
        $output .= '           <ul class="dropdown bg-'.$theme.'">';
        $output .= '               <li><a href="index.php?tab=configuration&sub=user&act=form&uid='.$cookie["id"].'">  <img src="../images/advancetools/employee.gif"/> '.l("Change Your Password").' </a></li>';
        $output .= '               <li><a href="/"> <img src="../images/advancetools/employee.gif"/>  '.l("Modify Your Profile").'</a></li>';
        $output .= '               <li><a href="'.$_SERVER['REQUEST_URI'] .'&logout'.'"> <img src="../images/advancetools/nav-logout.gif"/>  '.l("Logout").'</a></li>';
        $output .= '           </ul>';
    
        $output .= '       </li>';
        
        $output .= '       <div class="mobile bg-'.$theme.'">';
        if ($tabs){
            foreach($tabs as $tab){
                //$output .= '       <li class="services"><a href="'.(!empty($tab['url'])?$tab['url']:'#').'">'.$tab['name'].'</a></li>';
                $subtabs = xTab::getChildTabs($tab['id']);
                //var_dump($subtabs);
                if ($subtabs){
                    $output .= '       <li class="services">';
                    $output .= '           <a href="#">'.(!empty($tab['image'])?'<img src="../images/advancetools/'.$tab['image'].'"/> ':"").l($tab['name']).'</a>';

                    $output .= '           <!-- DROPDOWN MENU -->';
                    $output .= '           <ul class="dropdown bg-'.$theme.'">';
        
                    foreach($subtabs as $subtab){
                        $output .= '               <li><a href="index.php?tab='.strtolower($tab['url']).'&sub='.$subtab['url'].'">'.(!empty($subtab['image'])?('<img src="../images/advancetools/'.$subtab['image'].'"/> '):'').l($subtab['name']).' </a></li>';
                    }
                    $output .= '           </ul>';
                    $output .= '      </li>';
                } else {
                    $output .= '       <li class="services"> <a href="index.php?tab='. (!empty($tab['url'])?$tab['url']:'#').'">'.(!empty($tab['image'])?('<img src="../images/advancetools/'.$tab['image'].'"/> '):"") .l($tab['name']).'</a></li>';
                }
            }
        }
    /*
        $output .= '       <li class="services">';
        $output .= '           <a href="#">Services</a>';

        $output .= '           <!-- DROPDOWN MENU -->';
        $output .= '           <ul class="dropdown">';
        $output .= '               <li><a href="/">Dropdown 1 </a></li>';
        $output .= '               <li><a href="/">Dropdown 2</a></li>';
        $output .= '               <li><a href="/">Dropdown 2</a></li>';
        $output .= '               <li><a href="/">Dropdown 3</a></li>';
        $output .= '               <li><a href="/">Dropdown 4</a></li>';
        $output .= '           </ul>';

        $output .= '      </li>';

        $output .= '      <li><a href="/">Pricing</a></li>';
        $output .= '      <li><a href="/">Contact</a></li>';
        $output .= '    </div>';
    
        $output .= '    </div>';
    */
        $output .= '  </ul>';
    
        $output .= '</nav>';
    
    return $output; //$tabs;
}
?>