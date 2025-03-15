<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;

class Menu extends AdminTab
{
    public static function viewMenu()
    {
        $ret = '';
        $currentCSS = '';
        
        if (Tools::getValue('t') == 'hook'){
            $currentCSS = 'active';
        }
        $ret.= '<div class="sidebar">';
        //$ret.= '   <p>Menu</p>';
        //$records = XMLFile::readFile('configs','','xml');
        $menu = array(
           array('title'=>'Module','url'=>'core.php?t=Module'),
           array('title'=>'Hook','url'=>'core.php?t=Hook'),
           array('title'=>'Tab','url'=>'core.php?t=Tab'),
           array('title'=>'Server','url'=>'core.php?t=Server'),
           array('title'=>'User','url'=>'core.php?t=User'),
           array('title'=>'Tax','url'=>'core.php?t=Tax'),
           array('title'=>'Country','url'=>'core.php?t=Country'),
           array('title'=>'Langage','url'=>'core.php?t=Lang'),
           array('title'=>'FrontModule','url'=>'core.php?t=FrontModule'),
           array('title'=>'Front Hook','url'=>'core.php?t=FrontHook'),
           
        );
        //var_dump($records);
        if (!empty($menu))
        {
            foreach($menu as $record)
            {
                $active = '';
                if (strtolower(Tools::getValue('t')) == strtolower($record['title']))
                {
                    $active = 'active';
                }
                $ret.= '   <a class="'.$active.'" href="'.$record['url'].'">';
                //$ret.= '      <i class="fa fa-user-o" aria-hidden="true"></i>';
                $ret.= '      ' .$record["title"];
                $ret.= '   </a>';
                
            }
        }
        /*
        $ret.= '   <a class="" href="core.php">';
        //$ret.= '      <i class="fa fa-user-o" aria-hidden="true"></i>';
        $ret.= '      Module';
        $ret.= '   </a>';
        
        $ret.= '   <a class="" href="core.php">';
        //$ret.= '      <i class="fa fa-user-o" aria-hidden="true"></i>';
        $ret.= '      Hook';
        $ret.= '   </a>';
        */
        $ret.= '</div>';
        return $ret;
    }
}
?>