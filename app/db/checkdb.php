<?php
$db=new SaeMysql();
// $sql="insert into requests(request) values ('this is test');";
// $db->runSql($sql);
$sql="select * from requests";
echo "<pre>";
echo $sql."\n";
$result=$db->getData($sql);
var_dump($result);
echo "</pre>";

$sql="select * from xml";
echo "<pre>";
echo $sql."\n";
$result=$db->getData($sql);
var_dump($result);
echo "</pre>";

if(isset($_REQUEST['clear'])){
  $db->runSql("delete from requests");
  $db->runSql("delete from xml");
}
?>
