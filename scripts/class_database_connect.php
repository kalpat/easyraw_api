<?php
class class_database_connect {  
    
    private $db_server;
    private $db_user;
    private $db_password;
    private $db_options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_PERSISTENT => true);
    protected $database_connection;
  
  /*========== Funktionen zum Herstellen der Datenbankverbindung ========= */
  public function __construct($databasename) {

    $getdb_JSONconfig = file_get_contents("easyraw_api_config.json");      
    $getdb_config_array = json_decode($getdb_JSONconfig, true);

    $db_host = $getdb_config_array["host"];
    $this->db_user = $getdb_config_array["db_username"];
    $this->db_password = $getdb_config_array["db_password"];

   $this->db_server = "mysql:host=".$db_host.";dbname=".$databasename;


 /*======== Verbindungs-Informationen für die Datenbank =============*/
 /*--------- Verbindung zur Datenbank unter Anwendnung der Verbindungsinformationen herstellen ----------*/
 $this->database_connection = new PDO($this->db_server, $this->db_user, $this->db_password, $this->db_options);
 
 /*---------Prüfung, ob die Verbindung zur Datenbank erfolgreich war ------------ */
 if(!$this->database_connection)  {
 /* -------- Fehlermeldung, wenn die Verbindung zur Datenbank fehlgeschlagen ist ----------*/
 die('Die Datenbankverbindung konnte nicht hergestellt werden.');	
 }

  }

  public function get_connection() {

    return $this->database_connection;
    
      }
    
}