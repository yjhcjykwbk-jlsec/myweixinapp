<?php
function curlpost($curlPost,$url) //curl post 函数
{
  $ch = curl_init();//初始化curl  
  curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页  
  curl_setopt($ch, CURLOPT_HEADER, 0);//设置header  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上  
  curl_setopt($ch, CURLOPT_POST, 1);//post提交方式  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);  
  $data = curl_exec($ch);//运行curl  
  curl_close($ch);  
  return $data;
}
function media($content) //多媒体转换
{
  if(strstr($content,'murl')){//音乐
    $a=array();
    foreach (explode('#',$content) as $content)
    {
      list($k,$v)=explode('|',$content);
      $a[$k]=$v;
    }
    $content = $a;
  }              
  elseif(strstr($content,'pic'))//多图文回复
  {
    $a=array();
    $b=array();
    $c=array();
    $n=0;
    $contents = $content;
    foreach (explode('@t',$content) as $b[$n])
    {
      if(strstr($contents,'@t'))
      {
        $b[$n] = str_replace("itle","title",$b[$n]);
        $b[$n] = str_replace("ttitle","title",$b[$n]);
      }

      foreach (explode('#',$b[$n]) as $content)
      {
        list($k,$v)=explode('|',$content);
        $a[$k]=$v;
        $d.= $k;
      }
      $c[$n] = $a;
      $n++;

    }
    $content = $c ;
  }
  return $content;
}



function xiaojo(&$weixin,&$type) //小九接口函数，该函数可通用于其他程序
{
  $key=$weixin->keyword;
  $from=$weixin->fromUserName;
  $to=$weixin->toUserName;

  global $yourdb,$yourpw;
  $key=urlencode($key);
  $yourdb=urlencode($yourdb);
  $from=urlencode($from);
  $to=urlencode($to);
  $post="chat=".$key."&db=".$yourdb."&pw=".$yourpw."&from=".$from."&to=".$to;
  $api = "http://www.xiaojo.com/api5.php";
  $replys = curlpost($post,$api);
  $reply = media(urldecode( $replys));//多媒体转换

  $type="media";
  return $reply;
}
?>
