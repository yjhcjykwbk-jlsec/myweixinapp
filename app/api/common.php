<?php
//common used php api
function news(&$weixin,&$type){
  $type="news";
  $CreateTime = time();
  $FuncFlag = $weixin->setFlag ? 1 : 0;
  if(d()) echo "---\nweixin:".t($weixin)."--\n";
  $newsModel=new NewsModel(&$weixin);
  $newsModel->sample();
  return NewsView::fromModel($newsModel);
}
?>
