<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Démarrer la session
include('connexion_bdd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typeEnregistrement = $_POST['typeEnregistrement'];
    $dataToSave = $_POST['data'];

    // Ajoutez ici la logique d'enregistrement dans la base de données en fonction du type (temporaire ou définitif)
    // Utilisez les valeurs de $dataToSave pour l'enregistrement

    // Exemple (à adapter selon votre structure de base de données) :
    $url_site = $dataToSave['url'];
    $critere_id = $dataToSave['critere_id'];
    $etat = $dataToSave['etat'];
    $score_conformite = $dataToSave['score_conformite'];

    if ($typeEnregistrement === 'temporaire') {
        $sql = "INSERT INTO resultatstemporaires (url_site, critere_id, etat, score_conformite) VALUES ('$url_site', $critere_id, '$etat', $score_conformite)";
    } elseif ($typeEnregistrement === 'definitif') {
        $sql = "INSERT INTO resultatsfinaux (url_site, critere_id, etat, score_conformite) VALUES ('$url_site', $critere_id, '$etat', $score_conformite)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur d'enregistrement: " . $conn->error;
    }

    // Ajoutez ceci pour un débogage côté serveur
echo "Type d'enregistrement : " . $typeEnregistrement . "<br>";
echo "Données à enregistrer : ";
print_r($dataToSave);


    // Fermer la connexion à la base de données
    $conn->close();
}
?>
