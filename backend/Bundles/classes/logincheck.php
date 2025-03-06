<?php 
    session_start();
    include("include/configurationadmin.php");
    //include_once('../include/classes/config.inc.php');
    
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $sql  = mysqli_query($conn,"select * from ".$sufix."admin where username='".$username."'") ;
    

// HERE HOW TO LOG ACTION

    $log = new log("Logging in attempt from $username" , $username ,'Login Attempt' );
    $log->createAction();
    
//SIMPLE AND COOL RIGHT?

    if(mysqli_num_rows($sql) > 0)
    {
        $rows = mysqli_fetch_assoc($sql);
        if(md5($password) == $rows['password']) {
            $_SESSION['id'] = $rows['id'];
            $_SESSION['username'] = $rows['username'];
            $_SESSION['usertype'] = $rows['type'];
            mysqli_query($conn,"update ".$sufix."admin set lastlogin='".date('Y-m-d')."' where id = '".$rows['id']."' and username='".$rows['username']."'") ;
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie('rrdssrdda', $rows['id'], time()+120, '/', $domain, false);
            header("Location: http://localhost/test/admin-new/dashboard");
            exit();
        } else {
            $_SESSION['message']="<div class='alert alert-danger' role='alert'>Invalid userid/password!</div>";
            header("Location: http://localhost/test/admin-new/");
            exit();
    
        }
    
    } else { 
        $_SESSION['message']="<div class='alert alert-danger' role='alert'>Invalid userid/password!</div>";
        header("Location: http://localhost/test/admin-new/");
        exit();
       
   } 
?>
