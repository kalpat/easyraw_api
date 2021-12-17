<?php

include_once "scripts/inc_PassStrength.php";

if (isset($_REQUEST['password'])) {
  $get_password = $_REQUEST['password'];
  }

  $passw_secure_level = PassStrength($get_password);

 $secure_level_array = array(
   "passw_securelevel" => $passw_secure_level
 );

  echo json_encode($secure_level_array);