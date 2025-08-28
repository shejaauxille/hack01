<?php
$servername = "localhost"; // Change this from "db" to "localhost"
$username = "root";  
$password = ""; 
$dbname = "SQL_Database_edoc"; 

$database = new mysqli($servername, $username, $password, $dbname);

if ($database->connect_error) {
    die("Échec de la connexion : " . $database->connect_error);
}
?>
