<?php
function simsimiHttp($keyword)
{
  $JSESSIONID = 'JSESSIONID=B86064A18BB84AB21DF283963671E42C';
  $AWSELB = 'AWSELB=15E16D030EBAAAB8ACFf4BD9BB7E0CA8FB501388662941563CCCE3FBA00C1966E7EFC7E79C02
    70B337A9EB2DC66B3E19A07708673470FDFA0B2C01AB735E6CC2ABE3DC5F3AF';

  //模拟http报文发送消息
  $ch = curl_init('http://www.simsimi.com/func/req?lc=ch&msg='.$keyword);
  $header = array("Accept: application/json, text/javascript, */*; q=0.01",
    "X-Requested-With: XMLHttpRequest",
    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko)
    Chrome/27.0.1453.116 Safari/537.36",
    "Content-Type: application/json; charset=utf-8",
    "Referer: http://www.simsimi.com/talk.htm",
    "Accept-Encoding: gzip,deflate,sdch",
    "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3",
    "Cookie: sagree=true; ".$AWSELB."; selected_nc=ch; ".$JSESSIONID,
    "Connection: keep-alive"
  );

  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $data = curl_exec($ch);
  curl_close($ch);
  // $url='http://www.simsimi.com/func/req?lc=ch&msg='.$keyword;
  // $ch= new SaeFetchurl();
  // $data = $ch->fetch($url);
  // if($ch->errno()!=0) echo $ch->errmsg();
  if(d()){
    echo "------\n";
    echo "debug simi data:".t($data);
    echo "------\n";
  }
  return $data;
}

//转义特殊字符
function changeSpecialCharacter($str)
{                                          
  $str = str_replace(' ', '%20', $str);
  $str = str_replace('@', '%40', $str);
  $str = str_replace('#', '%23', $str);
  $str = str_replace('%', '%25', $str);
  $str = str_replace('&', '%26', $str);
  $str = str_replace('(', '%28', $str);
  $str = str_replace(')', '%29', $str);
  $str = str_replace('+', '%2B', $str);
  $str = str_replace('=', '%3D', $str);
  $str = str_replace('<', '%3C', $str);
  $str = str_replace('>', '%3E', $str);
  $str = str_replace('"', '%22', $str);
  $str = str_replace(',', '%2C', $str);
  $str = str_replace('/', '%2F', $str);
  $str = str_replace(':', '%3A', $str);
  $str = str_replace(';', '%3B', $str);
  $str = str_replace('?', '%3F', $str);
  $str = str_replace('\\', '%5C', $str);
  $str = str_replace('|', '%7C', $str);

  return $str;
}
//api
function simSimi(&$weixin,&$type){
  $keyword=$weixin->keyword;
  $answer=simsimiHttp(changeSpecialCharacter($keyword));
  $answer=json_decode($answer);
  if(d()){
    echo "------\nsimsimi answer:";
    var_dump($answer);
    echo "------\n";
  }
  $type="text";
  $strModel=new StrModel(&$weixin);
  return StrView::fromModel($strModel);
}
?>
