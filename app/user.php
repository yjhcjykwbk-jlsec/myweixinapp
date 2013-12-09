<?php
class User{
  public $userName;
  public $userState;
  public $userSession;
  public $lastTransact;
  public $lastStage;
  function __construct($userName){
    $this->userName=$userName;
    $tmp=db()->getState($userName);
    if($tmp){
      $this->userState=$tmp['0'];
      $this->lastTransact = $this->userState["lastTransact"];
      $this->lastStage = $this->userState["lastStage"];
    }
    $this->initSession();
  }
  private function initSession(){
    $tmp=db()->getSession($this->userName);
    ds(array("tmp"=>$tmp));
    foreach($tmp as $it=>$arr)
      $this->userSession[$arr['strKey']]=$arr['strValue'];
  }
  public function setSession($strKey,$strValue){
    $this->userSession[$strKey]=$strValue;
    $strValue=str_replace('\\','\\\\',$strValue);
    if(!db()->setSession($this->userName,$strKey,$strValue)){
      echo "set session $strKey error";
      exit();
    }
  }
  public function getSession($strKey){
    // return db()->getSession($this->userName,$strKey);
    return $this->userSession[$strKey];
  }
  public function s($strKey){
    // return db()->getSession($this->userName,$strKey);
    $strValue=$this->userSession[$strKey];
    if($strValue) {
      return json_decode($strValue);
    }
    return NULL;
  }
  public function setState($transact,$stage){
    $this->lastTransact = $transact;
    $this->lastStage = $stage;
    if(!db()->setState($this->userName,$transact,$stage))
    {
      echo "set state error";
      exit();
    }
  }
}
?>
