<?php
//Fehler-Report
ini_set('display_errors', 'On');
error_reporting (E_ALL | E_STRICT);

//Klassen automatisch einbinden - PHP-Datei-Bezeichnung und Klassenbezeichnung muss identisch sein

spl_autoload_register(function ($class) {
    include(__DIR__.'/scripts/' . $class . '.php');
});

if (isset($_GET["mode"])) {
   
$mode = $_GET["mode"];

switch ($mode) {
    //Eingebene Mail-Adresse im Registrierung-Formular prüfen‚
    case 'chk_registrationmail':
        var_dump($_POST);
        if (isset($_POST)) {
            echo 'TEST 2';
            new class_check_registrationmail($_REQUEST);
        }
        break;
}









}



?>