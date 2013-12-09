<?php
function music(&$weixin,&$type){
  $keyword=$weixin->keyword;
  @list($title,$singer,$song)=split(" ",$keyword,3);
  if(!$song){
    if(!$singer) $singer=decode(u()->s("singer"));
    if($singer){
      $songs=u()->s("songs");
      ds(array("singer not null, then songs:"=>$songs));
      if(!empty($songs)){
        $song=decode($songs[$keyword]);
      }
    }
  }
  ds(array("song"=>$song,"singer"=>$singer));
  if($song){
    // $url="http://api2.sinaapp.com/search/music/?appkey=0020130430&appsecert=fa6095e1133d28ad&reqtype=music&keyword=".urlencode($song);
    $url="http://box.zhangmen.baidu.com/x?op=12&count=1&title={$song}\$\${$singer}\$\$\$\$";
    $musicObj=c($url,"xml",true);
    // $musicModel=new MusicModel(&$weixin);
    $songs="找到的链接:";
    if($musicObj["count"]>0){
      foreach($musicObj['url'] as $it=>$itemobj)
      {
        $encode = $itemobj->encode;
        //处理decode ,发现微信在处理音乐的时候有个问题，所以这里删除一个参数
        $decode = $itemobj->decode;   
        $removedecode = end(explode('&', $decode));
        if($removedecode<>"")
        {
          $removedecode="&".$removedecode;    
        }
        $decode = str_replace($removedecode,"", $decode);
        $musicurl= str_replace(end(explode('/', $encode))   ,$decode,$encode);
        // $musicModel->addMusicItem($song,$singer,$musicurl,$musicurl);
        ds(array("musicurl"=>$musicurl));
        $songs.="$musicurl ";
        break;
      }
    }
    u()->setState("music","3");
    // return MusicView::sample(&$weixin);
    // return MusicView::fromModel($musicModel);
    return $weixin->makeText($songs);
  }
  //由singer找songs
  else if($singer!=""){
    u()->setSession("singer",json_encode($singer));

    $songs=findSinger($singer);
    u()->setSession("songs",json_encode($songs));
    ds(array("songs"=>$songs));
    $str="请输入具体歌名:";
    foreach($songs as $it=>$song)
      $str.="$it:$song ";
    u()->setState("music","2");
    return $weixin->makeText($str);
  }
  else {
    $type="text";
    $str="请输入 歌曲 [歌手名] [歌名]";
    u()->setState("music","1");
    return $weixin->makeText($str);
  }
}
function findSinger($singer){
  $url="http://mp3.baidu.com/dev/api/?tn=getinfo&ct=0&format=json&ie=utf-8&word=".urlencode($singer);
  $tmp=c($url,'json',true);
  $songs=array();
  foreach($tmp as $it=>$obj){ 
    array_push($songs,decode($obj->song));//str_replace('\u','u',$obj->song));
  }
  return $songs;
}
?>
