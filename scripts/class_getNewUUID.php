<?php

class class_getNewUUID{

protected $new_uuid;

public function __construct(){
    include_once "scripts/class_database_connect.php";
    include_once "scripts/class_generateUUID.php";
    $get_newuuid = class_generateUUID::v4();

    $pdo_db_connect = new class_database_connect("easyraw_userdatabase");
    $db_connection=$pdo_db_connect -> get_connection();
    
    $db_locale_query='SELECT * FROM user_database WHERE er_uuid = :uuid';
    $db_local_stmt = $db_connection->prepare($db_locale_query);
    $db_local_stmt -> execute(array(':uuid' => $get_newuuid));
    $getCols = $db_local_stmt -> rowCount();
  
    if ($getCols>0) {
        $this -> new_uuid = ""; 
}else{
   
    $this -> new_uuid = $get_newuuid;
}
}

public function get_newuuid(){
    return $this -> new_uuid;
}


}