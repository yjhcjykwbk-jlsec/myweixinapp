<?php
class MyDB{
  var $db;
  function __construct(){
    $this->db=new SaeMysql();
  }
  function logRequest($result,$xml,$answer){
    $result=str_replace("'","\'",$result);
    $xml=str_replace("'","\'",$xml);
    $answer=str_replace("'","\'",$answer);
    $sql="insert into requests(request,xml,answer) values('".$result."','".$xml."','".$answer."')";
    $this->db->runSql($sql);
  }
  function writeLog($xml){
    $xml=str_replace("'","\'",$xml);
    $sql="insert into xml(xml) values('".$xml."')";
    $this->db->runSql($sql);
  }
  function getState($userName){
    $sql="select * from userState where userName='{$userName}'";
    return $this->db->getData($sql);
  }
  function setState($userName,$transact,$stage){
    $sql="insert into userState(userName,lastTransact,lastStage) values('{$userName}','{$transact}','{$stage}')";
    ds(array("sql"=>$sql));
    $this->db->runSql($sql);
    if( $this->db->errno() != 0 ){
      $sql="update userState set lastTransact='{$transact}',lastStage='{$stage}' where userName='{$userName}'";
      ds(array("sql"=>$sql));
      $this->db->runSql($sql);
      if( $this->db->errno() != 0 ) return false;
    }
    return true;
  }
  function setSession($userName,$strKey,$strValue){
    $sql="insert into userSession(userName,strKey,strValue) values('{$userName}','{$strKey}','{$strValue}')";
    ds(array("sql"=>$sql));
    $this->db->runSql($sql);
    if( $this->db->errno() != 0 )
    {
      $sql="update userSession set strValue='{$strValue}' where userName='{$userName}' and strKey='{$strKey}'";
      ds(array("sql"=>$sql));
      $this->db->runSql($sql);
      if( $this->db->errno() != 0 ) return false;
    }
    return true;
  }
  function getSession($userName,$strKey=""){
    if($strKey!=""){
      $sql="select strValue from userSession where userName='{$userName}' and strKey='{$strKey}'";
      ds(array("sql"=>$sql));
      return $this->db->getData($sql);
    }
    $sql="select strKey,strValue from userSession where userName='{$userName}'"; 
    ds(array("sql"=>$sql));
    return $this->db->getData($sql);
  }
}
?>
