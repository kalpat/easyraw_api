<?php

class class_create_new_account{

public function __construct($newaccount){

    include_once (__DIR__.'scripts/class_database_connect.php');
    include_once (__DIR__.'scripts/class_getNewUUID.php');
    include_once (__DIR__.'scripts/class_Encryption.php');
    
    //Sprachausgabe gewährleisten
    if (isset($_POST['newaccount']['lang'])) {
    $lang = $_POST['newaccount']['lang'];
      }else{
        $lang = "en";
      }
    
    $msgfilepath = "lang/".$lang."/registration_msg.json";
    $msg_JSON = file_get_contents($msgfilepath);      
    $msg_JSON_array = json_decode($msg_JSON, true);
    
    //Wurden überhaupt Informationen für einen neuen account gesendet.
    if (isset($_POST['newaccount'])) {
    //Informationen herauspicken
    $newaccount = $_POST["newaccount"];
    if (isset($newaccount["api_key"]) && isset($newaccount["api_secret"])) {
    
    $api_config_path = "easyraw_api_config.json";
    $api_config_JSON = file_get_contents($api_config_path);
    $api_config_array = json_decode($api_config_JSON); 
    
    //Prüfung der zugesandten Api-Keys
     if (($api_config_array["api_key"] == $newaccount["api_key"]) && ($api_config_array["api_secret"] == $newaccount["api_secret"])) {
    
      //Sind alle Informationen zum neuen Account vorhanden? Dies wird zwar
      if ((isset($newaccount["type"])) && (isset($newaccount["name"])) && (isset($newaccount["address"])) && (isset($newaccount["zip"])) && (isset($newaccount["city"])) ) {
        # code...
      } else {
        $errormsg_array = array(
          "lang" => $lang,
          "result" => "fail",
          "errormsg" => $msg_JSON_array["newaccount_fail"]
        );
      
        echo json_encode($errormsg_array);
      }
    
     } else {
      $errormsg_array = array(
        "lang" => $lang,
        "result" => "fail",
        "errormsg" => $msg_JSON_array["auth_fail"]
      );
    
      echo json_encode($errormsg_array);
     }
    
    } else {
      $errormsg_array = array(
        "lang" => $lang,
        "result" => "fail",
        "errormsg" => $msg_JSON_array["newaccount_fail"]
      );
    
      echo json_encode($errormsg_array);
    }
      }else {
          $errormsg_array = array(
          "lang" => $lang,
          "result" => "fail",
          "errormsg" => $msg_JSON_array["newaccount_fail"]
        );
    
        echo json_encode($errormsg_array);
      }

}

}

?>