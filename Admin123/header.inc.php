<?php
    // P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
    header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
    require_once('init.php');
    //echo $lang;
    use Bundles\classes\WebCore as RTTCore;
    global $core,$iso;
    //var_dump($core);
    //echo $core->nav;
    //$_POST['nav'] = $core->nav;
?>
<!DOCTYPE html>
<html lang="<?php isset($iso)?$iso:'fr'; ?>">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- charset="ISO-8859-1" -->
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <?php
   try {
       $nbV = rand();
       RTTCore::includeCSS(array('css/admin.css?v'.$nbV,'css/zingchart.min.css?v'.$nbV,'css/boxicons2.1.4.css?v1'.$nbV,'css/boxicons2.1.4.min.css?v'.$nbV,'css/chat.css?v'.$nbV,'css/selectflag.css?v'.$nbV));
       RTTCore::includeJS(array('js/zingchart.min.js?v'.$nbV,'js/table.js?v'.$nbV,'js/clock.js?v'.$nbV));
   } catch(Error $e) {
       echo $e->getMessage();
   }
   ?>
</head>
<script type="text/javascript"> 
 var nav = "<?php echo !empty($core)?$core->nav:''; ?>";
 
</script> 