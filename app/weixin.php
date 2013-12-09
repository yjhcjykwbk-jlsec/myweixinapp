<?php
/**
 * wechat php test
 */
// function write_log(){
// $file=$_SERVER['DOCUMENT_ROOT'].'/requests.log';
// $fp = fopen($file,"a");
// if(!$fp){
// echo "system error";
// }else {
// $results = json_encode($_REQUEST);
// fwrite($fp,$results);
// fwrite($fp,"\n");
// fclose($fp);
// }
// }
include "lib/functions.php";
include "weixin.class.php";
include "db/db.php";
$db=new MyDB();
$weixin=new Weixin('weixinapp');
if(isset($_GET["echostr"])){
  $db->writeLog("valid request:".t($_REQUEST));
  $weixin->valid();
}
else {
  $weixin->parseMsg();
  $weixin->respondMsg();
}
?>
