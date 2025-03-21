<?php
    // P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
    header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
    require_once('init.php');
    use classes\WebCore as RTTCore;
?>
<!DOCTYPE html>
<html lang="<?php echo $iso; ?>">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iNhatrang &trade;</title>
  <?php
   try {
       $nbV = rand();
       RTTCore::includeCSS(array(_THEME_DIR_.'/assets/css/front.css?v'.$nbV));
       //RTTCore::includeJS(array('assets/js/cart.js?v'.$nbV));
   } catch(Error $e) {
       echo $e->getMessage();
   }
   ?>
</head>
<body>