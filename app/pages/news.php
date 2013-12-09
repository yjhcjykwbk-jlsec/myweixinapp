<?php
class NewsModel extends Model{
  // var $createTime;
  // var $fromUserName;
  // var $toUserName;
  var $newsData;
  function __construct(&$weixin){
    parent::__construct(&$weixin);
    // $this->createTime=$createTime;
    // $this->fromUserName=$fromUserName;
    // $this->toUserName=$toUserName;
    $this->newsData=array("items"=>array());
  }
  function addNewsItem($title,$desp,$picurl,$url){
    array_push($this->newsData["items"],
      array("title"=>$title,
      "picurl"=>$picurl,"url"=>$url, 
      "description"=>$desp)
    );
  }
  function setContent($content){
    $this->newsData["content"]=$content;
  }
  function toStr(){
    return json_encode($this->newsData);
  }
  public function sample(){
    $this->addNewsItem(
      "这是主题",
      "摘要..",
      "https://wx2.qq.com/cgi-bin/mmwebwx-bin/webwxgeticon?seq=620512589&username=gh_5b9bdaf61260",
      "http://news.qq.com/a/20131207/005239.htm"
    );
    $this->addNewsItem(
      "北京占地50亩别墅群烂尾20年",
      "至今无人..",
      "http://pnewsapp.tc.qq.com/newsapp_ls/0/11809548_640320/",
      "http://news.qq.com/a/20131207/005239.htm"
    );
    $this->addNewsItem(
      "美日战机实弹射击场搬迁 疑因紧邻中国防空识别区",
      "详细内容..",
      "http://pnewsapp.tc.qq.com/newsapp_ls/0/11807398_8080/",
      "http://news.qq.com/a/20131207/001754.htm"
    );
    $this->addNewsItem(
      "网上商城",
      "详细内容请进入..",
      "http://pnewsapp.tc.qq.com/newsapp_ls/0/11807398_8080/",
      "http://1.nanyancampus.sinaapp.com/app/subapp/gl.htm"
    );
    $this->setContent("欢迎光临主页");
  }
  public function toArr(){
    return $this->newsData;
  }
}
class newsView extends View{
  static function fromModel($newsModel){
    $createTime=$newsModel->createTime;
    $fromUserName=$newsModel->fromUserName;
    $toUserName=$newsModel->toUserName;
    $newsData=$newsModel->newsData;
    $funcFlag=$newsModel->funcFlag;

    $newTplHeader = "<xml>
      <ToUserName><![CDATA[{$fromUserName}]]></ToUserName>
      <FromUserName><![CDATA[{$toUserName}]]></FromUserName>
      <CreateTime>{$createTime}</CreateTime>
      <MsgType><![CDATA[news]]></MsgType>
      <Content><![CDATA[%s]]></Content>
      <ArticleCount>%s</ArticleCount><Articles>";
    $newTplItem = "<item>
      <Title><![CDATA[%s]]></Title>
      <Description><![CDATA[%s]]></Description>
      <PicUrl><![CDATA[%s]]></PicUrl>
      <Url><![CDATA[%s]]></Url>
      </item>";
    $newTplFoot = "</Articles>
      <FuncFlag>%s</FuncFlag>
      </xml>";
    $content = '';
    $itemsCount = count($newsData['items']);
    $itemsCount = $itemsCount < 10 ? $itemsCount : 10;//微信公众平台图文回复的消息一次最多10条
    if ($itemsCount) {
      foreach ($newsData['items'] as $key => $item) {
        if ($key<=9) {
          $content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
        }
      }
    }
    $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
    $footer = sprintf($newTplFoot,$funcFlag);
    return $header . $content . $footer;
  }
}
?>
