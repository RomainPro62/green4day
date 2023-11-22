<?php
session_start(); // Démarrer la session
include('connexion_bdd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedStatut = $_POST['statut'];

    // Ajoutez ici la logique pour récupérer les données filtrées en fonction du statut
    // Utilisez la valeur de $selectedStatut pour le filtre

    // Exemple (à adapter selon votre structure de base de données) :
    $sql = "SELECT * FROM criteres";

    if ($selectedStatut !== 'Tous') {
        $sql .= " WHERE statut = '$selectedStatut'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Id</th><th>Url</th><th>Thématique</th><th>Objectif</th><th>Mise en Oeuvre</th><th>Contrôle</th><th>Statut</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td><a href='{$row['url']}' target='_blank'>Lien</a></td>";
            echo "<td>{$row['thematique']}</td>";
            echo "<td>{$row['objectif']}</td>";
            echo "<td>{$row['miseEnOeuvre']}</td>";
            echo "<td>{$row['controle']}</td>";
            echo "<td>{$row['statut']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
