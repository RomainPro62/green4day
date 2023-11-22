<?php
//traitement.php
$serveur = 'localhost';
$utilisateur = 'root';
$motDePasse = '';
$baseDeDonnees = 'design4green';

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$baseDeDonnees", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
// Vérification de l'existence des données envoyées depuis le formulaire
if (isset($_POST['data'])) {
    // Récupération des données du formulaire
    $donnees = $_POST['data'];

    // Requête d'insertion dans la base de données
    $requete = $connexion->prepare("INSERT INTO audit (URL_site) VALUES (:data)");
    $requete->bindParam(':data', $donnees);

    // Exécution de la requête
    try {
        $requete->execute();
        echo "Données insérées avec succès";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
} else {
    echo "Aucune donnée reçue";
}
?>