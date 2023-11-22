<?php
// connexion_bdd.php

$servername = "localhost";
$username = "root";
$password = "";
$database = "design4green";  // Correction de la variable à utiliser ici

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}
?>