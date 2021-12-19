<?php
/*****************
 * 
 * PHP-Script zum Anlegen
 * 
 * 
 */

//Fehler-Report
ini_set('display_errors', 'On');
error_reporting (E_ALL | E_STRICT);

include_once "scripts/class_database_connect.php";
include_once "scripts/class_getNewUUID.php";
include_once "scripts/class_Encryption.php";

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

/*
if (isset($_REQUEST['client_datetime'])) {
  $newaccount_clientdatetime = $_REQUEST['client_datetime'];
  }

if (isset($_REQUEST['usermail'])) {
if (isset($_REQUEST['usermail'])) {
if (isset($_REQUEST['usermail'])) {
    $newaccount_usermail = $_REQUEST['usermail'];
    }

    if (isset($_REQUEST['password'])) {
        $newaccount_password = $_REQUEST['password'];
        }createnewaccount
        if (isset($_REQUEST['password_confirm'])) {
            $newaccount_password_confirm = $_REQUEST['password_confirm'];
            }

        if (filter_var($newaccount_usermail, FILTER_VALIDATE_EMAIL)) {

         $pdo_db_connect = new class_database_connect("easyraw_userdatabase");
        $db_connection=$pdo_db_connect -> get_connection();

        if(!$db_connection) {
            echo "Datenbankverbindung fehlgeschlagen!";
             }else{

         $get_encryption_class = new class_Encryption();
         $get_email_encryption = $get_encryption_class -> encryptString($newaccount_usermail);

$db_locale_query='SELECT er_email FROM user_database WHERE er_email = :email';
  $db_local_stmt = $db_connection->prepare($db_locale_query);
 	$db_local_stmt -> bindValue(":email", $get_email_encryption, PDO::PARAM_STR);
  $db_local_stmt -> execute();
  $getCols = $db_local_stmt -> rowCount();

  if ($getCols>0) {
    echo 'Emailsadresse bereits bekannt.';
  }else{

if ($newaccount_password==$newaccount_password_confirm) {
    $get_newuuid_inst = new class_getNewUUID();
    $get_new_uuid = $get_newuuid_inst -> get_newuuid();
   
    $pdo_db_connect = new class_database_connect("easyraw_userdatabase");
    $db_connection=$pdo_db_connect -> get_connection();
   
    if(!$db_connection) {
       echo "Datenbankverbindung fehl geschlagen!";
        }else{

            $get_password_encclass = new class_Encryption();
            $get_password_encryption = $get_password_encclass -> encryptString($newaccount_password);

            $db_customers_data = array(
                'er_uuid' => $get_new_uuid,
                'er_datetime' => $newaccount_clientdatetime,
                'er_email' => $get_email_encryption,
                'er_password' => $get_password_encryption
            );

           $db_customer_insert_query='INSERT INTO user_database (er_uuid, er_creationdatetime, er_email, 
           er_password, user_emailconfirmation) VALUES (:er_uuid, :er_datetime, :er_email, :er_password, "0")';
           
           $db_customer_insert_func = $db_connection -> prepare($db_customer_insert_query);
           $db_customer_insert_func -> execute($db_customers_data);
            $db_customer_insert_result = $db_customer_insert_func -> rowCount(); 
                   
           if ($db_customer_insert_result>0) {

/************************************************** CREATE NEW USERDATABASE *************************************************************************** */
/*
$getdb_JSONconfig = file_get_contents("easyraw_api_config.json");      
$getdb_config_array = json_decode($getdb_JSONconfig, true);

$create_databasename = $get_new_uuid.$getdb_config_array["db_suffix"];
$db_host = $getdb_config_array["host"];
$db_user = $getdb_config_array["db_username"];
$db_password = $getdb_config_array["db_password"];

//*************************************************** CREATE NEW USER-DATABASE generated by user-UUID *************************************************/
/*
try {
    $dbh = new PDO("mysql:host=$db_host", $db_user, $db_password);
    $dbh->exec("CREATE DATABASE IF NOT EXISTS `$create_databasename`;
            CREATE USER '$db_user'@'$db_host' IDENTIFIED BY '$db_password';
            GRANT ALL ON `$create_databasename`.* TO '$db_user'@'$db_host';
            FLUSH PRIVILEGES;") 
    or die(print_r($dbh->errorInfo(), true));

} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}

$pdo_db_connect = new class_database_connect($create_databasename);
$db_connection=$pdo_db_connect -> get_connection();

//****************************************************** CREATE TABLE accounts  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS accounts (
  uuid VARCHAR(36) NOT NULL,
  account_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (uuid),
  UNIQUE INDEX account_name_UNIQUE (account_name ASC))
ENGINE = InnoDB';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE units_save  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS units_save (
  uuid VARCHAR(36) NOT NULL,
  unit VARCHAR(6) NULL DEFAULT NULL,
  PRIMARY KEY (uuid),
  UNIQUE INDEX unit_UNIQUE (unit ASC))
ENGINE = InnoDB';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//******************************************** CREATE TABLE materials ********************************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS materials (
  uuid VARCHAR(36) NOT NULL,
  material VARCHAR(100) NOT NULL,
  unit VARCHAR(6) NOT NULL,
  sort_num INT NULL,
  PRIMARY KEY (uuid))
ENGINE = InnoDB';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE daily_prices_datetime  **********************************************/
/*

$db_create_table_query='CREATE TABLE IF NOT EXISTS daily_prices_datetime (
  uuid VARCHAR(36) NOT NULL,
  prices_datetime DATETIME NOT NULL,
  PRIMARY KEY (uuid))
ENGINE = InnoDB;';

$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();
//****************************************************** CREATE TABLE daily_prices  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS daily_prices (
    uuid VARCHAR(36) NOT NULL,
    price REAL NULL DEFAULT NULL,
    materials_uuid VARCHAR(36) NOT NULL,
    daily_prices_datetime_uuid VARCHAR(36) NOT NULL,
    material_sort INT NOT NULL,
    PRIMARY KEY (uuid),
  INDEX fk_daily_prices_materials_idx (materials_uuid ASC),
  INDEX fk_daily_prices_daily_prices_datetime1_idx (daily_prices_datetime_uuid ASC) ,
  CONSTRAINT fk_daily_prices_materials
    FOREIGN KEY (materials_uuid)
    REFERENCES materials (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_daily_prices_daily_prices_datetime1
    FOREIGN KEY (daily_prices_datetime_uuid)
    REFERENCES daily_prices_datetime (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE booking_actions  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS booking_actions (
  uuid VARCHAR(36) NOT NULL,
  action_name VARCHAR(200) NULL DEFAULT NULL,
  action_time DATETIME NULL DEFAULT NULL,
  action_completed TINYINT(4) NULL DEFAULT NULL,
  accounts_uuid VARCHAR(36) NOT NULL,
  booking_notice TEXT NULL,
  PRIMARY KEY (uuid),
  INDEX fk_booking_actions_accounts1_idx (accounts_uuid ASC),
  CONSTRAINT fk_booking_actions_accounts1
    FOREIGN KEY (accounts_uuid)
    REFERENCES accounts (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB';

  $db_create_insert_func = $db_connection -> prepare($db_create_table_query);
  $db_create_insert_func -> execute();

  //****************************************************** CREATE TABLE booking_action_pos  **********************************************/
  /*
$db_create_table_query='CREATE TABLE IF NOT EXISTS booking_action_pos (
  uuid VARCHAR(36) NOT NULL,
  booking_value REAL NULL DEFAULT NULL,
  price REAL NULL DEFAULT NULL,
  materials_uuid VARCHAR(36) NOT NULL,
  booking_datetime DATETIME NOT NULL,
  booking_actions_uuid VARCHAR(36) NOT NULL,
  booking_pos_sort INT NOT NULL,
  PRIMARY KEY (uuid),
  INDEX fk_bookings_materials1_idx (materials_uuid ASC),
  INDEX fk_bookings_booking_actions1_idx (booking_actions_uuid ASC),
  CONSTRAINT fk_bookings_materials100
    FOREIGN KEY (materials_uuid)
    REFERENCES materials (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_bookings_booking_actions100
    FOREIGN KEY (booking_actions_uuid)
    REFERENCES booking_actions (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE bookings  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS bookings (
  uuid VARCHAR(36) NOT NULL,
  booking_value REAL NULL DEFAULT NULL,
  price REAL NULL DEFAULT NULL,
  booking_datetime DATETIME NOT NULL,
  materials_uuid VARCHAR(36) NOT NULL,
  accounts_uuid VARCHAR(36) NOT NULL,
  current_status REAL NULL DEFAULT NULL,
  is_last_booking TINYINT NULL,
  bookings_action_pos_uuid VARCHAR(36) NOT NULL,
  PRIMARY KEY (uuid),
INDEX fk_bookings_materials1_idx (materials_uuid ASC),
INDEX fk_bookings_accounts1_idx (accounts_uuid ASC),
INDEX fk_bookings_bookings_action_pos1_idx (bookings_action_pos_uuid ASC),
CONSTRAINT fk_bookings_materials1
  FOREIGN KEY (materials_uuid)
  REFERENCES materials (uuid)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
CONSTRAINT fk_bookings_accounts1
  FOREIGN KEY (accounts_uuid)
  REFERENCES accounts (uuid)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT fk_bookings_bookings_action_pos1
  FOREIGN KEY (bookings_action_pos_uuid)
  REFERENCES booking_action_pos (uuid)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
ENGINE = InnoDB';

$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE bookings_notes  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS bookings_notes (
  uuid VARCHAR(36) NOT NULL,
  note_value REAL NULL DEFAULT NULL,
  price REAL NULL DEFAULT NULL,
  note_datetime DATETIME NOT NULL,
  materials_uuid VARCHAR(36) NOT NULL,
  accounts_uuid VARCHAR(36) NOT NULL,
  bookings_action_pos_uuid VARCHAR(36) NOT NULL,
  PRIMARY KEY (uuid),
  INDEX fk_bookings_materials1_idx (materials_uuid ASC),
  INDEX fk_bookings_accounts1_idx (accounts_uuid ASC),
  INDEX fk_bookings_notes_bookings_action_pos1_idx (bookings_action_pos_uuid ASC),
  CONSTRAINT fk_bookings_materials10
    FOREIGN KEY (materials_uuid)
    REFERENCES materials (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_bookings_accounts10
    FOREIGN KEY (accounts_uuid)
    REFERENCES accounts (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_bookings_notes_bookings_action_pos1
    FOREIGN KEY (bookings_action_pos_uuid)
    REFERENCES booking_action_pos (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB';
  
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

//****************************************************** CREATE TABLE generell_values  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS generell_values (
  id INT NOT NULL AUTO_INCREMENT,
  value_key VARCHAR(30) NULL,
  gen_value VARCHAR(100) NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;';

$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

  
//****************************************************** CREATE TABLE booking_action_imgs  **********************************************/
/*
$db_create_table_query='CREATE TABLE IF NOT EXISTS booking_action_imgs (
  uuid VARCHAR(36) NOT NULL,
  booking_img BLOB NULL,
  booking_imgtype VARCHAR(20) NULL,
  booking_actions_uuid VARCHAR(36) NOT NULL,
  PRIMARY KEY (uuid),
  INDEX fk_booking_action_imgs_booking_actions1_idx (booking_actions_uuid ASC),
  CONSTRAINT fk_booking_action_imgs_booking_actions1
    FOREIGN KEY (booking_actions_uuid)
    REFERENCES booking_actions (uuid)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB';

 $db_create_insert_func = $db_connection -> prepare($db_create_table_query);
 $db_create_insert_func -> execute();

  //****************************************************** CREATE TABLE booking_actions_uebertrag  **********************************************/
  /*
  $db_create_table_query='CREATE TABLE IF NOT EXISTS booking_actions_uebertrag (
    uuid VARCHAR(36) NOT NULL,
    booking_prime_uuid VARCHAR(36) NOT NULL,
    booking_second_uuid VARCHAR(36) NOT NULL,
    PRIMARY KEY (uuid),
    INDEX fk_booking_actions_uebertrag_booking_actions1_idx (booking_prime_uuid ASC),
    INDEX fk_booking_actions_uebertrag_booking_actions2_idx (booking_second_uuid ASC),
    CONSTRAINT fk_booking_actions_uebertrag_booking_actions1
      FOREIGN KEY (booking_prime_uuid)
      REFERENCES booking_actions (uuid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
    CONSTRAINT fk_booking_actions_uebertrag_booking_actions2
      FOREIGN KEY (booking_second_uuid)
      REFERENCES booking_actions (uuid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
  ENGINE = InnoDB';
  
    $db_create_insert_func = $db_connection -> prepare($db_create_table_query);
    $db_create_insert_func -> execute();

$db_create_table_query='INSERT INTO generell_values (id, value_key, gen_value) VALUES (DEFAULT, "currency", "$");';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();

$db_create_table_query='INSERT INTO generell_values (id, value_key, gen_value) VALUES (DEFAULT, "material_sort", "0");';
$db_create_insert_func = $db_connection -> prepare($db_create_table_query);
$db_create_insert_func -> execute();


            $get_loginform = file_get_contents('templates/loginform.html');
            echo $get_loginform;

           }else{
               echo "Eintrag nicht erfolgreich.";
           }
         
        }
   
}else{
    echo "Passwort und Passwort-Bestätigung stimmen nicht überein!";
}

}
     
}

            }
*/