// print process.argv
process.argv.forEach(function (val, index, array) {
  console.log(index + ': ' + val);
});
var http=require("http");
packet="<xml><ToUserName><![CDATA[toUser]]></ToUserName> <FromUserName>"+
"<![CDATA[fromUser]]></FromUserName> <CreateTime>12345678</CreateTime>"+
"<MsgType><![CDATA[text]]></MsgType> <Content><![CDATA["+process.argv[2]+"]]></Content></xml>";
var options={
  host:"1.nanyancampus.sinaapp.com",
  port:80,
  path:"/index_debug.php",
  // path:"",
  method:"POST"
};
var req=http.request(options,function(res){
  console.log("status:"+res.statusCode);
  console.log("headers:"+JSON.stringify(res.headers));
  console.log("-------------------------------------");
  res.on('data',function(chunk){
    console.log(chunk+"");
  });
});
req.on('socket', function(socket) {
  socket.on('error', function(err) {
    console.log('socket on error');
    callback('socket error: ' + err);
    req.abort();
  });
});
req.write(packet);
req.end();
