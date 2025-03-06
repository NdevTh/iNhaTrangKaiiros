<?php
namespace Bundles\classes;
//use Bundles\classes\XMLFile as  BundlesXMLFile;
use Bundles\classes\WebCore as  RTTCore;
use DOMDocument;
use DOMAttr;

class XMLFile
{
    public static function deleteById($filename,$Id,$path="")
    {
        
        /* check if file exist */
        $file = $filename.'.xml';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.xml';
        }
        if (!file_exists($file)){
            return;
        }
        $xml = simplexml_load_file($file);
        if(!$xml)
        {
            exit;
        }
        $list = $xml->children();
        for ($i = 0; $i < count($list); $i++)
        {
            if ($list[$i]->attributes()->id == $Id){
                unset($xml->children()[$i]);
            }
        }
        $xml->asXML($file);
        
    }
    
    public static function getXMLByWhere($filename,$query='',$whereClause='',$path=''){
        /* check if file exist */
        $file = $filename.'.xml';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.xml';
        }
        
        if (!file_exists($file)){
            RTTCore::initTheme();
            //echo 'File ' . $file .' doesn\'t exist';
            return;
        }
        //echo 'File '.$file;
        $xml = simplexml_load_file($file);
        if(!$xml)
        {
            exit;
        }
        //echo 'Query Where: ' . $query.$whereClause;
        $objs = $xml->xpath($query.$whereClause);
        $fields = self::getFields($filename,$path);
        //var_dump($fields);
        $arr = array();
        foreach($objs as $obj) {
            $row = array();
            foreach($fields as $field){
                //echo "{$field} : {$hook->$field} ";
                $row[$field] = "{$obj->$field}";
                if (!empty("{$obj->attributes()->id}")){
                    $row['id'] = "{$obj->attributes()->id}";
                }
            }
            //echo '<br/><br/>';
            array_push($arr,$row);
        }
        //echo "<br />";
        //var_dump($arr);
        return $arr;
    }
    
    public static function getList($key, $filename,$path=""){
        /* check if file exist */
        try{
            //check file is exist or not
            $file = $filename.'.xml';
            if (!empty($path))
            {
                $file = $path.'/'.$filename.'.xml';
            }
            
            if (!file_exists($file)){
                //echo 'File ' . $file .' doesn\'t exist.';
                return;
            }
            
            //check node name is empty
            if (empty($childname))
            {
                $childname = $filename;
            }
            //echo 'File ' .$file.'<br>';
            $xml = simplexml_load_file($file);
            //$xml = simplexml_load_file('admin/db/xml/'.$hook_name.'.xml');
            $list = $xml->children();
            
            //$output = '';
            $fields = self::getFields($filename,$path);
            //var_dump($fields);
            $arr = array();
            if (!empty($fields))
            {
                for ($i = 0; $i < count($list); $i++)
                {
                    foreach ($fields as $k =>$v){
                        if ($v == $key){
                            array_push($arr,$list[$i]->{$v});
                        }
                        //$arr[$i][$v] = $list[$i]->{$v};
                        //echo $list[$i]->{$v} .'<br/>';
                    }
                }
            }
            //return $output;
            //var_dump($arr);
            return $arr;
        } catch (Error $e){
            return $e->getMessage();
        }
    }
    public static function readLast($filename, $childname="", $path="")
    {
        try{
            //check file is exist or not
            $file = $filename.'.xml';
            if (!empty($path))
            {
                $file = $path.'/'.$filename.'.xml';
            }
            //echo ' File ' .$file;
            if (!file_exists($file)){
                //echo 'File ' . $file .' doesn\'t exist.';
                return;
            }
            
            //check node name is empty
            if (empty($childname))
            {
                $childname = $filename;
            }
            //read data from XML file
            $xml = simplexml_load_file($file);
            $records = $xml->xpath('//'.$childname.'/'.substr($childname,0,strlen($childname)-1).'[last()]');
            //var_dump($xml);
            return $records;
        } catch (Error $e){
            return $e->getMessage();
        }
        
    }
    
    public static function readFile($filename, $childname="", $path="")
    {
        try{
            //check file is exist or not
            $file = $filename.'.xml';
            if (!empty($path))
            {
                $file = $path.'/'.$filename.'.xml';
            }
            //echo ' File ' .$file;
            if (!file_exists($file)){
                //echo 'File ' . $file .' doesn\'t exist.';
                return;
            }
            
            //check node name is empty
            if (empty($childname))
            {
                $childname = $filename;
            }
            //read data from XML file
            $xml = simplexml_load_file($file);
            //var_dump($xml);
            $list = $xml->children();
            //var_dump($list);
            $fields = self::getFields($filename,$path);
            //var_dump($fields);
            $records = array();
            for ($i = 0; $i < count($list); $i++)
            {
                /*
                if (!empty($list[$i]->attributes()->id)){
                    //echo $list[$i]->attributes()->id .'<br/>';
                    $arr[$i]['id'] = $list[$i]->attributes()->id;
                }
                foreach ($fields as $k =>$v){
                    if ($v != 'id'){
                        $arr[$i][$v] = $list[$i]->{$v};
                    }
                    //echo $list[$i]->{$v} .'<br/>'
                }
                */
                //$record = array();
                foreach ($fields as $k =>$v){
                    if (!empty($list[$i]->attributes()->id)){
                        //echo $list[$i]->attributes()->id .'<br/>';
                        $records[$i]['id'] = $list[$i]->attributes()->id;
                    }
                    if ($v != 'id'){
                        $records[$i][$v] = $list[$i]->{$v};
                    }
                }
                //array_push($records,$record);
            }
            //var_dump($arr);
            return $records;
        } catch (Error $e){
            return $e->getMessage();
        }
        
    }
    
    public static function getFields($filename, $path="")
    {
        //declaration variables
        $fieldsName = array();
		
        /* check if file exist */
        $file = $filename.'.xml';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.xml';
        }
        if (!file_exists($file))
        {
            //echo 'File ' . $file .' doesn\'t exist.';
            return;
        }
        $xml = simplexml_load_file($file);
		if(!$xml)
		{
			exit;
		}
        //$xml = simplexml_load_file('admin/db/xml/employees.xml');
        //var_dump($xml);
        /*
        if (!empty($xml->children()->attributes()->id)){
            array_push($fieldsName,'id');
        }*/
        if(!empty($xml->children())){
            foreach($xml->children()->children() as $child)
            {
                array_push($fieldsName,$child->getName());
            }
        }
        //var_dump($fieldsName);
        return $fieldsName;
    }
    
    
    public static function update($filename,$whereClause='',$updateFields=array(),$query='',$path="")
    {
        $id = 0;
        /* check if file exist */
        $file = $filename.'.xml';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.xml';
        }
        
        if (!file_exists($file)){
            return;
        }
        $xml = simplexml_load_file($file);
		if(!$xml)
		{
			exit;
		}
		
        //echo 'Query Where: ' . $query.$whereClause;
        $objs = $xml->xpath($query.$whereClause);
        //var_dump($objs);
        $id = !empty($objs)?$objs[0]->attributes()->id:0;
        if ($updateFields)
        {
            foreach( $updateFields as $key => $val)
            {
                $objs[0]->{$key} = $val;
            }
        }
        //$objs[0]->{$editField} = $editValue;
        $xml->asXML($file);
        return $id;
    }
    
    public static function writeFile($filename,$fieldsRequired=array(),$parentNode="",$path="")
    {
        $id = 1;
        /* check if file exist */
        /* check if file exist */
        $file = $filename.'.xml';
        if (!empty($path))
        {
            $file = $path.'/'.$filename.'.xml';
        }
        
        if (!file_exists($file)){
            redo:
            /* Create New File */
            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->xmlVersion = '1.0';
            $dom->formatOutput = true;//$xml_file_name = $hook_name.'.xml';
            $root = $dom->createElement($filename);

            if ($fieldsRequired){
                if (empty($parentNode))
                {
                    $parentNode = substr($filename,0,strlen($filename)-1);
                }
                $parent_node = $dom->createElement($parentNode);
                foreach($fieldsRequired as $field){
                    //var_dump($field);
                    //$record = $dom->createElement(substr($hook_name,0,strlen($hook_name)-1));
                    if (strtolower($field) == 'id'){
                        $attr_id = new DOMAttr('id', '1');
                        $parent_node->setAttributeNode($attr_id);
                    } elseif(strtolower($field) == 'password') {
                        $child_node = $dom->createElement($field, md5(Tools::getValue($field)));
                        $parent_node->appendChild($child_node);
                    } else {
                        $child_node = $dom->createElement($field, Tools::getValue($field));
                        $parent_node->appendChild($child_node);
                    }
                    //$obj->appendChild($record);
                }
                $root->appendChild($parent_node);
            
            }
            $dom->appendChild($root);
            $dom->save($file);
            return $id;
        } else {
            $xml = simplexml_load_file($file);
            $fields = self::getFields($filename,$path);
            if(!empty($xml->children()))
            {
                $list = $xml->children();
                //var_dump($list);
                $id = $list[(count($list)-1)]->attributes()->id;
                //echo 'Fields of ' .$filename.': <br/> ';var_dump($fields);
                
                $id = (1 + (int)$id);
                $node = $xml->addChild($xml->children()->getName());
                //$id = (1 + (int)$id);
                $node->addAttribute('id', $id);
                foreach($fields as $field)
                {
                    if ($field != 'id' && $field != 'datetime' && strtolower($field) != 'password')
                    {
                        $node->addChild($field, Tools::getValue($field) );
                        //$node->appendChild($child);
                    }
                    if ($field == 'date_add')
                    {
                        $node->addChild($field,date("Y-m-d"));
                    }
                    if ($field == 'datetime')
                    {
                        $node->addChild($field,date("d-m-Y h:i:s"));
                    }
                    if (strtolower($field) == 'password')
                    {
                        $node->addChild($field,md5(Tools::getValue($field)));
                    }
                }
            } else {
                unlink($file);
                goto redo;
            }
            $xml->asXML($file);
        }
        return $id;
    }
    
}