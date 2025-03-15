<?php
use classes\Module as Module;
use classes\WebCore as RTTCore;
class Home extends Module {
    public static function hookHome($args)
    {
        global $defaultBackground;
        $ret = "";
        //$ret.= "Hook: Home";
        //<!-- Section "Storytelling" sur Nha Trang -->
        $ret.= '<section class="storytelling container">';
        $ret.= '<h2>Découvrir Nha Trang, Perle du Vietnam</h2>';
        $ret.= '<p>';
        $ret.= 'Bienvenue à <strong>Nha Trang</strong>, la perle côtière du Vietnam.
        Je souhaite partager avec vous mes plus beaux souvenirs d’enfance
        dans cette ville où les vagues rythment le quotidien et où
        la gastronomie est un véritable art de vivre.';
        $ret.= '</p>';
        $ret.= '<p>';
        $ret.= 'Chaque matin, j’accompagnais ma grand-mère au marché local. L’odeur
        des herbes fraîches, le bruit des vendeurs annonçant leur pêche du jour,
        tout contribua à créer ma passion pour la <em>cuisine vietnamienne</em>.
        Aujourd’hui encore, je retrouve cette ambiance unique en préparant
        des plats typiques comme le <strong>bánh canh chả cá</strong> (soupe
        de nouilles au poisson) ou le <strong>bánh xèo</strong>, crêpe fine
        et croustillante garnie de crevettes et d’herbes fraîches.';
        $ret.= '</p>';
        
        $ret.= '<p>';
        $ret.= 'Si vous voulez en savoir plus, découvrez d’autres recettes et
        anecdotes sur <a href="https://inhatrang.kaiiros.fr/" target="_blank" rel="noopener">
        inhatrang.kaiiros.fr</a>. C’est pour moi l’occasion de
        partager cette culture culinaire qui allie tradition, saveurs et convivialité.';
        $ret.= '</p>';
        //</section>
          
        return $ret;
    }
}
?>