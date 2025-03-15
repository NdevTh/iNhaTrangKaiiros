<?php
    // P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
    header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
    require_once('init.php');
    //echo $lang;
    use Bundles\classes\WebCore as WebCore;
?>
<!DOCTYPE html>

<html lang="km">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />

  <title>eShop&trade; | Home </title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css?v1" />
  <?php
   try {
       $nbV = rand();
       WebCore::includeCSS(array('css/style.css?v'.$nbV,'css/zingchart.min.css?v'.$nbV,'css/boxicons2.1.4.css?v1'.$nbV,'css/boxicons2.1.4.min.css?v'.$nbV));
       WebCore::includeJS(array('js/zingchart.min.js?v'.$nbV,'js/table.js?v'.$nbV));
   } catch(Error $e) {
       echo $e->getMessage();
   }
   ?>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>