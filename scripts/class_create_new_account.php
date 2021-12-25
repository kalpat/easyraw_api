<?php

class class_create_new_account{

 function fail_sponse($lang, $message){
    $errormsg_array = array(
      "lang" => $lang,
      "result" => "fail",
      "errormsg" => $message
    );
  
    echo json_encode($errormsg_array);
  
  }

public function __construct($newaccount){
    
    //Sprachausgabe gewährleisten
    if (isset($newaccount['newaccount']['lang'])) {
    $lang = $newaccount['newaccount']['lang'];
      }else{
        $lang = "en";
      }
    
    $msgfilepath = "lang/".$lang."/registration_msg.json";
    $msg_JSON = file_get_contents($msgfilepath);      
    $msg_JSON_array = json_decode($msg_JSON, true);
    
    //Wurden überhaupt Informationen für einen neuen account gesendet.
    if (isset($newaccount['newaccount'])) {
    //Informationen herauspicken
    $newaccount = $newaccount["newaccount"];
    if (isset($newaccount["api_key"]) && isset($newaccount["api_secret"])) {
    
    $api_config_path = "easyraw_api_config.json";
    $api_config_JSON = file_get_contents($api_config_path);
    $api_config_array = json_decode($api_config_JSON, true); 

    //Prüfung der zugesandten Api-Keys
     if (($api_config_array["api_key"] == $newaccount["api_key"]) && ($api_config_array["api_secret"] == $newaccount["api_secret"])) {
    
      //Sind alle Informationen zum neuen Account vorhanden? Dies wird zwar
      if (isset($newaccount["type"]) && isset($newaccount["name"]) && isset($newaccount["address"]) && isset($newaccount["zip"]) && isset($newaccount["city"]) && isset($newaccount["usermail"]) && isset($newaccount["password"]) && isset($newaccount["passwordconfirm"])) {
      
        if ($newaccount["password"] == $newaccount["passwordconfirm"]) {
         
          


        } else {
          $this -> fail_sponse($lang, $msg_JSON_array["newaccount_fail"]);
        }
      
      } else {
        $this -> fail_sponse($lang, $msg_JSON_array["newaccount_fail"]);
      }
    
     } else {
      $this -> fail_sponse($lang, $msg_JSON_array["newaccount_fail"]);
     }
    
    } else {
      $this -> fail_sponse($lang, $msg_JSON_array["newaccount_fail"]);
    }
      }else {
          $this -> fail_sponse($lang, $msg_JSON_array["newaccount_fail"]);
      }

}

}

?>