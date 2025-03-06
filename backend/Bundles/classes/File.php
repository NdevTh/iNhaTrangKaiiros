<?php
namespace Bundles\classes;
//use Bundles\classes\File as  BundlesFile;
//use strip_tags;
class  File
{
    public function __construct(){}
    public static function putContent($filename,$content="",$path="")
    {
        $file = 'template.php';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.php';
        }
        /*
        if (!file_exists($file)){
            //echo 'File ' . $file .' doesn\'t exist.';
            return;
        }*/
        //shell_exec("touch ".$file);
        if (!file_exists($filename)) {
            //echo "The directory $path exists.";
        //} else {
            mkdir($path, 0777);
            //echo "The directory $path was successfully created.";
            //exit;
        }
        
        if(!is_file($file)){
            $contents = $content;           // Some simple example content.
            file_put_contents($file, $contents);     // Save our content to the file.
        } else {
            /*
            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new person to the file
            $current .= $content;
            // Write the contents back to the file
            file_put_contents($file, $current);
            */
        }
    }
    public static function write($filename,$txt)
    {
        $myfile = fopen($filename, "w") or die("Unable to open file!");
        //$txt = "John Doe\n";
        fwrite($myfile, $txt);
        //$txt = "Jane Doe\n";
        //fwrite($myfile, $txt);
        fclose($myfile);
    }
    
    public static function read($filename,$path="",$ext="")
    {
        /* check if file exist */
        if (strpos($filename,'.') > 0 && !empty($ext))
        {
            $ext = ''; //substr($filename,-4);
        } elseif (strpos($filename,'.') == 0 && empty($ext))
        {
            $ext = '.txt';
        } else {
            //$ext = ''; //substr($filename,-4);
        }
        $file = $filename.$ext;
        if (!empty($path))
        {
            $file = $path.'/'.$filename.$ext;
        }
        //echo strpos($filename,'.') . ' File : '.$file;
        if (!file_exists($file)){
            return;
        }
        $myfile = fopen($file, "r") or die("Unable to open file!");
        //return fgets($myfile);
        while(!feof($myfile)) {
            //$str .= strip_tag(fgets($myfile)) . "<br>";
            $str .= fgets($myfile);
        }
        fclose($myfile);
        return $str;
    }
}
?>