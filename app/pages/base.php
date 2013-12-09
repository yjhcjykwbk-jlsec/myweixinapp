<?php
class Model{
  public $createTime;
  public $fromUserName;
  public $toUserName;
  public $funcFlag;
  function __construct(&$weixin){
    $this->createTime=$weixin->createTime;
    $this->fromUserName=$weixin->fromUserName;
    $this->toUserName=$weixin->toUserName;
    $this->funcFlag=$weixin->funcFlag;
  }
  function setContent($content){
  }
}
class View{
  static  function fromModel($model){
    return "";
  } 
}
?>
