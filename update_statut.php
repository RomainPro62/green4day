<?php
// update_statut.php

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "design4green";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données de la requête AJAX
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $url = isset($_POST['url']) ? $_POST['url'] : null;
    $thematique = isset($_POST['thematique']) ? $_POST['thematique'] : null;
    $objectif = isset($_POST['objectif']) ? $_POST['objectif'] : null;
    $miseEnOeuvre = isset($_POST['miseEnOeuvre']) ? $_POST['miseEnOeuvre'] : null;
    $controle = isset($_POST['controle']) ? $_POST['controle'] : null;
    $statut = isset($_POST['statut']) ? $_POST['statut'] : null;

    // Utiliser ON DUPLICATE KEY UPDATE pour insérer ou mettre à jour la ligne
    $sql = "INSERT INTO criteres (id, url, thematique, objectif, miseEnOeuvre, controle, statut)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        // Utiliser le bon type de données pour chaque colonne
        $stmt->bind_param("sssssss", $id, $url, $thematique, $objectif, $miseEnOeuvre, $controle, $statut);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Insertion ou mise à jour réussie pour l'ID $id";
        } else {
            echo "Échec de l'insertion ou de la mise à jour : " . $stmt->error;
        }

        // Fermer la déclaration
        $stmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    }
} else {
    // Si la requête n'est pas une requête POST, renvoyer une réponse d'erreur
    echo "Erreur : méthode non autorisée";
}

// Fermer la connexion à la base de données
$conn->close();
?>