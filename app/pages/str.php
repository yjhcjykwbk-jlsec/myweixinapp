<?php
class StrModel extends Model{
  var $strData;
  function __construct(&$weixin){
    parent::__construct(&$weixin);
    $this->strData="";
  }
  function setContent($str){
    $this->strData=$str;
  }
}
class StrView extends View{
  static function fromModel($strModel){
    $createTime = $strModel->createTime;
    $text=$strModel->strData;
    $funcFlag = $strModel->setFlag ? 1 : 0;
    $textTpl = "<xml>
      <ToUserName><![CDATA[{$strModel->fromUserName}]]></ToUserName>
      <FromUserName><![CDATA[{$strModel->toUserName}]]></FromUserName>
      <CreateTime>{$createTime}</CreateTime>
      <MsgType><![CDATA[text]]></MsgType>
      <Content><![CDATA[%s]]></Content>
      <FuncFlag>%s</FuncFlag>
      </xml>";
    return sprintf($textTpl,$text,$funcFlag);
  }
}
?>
