<?php
//Fehler-Report
ini_set('display_errors', 'On');
error_reporting (E_ALL | E_STRICT);

//Klassen automatisch einbinden - PHP-Datei-Bezeichnung und Klassenbezeichnung muss identisch sein

spl_autoload_register(function ($class) {
    include(__DIR__ . '/scripts/' . $class . '.php');
});

if (isset($_GET["mode"])) {
   
$mode = $_GET["mode"];

switch ($mode) {
    //Eingegebene Mail-Adresse im Registrierung-Formular prüfen‚
    case 'chk_registrationmail':
        if (isset($_POST)) {
            new class_check_registrationmail($_POST);
        }
        break;
    case 'chk_registrationpassword':
        //Eingegebenes Password im Registrierungsformular prüfen. Gibt Sicherheitslevel zurück.
        if (isset($_POST)) {
            new class_registrationpassword_level($_POST);
        }
        break;
        //Account kann angelegt werden, Registrierungsformular hat alle Prüfungen (clientseitig) bestanden.
    case 'create_newaccount':

        break;
}

}
?>