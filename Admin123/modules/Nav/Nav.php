<?php 
use classes\Module as Module; 
use classes\xTab as xTab;
use Bundles\classes\WebCore as  WebCore;
use classes\Tools as Tools;
use classes\myCompany as myCompany;

class Nav extends Module {
    
    public static function hookNav($args)
    {
        global $server,$CusUser,$AdminUser,$cookie, $systemrole;
        $ret = "";
        $tabs = array();
        if (!empty($AdminUser)){
            //echo $user['system_role'];
            $tabs = xTab::getParentTabsBySystemRole($AdminUser['system_role']);
            $imgEmp = !empty($AdminUser['img_name'])?$AdminUser['img_name']:'people2.jpg';
            $empName = !empty($AdminUser['full_name'])?$AdminUser['full_name']:'Sopheaktra ROS';
            
            $recordCom = isset(myCompany::getById($AdminUser['id_company'])[0])?(myCompany::getById($AdminUser['id_company'])[0]):array();
            if (array_key_exists('img_name',$recordCom)){
                $imgCom = $recordCom['img_name'];
            }else{
                $imgCom = 'logo_text_169x40.png';
            }
        }else{
            $tabs = xTab::getParentTabsBySystemRole($CusUser['system_role']);
            $imgEmp = !empty($CusUser['img_name'])?$CusUser['img_name']:'people2.jpg';
            $empName = !empty($CusUser['full_name'])?$CusUser['full_name']:'Sopheaktra ROS';
            //$recordCom = myCompany::getById($CusUser['id_company'])[0];
            $recordCom = isset(myCompany::getById($CusUser['id_company'])[0])?(myCompany::getById($CusUser['id_company'])[0]):array();
            if (array_key_exists('img_name',$recordCom)){
                $imgCom = $arecordCom['img_name'];
            }else{
                $imgCom = 'logo_text_169x40.png';
            }
        }
        //var_dump($imgCom);
        //var_dump($AdminUser);
        //$ret.= "Hook: Nav"; 
        $ret.= '<header>';
        $ret.= '   <div class="image-text">';
        $ret.= '      <span class="image">';
        $ret.= '         <img src="images/logo.jpg" alt="">';
        $ret.= '      </span>';
        $ret.= '      <div class="text logo-text">';
        $ret.= '          <span class="name">Server: '.$server.'</span>';
        $ret.= '          <span class="profession"> Animé par RithyThidaTévy</span>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        $ret.= '   <i id="nav_active" class="bx bxs-chevron-right toggle"></i>';
        $ret.= '</header>';
        $ret.= '<ul class="nav-links">';
        if ($tabs)
        {
            $tabs = Tools::array_sort($tabs, 'position', 'SORT_ASC');
            foreach($tabs as $tab)
            {
                $icon = (!empty($tab['image'])?$tab['image']:'2486235.png');
                $subtabs = xTab::getChildTabs($tab['id']);
                if ($subtabs)
                {
                    $subtabs = Tools::array_sort($subtabs, 'position', 'SORT_ASC');
                    $ret.= '   <li class="">';
                    $ret.= '      <div class="iocn-link">';
                    $ret.= '         <a href="#">';
                    /*$ret.= '            <i class="bx bx-'.$icon.'" ></i>';*/
                    $ret.= '            <img class="ico" src="../Bundles/images/advancetools/'.$icon.'" alt="'.$icon.'" />';
                    $ret.= '            <span class="link_name">'.WebCore::l($tab['name'],'admin').'</span>';
                    $ret.= '         </a>';
                    $ret.= '         <i class="bx bxs-chevron-down arrow" ></i>';
                    $ret.= '      </div>';
                    $ret.= '      <ul class="sub-menu">';
                    $ret.= '         <li><a class="link_name" href="#">'.WebCore::l($tab['name'],'admin').'</a></li>';
                    foreach($subtabs as $subtab){
                        $ret.= '         <li><a href="index.php?t='.$subtab['url'].'&pt='.$tab["url"].'&token='.Tools::getValue('token').'">'.WebCore::l($subtab['name'],'admin').'</a></li>';
                    }
                    /*
                    $ret.= '         <li><a href="#">JavaScript</a></li>';
                    $ret.= '         <li><a href="#">PHP & MySQL</a></li>';
                    */
                    $ret.= '      </ul>';
                    $ret.= '   </li>';
                }else{
                    $ret.= '   <li>';
                    $ret.= '      <a href="#">';
                    /*$ret.= '         <i class="bx bx-'.$icon.'" ></i>';*/
                    $ret.= '            <img class="ico" src="Bundles/images/advancetools/'.$icon.'" alt="'.$icon.'" />';
                    
                    $ret.= '         <span class="link_name">'.WebCore::l($tab['name'],'admin').'</span>';
                    $ret.= '      </a>';
                    $ret.= '      <ul class="sub-menu blank">';
                    $ret.= '          <li><a class="link_name" href="#">'.WebCore::l($tab['name'],'admin').'</a></li>';
                    $ret.= '      </ul>';
                    $ret.= '   </li>';
                }
            }
        } else {
            $ret.= '   <li>';
            $ret.= '      <a href="#">';
            $ret.= '         <i class="bx bx-grid-alt" ></i>';
            $ret.= '         <span class="link_name">Dashboard</span>';
            $ret.= '      </a>';
            $ret.= '      <ul class="sub-menu blank">';
            $ret.= '          <li><a class="link_name" href="#">Category</a></li>';
            $ret.= '      </ul>';
            $ret.= '   </li>';
            
            $ret.= '   <li class="">';
            $ret.= '      <div class="iocn-link">';
            $ret.= '         <a href="#">';
            $ret.= '            <i class="bx bx-collection" ></i>';
            $ret.= '            <span class="link_name">Category</span>';
            $ret.= '         </a>';
            $ret.= '         <i class="bx bxs-chevron-down arrow" ></i>';
            $ret.= '      </div>';
            $ret.= '      <ul class="sub-menu">';
            $ret.= '         <li><a class="link_name" href="#">Category</a></li>';
            $ret.= '         <li><a href="#">HTML & CSS</a></li>';
            $ret.= '         <li><a href="#">JavaScript</a></li>';
            $ret.= '         <li><a href="#">PHP & MySQL</a></li>';
            $ret.= '      </ul>';
            $ret.= '   </li>';
        }
        $ret.= '</ul>';
        
        $ret.= '<footer>';
        $ret.= '   <div class="image-text">';
        $ret.= '      <span class="image">';
        $ret.= '         <img src="images/emp/'.$imgEmp.'" alt="">';
        //$ret.= '          <a href="index.php?logout" class="btn name"> <i class="bx bx-power-off" ></i>  Déconnexion</a>';
        $ret.= '      </span>';
        $ret.= '      <div class="text logo-text">';
        $ret.= '          <span class="name">'.$empName.'</span>';
        //$ret.= '          <a href="index.php?'.Tools::newURL('logout','1').'" class="btn name"> <i class="bx bx-power-off" ></i>  Déconnexion</a>';
        $ret.= '      </div>';
        $ret.= '   </div>';
        //$ret.= '   <i class="bx bxs-chevron-right toggle"></i>';
        //$ret.= '   <a href="index.php?logout" class="btn name"> <i class="bx bx-power-off" ></i>  <span class="link_name"> Déconnexion</span></a>';
        
        $ret.= '</footer>';
        
        return $ret;
    }
}
?>