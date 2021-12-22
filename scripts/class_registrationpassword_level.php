<?php

class class_registrationpassword_level{

    public function __construct($registrationpassword){
        include_once (__DIR__.'/inc_PassStrength.php');

        if (isset($registrationpassword["password"])) {
          $registrationpassword = $registrationpassword["password"];

          $passw_secure_level_array = array(
              'passw_securelevel' => PassStrength($registrationpassword)
          );

          echo json_encode($passw_secure_level_array);
          }          
        
    }
}

?>