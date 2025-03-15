<?php
namespace Bundles\classes;
//use Bundles\classes\Dispatcher as  BundlesDispatcher;

class  Dispatcher1
{
    public static $layout = array();
    public function __construct(){}
    public static function assign($layout = array())
    {
        self::$layout = $layout;
    }
    public static function displayFront($layout = array())
    {
        $output = '';
        if (!empty($layout))
        {
            self::$layout = $layout;
        }
        if (!empty(self::$layout))
        {
            foreach(self::$layout as $block => $content)
            {
                if (strtolower($block) == 'header'){
                    $output .= '<header id="'.$block.'" class="'.$block.' container">'.$content.'</header>';
                }else if (strtolower($block) == 'storytelling'){
                    $output .= '<section id="'.$block.'" class="'.$block.' container">'.$content.'</section>';
                }else if (strtolower($block) == 'header'){
                    $output .= '<header id="'.$block.'" class="'.$block.'">'.$content.'</header>';
                }else if (strtolower($block) == 'storytelling'){
                    $output .= '<section id="'.$block.'" class="'.$block.' container">'.$content.'</section>';
                }else if (strtolower($block) == 'footer'){
                    $output .= '<footer id="'.$block.'" class="'.$block.'">'.$content.'</footer>';
                }else if (strtolower($block) == 'menu-bar'){
                    $output .= '<ul id="'.$block.'" class="'.$block.'">'.$content.'</ul>';
                }else if (strtolower($block) == 'menu'){
                    $output .= '<ul id="'.$block.'" class="'.$block.'">'.$content.'</ul>';
                }else if (strtolower($block) == 'nav'){
                    $output .= '<nav id="'.$block.'" class="sidebar close">'.$content.'</nav>';
                }else{
                    $output .= '<div id="'.$block.'" class="'.$block.'">'.$content.'</div>';
                }
            }
        }
        //var_dump(self::$layout);
        echo $output;
    }
    public static function display($layout = array())
    {
        $output = '';
        if (!empty($layout))
        {
            self::$layout = $layout;
        }
        if (!empty(self::$layout))
        {
            foreach(self::$layout as $block => $content)
            {
                if (strtolower($block) == 'footer'){
                    $output .= '<footer id="'.$block.'" class="'.$block.'">'.$content.'</footer>';
                }else if (strtolower($block) == 'menu-bar'){
                    $output .= '<ul id="'.$block.'" class="'.$block.'">'.$content.'</ul>';
                }else if (strtolower($block) == 'menu'){
                    $output .= '<ul id="'.$block.'" class="'.$block.'">'.$content.'</ul>';
                }else if (strtolower($block) == 'nav'){
                    $output .= '<nav id="'.$block.'" class="sidebar close">'.$content.'</nav>';
                }else{
                    $output .= '<div id="'.$block.'" class="'.$block.'">'.$content.'</div>';
                }
            }
        }
        //var_dump(self::$layout);
        echo $output;
    }
}
?>