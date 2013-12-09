<?php
/*
{
    "errcode": 0,
    "msgtype": "music",
    "music": {
        "title": "歌曲名称",
        "description": "music",
        "musicurl": "音乐地址",
        "hqmusicurl": "音乐地址"
    }
}
{
    "errcode": "1",
    "msgtype": "text",
    "text": {
        "content": "检索音乐失败！"
    }
}
 */
class MusicModel extends Model{
  var $musicData;
  function __construct(&$weixin){
    parent::__construct(&$weixin);
    $this->musicData=array();
  }
  function addMusicItem($title,$desp,$murl,$hqurl){
    array_push($this->musicData,
      array("title"=>$title,
      "murl"=>$murl,"hqurl"=>$hqurl, 
      "description"=>$desp)
    );
  }
}
class MusicView extends View{
  static public function fromModel($musicModel)//$arr_item)
  {
    $createTime=$musicModel->createTime;
    $fromUserName=$musicModel->fromUserName;
    $toUserName=$musicModel->toUserName;
    $funcFlag=$musicModel->funcFlag;
    $itemTpl ="<Title><![CDATA[%s]]></Title>
      <Description><![CDATA[%s]]></Description>
      <MusicUrl><![CDATA[%s]]></MusicUrl> 
      <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>";
    $arr_item = $musicModel->musicData;
    $item_str = "";
    foreach ($arr_item as $item)
      $item_str .= sprintf($itemTpl, $item['title'], $item['description'],
        $item['murl'], $item['hqurl']);
    return 
      "<xml>
      <ToUserName><![CDATA[{$toUserName}]]></ToUserName>
      <FromUserName><![CDATA[{$fromUserName}]]></FromUserName>
      <CreateTime>{$createTime}</CreateTime>
      <MsgType><![CDATA[music]]></MsgType>
      <Music>
    {$item_str}
    </Music>
    <FuncFlag>{$funcFlag}</FuncFlag>
    </xml>";
  }
  static public function sample(&$weixin){
    $musicUrl="http://stream18.qqmusic.qq.com/32462232.mp3";
    return "<xml>
      <ToUserName><![CDATA[$weixin->toUserName]]></ToUserName>
      <FromUserName><![CDATA[$weixin->fromUserName]]></FromUserName>
      <CreateTime>$weixin->createTime</CreateTime>
      <MsgType><![CDATA[music]]></MsgType>
      <Music>
      <Title><![CDATA[for the love]]></Title>
      <Description><![CDATA[whitney houston]]></Description>
      <MusicUrl><![CDATA[$musicUrl]]></MusicUrl>
      <HQMusicUrl><![CDATA[$musicUrl]]></HQMusicUrl>
      </Music>
      <FuncFlag>0</FuncFlag>
      </xml>";
  }
}
?>
