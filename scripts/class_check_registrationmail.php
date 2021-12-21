<?php

class class_check_registrationmail{

    public function __construct($registrationmail_array){

        if (isset($registrationmail_array['lang'])) {
            $get_lang = $registrationmail_array['lang'];
            }
          
          if (isset($registrationmail_array['usermail'])) {
          $get_usermail = $registrationmail_array['usermail'];
          }
          
          $get_msgfilepath = "lang/".$get_lang."/registration_msg.json";
          $getmsg_JSON = file_get_contents($get_msgfilepath);      
          $getmsg_JSON_array = json_decode($getmsg_JSON, true);
          
          if (filter_var($get_usermail, FILTER_VALIDATE_EMAIL)) {
          
          $pdo_db_connect = new class_database_connect("easyraw_userdatabase");
          $db_connection=$pdo_db_connect -> get_connection();
          
            if(!$db_connection) {
          
              $msg_emailadress = array(
                'result' => 'fail',
                'errormsg'=> $getmsg_JSON_array["db_connecterror"]
              );
          
            }else{
          
              $get_email_ecryptclass = new class_Encryption();
              $get_email_encryption = $get_email_ecryptclass -> encryptString($get_usermail);
              
              $db_locale_query='SELECT er_email FROM er_user_logins WHERE er_email = :email';
              $db_local_stmt = $db_connection->prepare($db_locale_query);
              $db_local_stmt -> bindValue(":email", $get_email_encryption, PDO::PARAM_STR);
              $db_local_stmt -> execute();
              $getCols = $db_local_stmt -> rowCount();
          
            if ($getCols>0) {
              $msg_emailadress = array(
                'result' => 'fail',
                'errormsg'=> $getmsg_JSON_array["mailadresse_isregistered"]
              );
            }else{
              $msg_emailadress = array(
                'result' => 'Ok',
                'errormsg'=> 'MailOk'
              );
            }
          
          }
          
          }else{
          
            $msg_emailadress = array(
              'result' => 'fail',
              'errormsg'=> $getmsg_JSON_array["wrong_mailadress"]
            );
          
          }
          
          echo json_encode($msg_emailadress);


    }
}

?>