<?php

class class_check_registrationmail{

    public function __construct($registrationmail_array){

        if (isset($registrationmail_array['lang'])) {
            $lang = $registrationmail_array['lang'];
            }
          
          if (isset($registrationmail_array['usermail'])) {
          $usermail = $registrationmail_array['usermail'];
          }
          
          $msgfilepath = "lang/".$lang."/registration_msg.json";
          $msg_JSON = file_get_contents($msgfilepath);      
          $msg_JSON_array = json_decode($msg_JSON, true);
          
          if (filter_var($usermail, FILTER_VALIDATE_EMAIL)) {
          
          $pdo_db_connect = new class_database_connect("easyraw_userdatabase");
          $db_connection=$pdo_db_connect -> get_connection();
          
            if(!$db_connection) {
          
              $msg_emailadress = array(
                'result' => 'fail',
                'errormsg'=> $msg_JSON_array["db_connecterror"]
              );
          
            }else{
          
              $email_ecryptclass = new class_Encryption();
              $email_encryption = $email_ecryptclass -> encryptString($usermail);
              
              $db_locale_query='SELECT er_email FROM er_user_logins WHERE er_email = :email';
              $db_local_stmt = $db_connection->prepare($db_locale_query);
              $db_local_stmt -> bindValue(":email", $email_encryption, PDO::PARAM_STR);
              $db_local_stmt -> execute();
              $CountresultRows = $db_local_stmt -> rowCount();
          
            if ($CountresultRows>0) {
              $msg_emailadress = array(
                'result' => 'fail',
                'errormsg'=> $msg_JSON_array["mailadresse_isregistered"]
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
              'errormsg'=> $msg_JSON_array["wrong_mailadress"]
            );
          
          }
          
          echo json_encode($msg_emailadress);


    }
}

?>