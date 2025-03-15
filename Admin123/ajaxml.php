<?php
include('config/config.inc.php');
use Bundles\classes\XMLFile as  XMLFile;
// Read POST data
$data = json_decode(file_get_contents("php://input"));
$filename = $data->table;
$fields = $data->fields;
$values = $data->values;
$whereClause = $data->whereclause; //"[@id=1]";

$query = '//'.$filename.'/'.substr($filename,0,strlen($filename)-1).'';
//$whereClause = '[@id="'.Tools::getValue('id').'"]';
$updateFields = array();
$ind = 0;
foreach($values as $value)
{
    $updateFields[$fields[$ind]] = $value;
    $ind++;
}
XMLFile::update($filename,$whereClause,$updateFields,$query,"../Bundles/xml");
header('Content-Type: application/json');
echo json_encode($updateFields, JSON_PRETTY_PRINT);
exit();
?>