<?php

if(isset($_FILES['file']['name'])){
    // file name
    $filename = $_FILES['file']['name'];
    $loc = 'equ';
    if (isset($_POST['loc'])){
       $loc = $_POST['loc'];
    }
    $dir = '../images/../'.$loc;
    if (!file_exists($dir)) {
       mkdir($dir, 0777, true);
    }
    // Location
    $location = $dir.'/'.$filename;
    // file extension
    $file_extension = pathinfo($location, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);

    // Valid extensions
    $valid_ext = array("ppt","pptx","xls","xlsx","pdf","doc","docx","jpg","png","jpeg","webp");

    $response = 0;
    if(in_array($file_extension,$valid_ext)){
        // Upload file
        if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
             $response = 1;
        } 
    }

    //echo $response . ' location: ' . $_POST['loc'];
    echo $response;
	exit;
}