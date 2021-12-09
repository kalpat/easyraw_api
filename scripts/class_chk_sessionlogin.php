<?php
class class_chk_sessionlogin{

public $new_generated_token;

public function __construct($get_session_lang, $get_session_datetime, $get_session_emailencrypt, $get_session_token){

include_once "scripts/class_kill_session.php";

$pdo_db_connect = new class_database_connect("easyraw_userdatabase");
$db_connection=$pdo_db_connect -> get_connection();

$get_accountdata_array = array(
    'email_encrypted' => $get_session_emailencrypt
);

$db_account_query='SELECT er_uuid FROM user_database WHERE er_email = :email_encrypted';
$db_account_stmt = $db_connection->prepare($db_account_query);
$db_account_stmt -> execute($get_accountdata_array);
$get_accountRows = $db_account_stmt -> rowCount();
$db_account_result = $db_account_stmt -> fetch(PDO::FETCH_ASSOC);

if ($get_accountRows==1) {

    $del_token_data = array(
        "session_token" => $get_session_token,
        "user_database_er_uuid" => $db_account_result['er_uuid']
    );

    $db_token_query='DELETE FROM session_token_cache WHERE session_token != :session_token AND user_database_er_uuid = :user_database_er_uuid';
    $db_token_stmt = $db_connection->prepare($db_token_query);
    $db_token_stmt -> execute($del_token_data);

    $get_oldtoken_data = array(
    "session_token" => $get_session_token,
    "user_database_er_uuid" => $db_account_result['er_uuid']
    );

    $db_oldtoken_query='SELECT * FROM session_token_cache WHERE session_token = :session_token AND user_database_er_uuid = :user_database_er_uuid';
    $db_oldtoken_stmt = $db_connection->prepare($db_oldtoken_query);
    $db_oldtoken_stmt -> execute($get_oldtoken_data);
    $get_oldtokenRows = $db_oldtoken_stmt -> rowCount();

    if ($get_oldtokenRows>0) {
     
    $generated_token = openssl_random_pseudo_bytes(16);		
    $generated_token = bin2hex($generated_token);

    $this -> new_generated_token = $generated_token;

  $get_new_token = array(
    "new_session_datetime" => $get_session_datetime,
     "new_session_token" => $this -> new_generated_token,
     "new_session_uuid" => $db_account_result['er_uuid'],
     "old_session_token" => $get_session_token
  );
  
$db_newtoken_query='UPDATE session_token_cache SET token_datetime = :new_session_datetime, session_token = :new_session_token WHERE user_database_er_uuid = :new_session_uuid AND session_token = :old_session_token';
$db_newtoken_stmt = $db_connection->prepare($db_newtoken_query);
$db_newtoken_stmt -> execute($get_new_token);

}else {
    new class_kill_session();
}

}else {
    new class_kill_session();
}

}

public function getnewToken(){
    return $this -> new_generated_token;
}

}