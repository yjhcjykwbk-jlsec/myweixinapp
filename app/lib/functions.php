<?php
//convert anything to string no matter what type it is
function t($myVar){
  return print_r($myVar,true);
}
function d(){
  global $debug;
  return $debug;
}
function ds($arr){
  if(d()){
    echo "\n---\n";
    foreach($arr as $key=>$value){
      echo "$key:".t($value);
    }
    echo "\n---\n";
  }
}
function c($url,$type,$debug=false){
  $ch = curl_init();
  $timeout = 5000;
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $content = curl_exec($ch);
  curl_close($ch);
  // $content=file_get_contents($url);
  if($debug) ds(array("url"=>$url,"curl"=>$content));
  switch($type){
  case "json":
    return json_decode($content);
  case "xml":
      return (array)simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
  }
}
function db(){
  global $db;
  return $db;
}
function u(){
  global $user;
  return $user;
}
function decode($str) {
  return html_entity_decode(preg_replace("/.u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str)), null, 'UTF-8');
}
?>
