<?php
include('config/config.inc.php');
include('function.php');
use Bundles\classes\WebCore as RTTCore;
use classes\Cookie as Cookie;
use classes\Tools as Tools;
use classes\myUser as myUser;
myUser::init();
$messages = array();

$errors = array();
$iso = 'en';
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/errors.php');
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/fields.php');
include('../'._RTT_TRANSLATIONS_DIR_.$iso.'/admin.php');

/* Cookie creation and redirection */
if (Tools::isSubmit('Submit'))
{
    $records = myUser::getRecords();
    //var_dump($records);
    if (!$records)
    {
        Tools::redirect('install.php');
    }
    
    //echo 'Submit';
    $email = Tools::getValue('email');
    $password = Tools::encrypt(Tools::getValue('pwd'));
    //echo '<br/>user: ' . $email . ' pwd: ' .md5($password);
    $users = !empty(myUser::getByEmail($email,$password))?myUser::getByEmail($email,$password):array();
    //var_dump($user);
    /*
    if (strtolower($user["level"]) == 'owner' OR strtolower($user["level"]) == 'employee'){
        // Set cookie value
        $cookie->setValue($user);
        // Set cookie expiration time
        $cookie->setTime("+1 hour");
        // Create the cookie
        $cookie->create();
    }*/
    //var_dump($users);
    if($users){
        //echo '<br/>Login';
        $_POST['status']     = 1;
        $_POST['date_upd']   = $currentDate;
        
        $fields = array('status','date_upd');
        myUser::updateByFields($users[0]['id_user'],$fields);
        
        $cookie = $user;
        $classCookie = new Cookie();
        // Set cookie name
        $classCookie->setName(Tools::getToken());

        // Set cookie value
        $classCookie->setValue($users);
        // Set cookie expiration time
        $classCookie->setTime("+1 hour");
        // Create the cookie
        $classCookie->create();
        $defaultTab = 'CusDashboard';
        if ($users[0]['system_role'] === '1')
        {
            $defaultTab = 'Home';
        }
        Tools::redirect("index.php?t=".$defaultTab."&pt=".$defaultTab."&token=".Tools::getToken());
    }else {
        $messages["login"] = "Hop...! Something went wrong";
    }
    
}

?>
<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="<?php isset($iso)?$iso:'fr'; ?>" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>eShop&trade; | RithyThidaTévy</title> 
    <link rel="stylesheet" href="css/style.css?v2">
    <link rel="stylesheet" href="css/boxicons2.1.4.css?v12"/>
  </head>
  <body>
    <div class="container">
      
      <div class="wrapper">
        <div class="title"><span>RithyThidaTévy</span></div>
        <form action="<?php $_SERVER['REQUEST_URI'] ?>" methode="post">
          <div class="row">
            <i class="bx bxs-user"></i>
            <input name="email" type="text" placeholder="<?php echo RTTCore::l('Username'); ?>" required>
          </div>
          <div class="row">
            <i class="bx bxs-lock"></i>
            <input name="pwd" type="password" placeholder="<?php echo RTTCore::l('Password'); ?>" required>
          </div>
          <!-- div class="pass"><a href="#">Forgot password?</a></div -->
          <div class="row button">
            <input name="Submit" type="submit" value="<?php echo RTTCore::l('Login'); ?>">
          </div>
          <!-- div class="signup-link">Not a member? <a href="#">Signup now</a></div-->
            <?php
        /* Start Message */
            //var_dump($messages);
        if ($messages){
            echo  '<div class="msg border-'.$theme.'">';
            foreach($messages as $message){
                echo $message;
            }
            echo '</div>';
        }
      ?>
        </form>
           
      </div>
    </div>
  </body>
</html>