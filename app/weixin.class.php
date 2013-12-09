<?php
include "pages/base.php";
include "pages/news.php";
include "pages/music.php";
include "pages/str.php";
include "api/stock.php";
include "api/common.php";
include "api/music.php";
include "api/simSimi.php";
include "user.php";
$user="";
class Weixin
{
  public $token = "";
  public $log=true;
  public $setFlag = false;
  public $funcFlag = 0;
  public $msgtype = 'text';   //('text','image','location')
  public $msg = array();
  public $fromUserName="";
  public $toUserName="";
  public $keyword="";
  public $createTime;
  public function __construct($token)
  {
    $this->token = $token;
    $this->createTime=time();
  }
  //获得用户发过来的消息（消息内容和消息类型  ）
  public function parseMsg()
  {
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    if (!empty($postStr)) {
      $this->msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->msgtype = strtolower($this->msg['MsgType']);
      //用SimpleXML解析POST过来的XML数据
      $this->fromUserName = $this->msg["FromUserName"]; //获取发送方帐号（OpenID）
      $this->toUserName = $this->msg["ToUserName"]; //获取接收方账号
      $this->keyword = trim($this->msg["Content"]); //获取消息内容
      if(d()) {
        echo "---\nkeyword:".t($this->keyword)." fromUserName:".t($this->fromUsername)." msg:".t($this->msg)."---\n";
      }
      if($this->keyword=="") $this->keyword="empty str"; 
      global $user;
      $user=new User($this->fromUserName); 
    }
    else exit();
  }
  //route路由，调用相应api,返回不同内容
  private function route(){
    $keyword=$this->keyword;
    // $pattern = '/' ."股票". '/'; 
    //股票
    if(strpos($keyword,"股票")!==false){
      return "stock";
      //新闻
    }else if(strpos($keyword,"新闻")!==false){
      return "news";
    }else if(strpos($keyword,"歌曲")!==false||strpos($keyword,"歌曲")!==false){
      return "music";
    }
    else {
      if(u()->lastTransact){
        return u()->lastTransact;
      }
      else {
     //小黄鸭
        return "simSimi";
      }
    }
  }
  public function makeText($text){
    $strModel=new StrModel(&$this);
    $strModel->setContent($text);
    return StrView::fromModel($strModel);
  }
  //使用api(keyword)回复用户
  public function respondMsg(){
    $api=$this->route($this->keyword);
    $type="news";
    //获取返回信息
    $result=call_user_func($api,&$this,&$type);
    //打印提示信息
    if (d()) {
      echo "---\ntype:$type api:$api "."---\n";
    }
    global $user;
    ds(array("user"=>$user));
    //返回给用户
    switch($type){
    case("text"):
      $result=($result==""?"未找到有效关键词,请尝试输入 新闻 或 股票 获取信息":$result);
      echo $result;
      break;
    case("news"):
      echo $result;
      break;
    }
    //记录本次会话
    if ($this->log){
      db()->logRequest(t($_REQUEST),t($this->msg),t($result));
    }
  }
  public function valid()
  {
    if ($this->checkSignature()) {
      if( $_SERVER['REQUEST_METHOD']=='GET' )
      {
        echo $_GET['echostr'];
        db()->writeLog("valid request succeed");
        exit;
      }
    }
    else{
      db()->writeLog("valid request failed");
      exit;
    }
  }
  private function checkSignature()
  {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];    

    $token = $this->token;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );

    if(d()){
      echo "---\nrequest:".t($_REQUEST)." signature:$signature tmpstr:$tmpStr ---\n";
    }
    if( $tmpStr == $signature ){
      return true;
    }else{
      return false;
    }
  }
}
?>
