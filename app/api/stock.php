<?php
function stock(&$weixin,&$type){
  $keyword=$weixin->keyword;
  $url = "http://api2.sinaapp.com/search/stock/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword=".$keyword; 
  $stockJson = file_get_contents($url); 
  $stockInfo = json_decode($stockJson, true); 
  $contentStr = $stockInfo['text']['content']; 
  if(d()){
    echo "<pre>url:$url\nstockJson:$stockJson\ncontentStr:$contentStr</pre>";
  }
  $type="text";
  $strModel=new StrModel(&$weixin);
  $strModel->setContent($contentStr);
  return StrView::fromModel($strModel);
}
?>
