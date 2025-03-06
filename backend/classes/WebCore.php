<?php
namespace classes;
use Bundles\classes\WebCore as BundleWebCore;
use Bundles\classes\XMLFile as BundlesXMLFile;

class WebCore extends BundleWebCore
{
    public static function initMenu()
    {
        /* create hooks data */
        $hookFields = array('id','title','url','description','position','active');
        $hookData   = array(
               array(
                  'title'       => 'Home',
                  'url'         => 'Home',
                  'description' => 'never',
                  'position'    => '1',
                  'active'      => '1'
               ),
               array(
                  'title'       => 'About Us',
                  'url'         => 'About',
                  'description' => 'never',
                  'position'    => '2',
                  'active'      => '1'
               ),
               array(
                  'title'       => 'Products',
                  'url'         => 'Products',
                  'description' => 'never',
                  'position'    => '3',
                  'active'      => '1'
               ),
               array(
                  'title'       => 'Blog',
                  'url'         => 'Blog',
                  'description' => 'never',
                  'position'    => '4',
                  'active'      => '1'
               ),
               array(
                  'title'       => 'Contact Us',
                  'url'         => 'Contact',
                  'description' => 'never',
                  'position'    => '5',
                  'active'      => '1'
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
            BundlesXMLFile::writeFile('menus',$hookFields,"","assets/xml");
        }
        $listHook = BundlesXMLFile::getList('title','menus',"xml");
        //var_dump($listHook);
    }
    
}    
?>