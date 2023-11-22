<!--site3.php-->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        #filterStatut {
            margin-bottom: 10px;
        }
        
        #table-container {
            max-height: 800px;
            overflow-y: auto;
        }

        #exportButton {
            margin-top: 10px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        @media print {
            body {
                font-size: 12px;
            }
        }

        #table-container {
            height: auto;
        }

        #table-container table th:nth-child(1),
        #table-container table td:nth-child(1) {
            width: 5%;
        }

        #table-container table th:nth-child(2),
        #table-container table td:nth-child(2) {
            width: 15%;
        }

        #table-container table th:nth-child(3),
        #table-container table td:nth-child(3) {
            width: 15%;
        }

        #table-container table th:nth-child(4),
        #table-container table td:nth-child(4) {
            width: 20%;
        }

        #table-container table th:nth-child(5),
        #table-container table td:nth-child(5) {
            width: 20%;
        }

        #table-container table th:nth-child(6),
        #table-container table td:nth-child(6) {
            width: 15%;
        }

        #table-container table th:nth-child(7),
        #table-container table td:nth-child(7) {
            width: 10%;
        }
    </style>
    <title>Tableau de Données</title>
</head>
<h1>Formulaire d'audit de la conformité écoconception d'un site</h1>

    <h1>Enregistré l'url du site</h1>

    <!-- Formulaire -->
    <form id="myForm">
        <label for="data">Entrez l'url du site a audité :</label>
        <input type="text" id="data" name="data">
        <button type="button" onclick="envoyerDonnees()">Envoyer</button>
    </form>

    <!-- Affichage des résultats -->
    <div id="result"></div>

    <script>
        function envoyerDonnees() {
            var data = document.getElementById('data').value;

            var xhr = new XMLHttpRequest();
            var url = 'traitement.php'; // Remplacez cela par l'URL de votre script de traitement

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('result').innerHTML = xhr.responseText;
                    } else {
                        console.error('Erreur : ' + xhr.status);
                    }
                }
            };

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('data=' + encodeURIComponent(data));
        }
    </script>

<body>

    <?php
    // Inclusion du fichier de connexion à la base de données
    include('connexion_bdd.php');

    // Récupérer les valeurs distinctes de la colonne "Statut" dans le menu déroulant de filtrage
    $sqlStatutValues = "SELECT DISTINCT statut FROM criteres";
    $resultStatutValues = $conn->query($sqlStatutValues);

    $statutValues = [];
    while ($row = $resultStatutValues->fetch_assoc()) {
        $statutValues[] = $row['statut'];
    }

    $data = [
        // Vos données ici
    ];

    // Récupérer le contenu JSON depuis le lien
    $json_content = file_get_contents("https://ecoresponsable.numerique.gouv.fr/publications/referentiel-general-ecoconception/export/referentiel-general-ecoconception-version-v1.json");

    // Décoder le contenu JSON en tableau associatif
    $json_data = json_decode($json_content, true);

    // Ajouter les critères au tableau
    foreach ($json_data['criteres'] as $critere) {
        $data['criteres'][] = $critere;
    }
    echo "<label for='filterStatut'>Filtrer par statut :</label>";
    echo "<select id='filterStatut'>";
    
    // Ajouter l'option pour "Tous" en dehors de la boucle des valeurs distinctes
    echo "<option value='Tous'>Tous</option>";

    // Ajouter les valeurs distinctes de la colonne "Statut" dans le menu déroulant
    foreach ($statutValues as $statut) {
        echo "<option value='$statut'>$statut</option>";
    }

    echo "</select>";

    echo "<div id='table-container'>";
    echo "<table>";
    echo "<tr><th>Id</th><th>Url</th><th>Thématique</th><th>Objectif</th><th>Mise en Oeuvre</th><th>Contrôle</th><th>Statut</th></tr>";

    foreach ($data['criteres'] as $critere) {
        echo "<tr>";
        echo "<td>{$critere['id']}</td>";
        echo "<td><a href='{$critere['url']}' target='_blank'>Lien</a></td>";
        echo "<td>{$critere['thematique']}</td>";
        echo "<td>{$critere['objectif']}</td>";
        echo "<td>{$critere['miseEnOeuvre']}</td>";
        echo "<td>{$critere['controle']}</td>";
        echo "<td>";
        echo "<select class='statut' data-id='{$critere['id']}' data-url='{$critere['url']}' data-thematique='{$critere['thematique']}' data-objectif='{$critere['objectif']}' data-miseEnOeuvre='{$critere['miseEnOeuvre']}' data-controle='{$critere['controle']}' data-current-statut='" . (isset($critere['statut']) ? $critere['statut'] : 'Non défini') . "'>";

        // Utilisez une variable pour stocker la valeur du statut actuel
        $currentStatut = isset($critere['statut']) ? $critere['statut'] : 'Non défini';

        // Ajouter l'option pour "Non défini" avec une vérification pour sélectionner si nécessaire
        echo "<option value='Non défini' " . ($currentStatut === 'Non défini' ? 'selected' : '') . ">Non défini</option>";

        $options = ["Conformes", "En cours de déploiement", "Non conformes", "Non applicables"];
        foreach ($options as $option) {
            // Utiliser la variable pour sélectionner l'option correcte
            $selected = (isset($currentStatut) && $option === $currentStatut) ? 'selected' : '';
            echo "<option value='$option' $selected>$option</option>";
        }

        echo "</select>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";

    // Fermer la connexion à la base de données
    $conn->close();
    ?>
<button id="exportButton">Exporter en PDF</button>
    <script>
        $(document).ready(function () {
            // Ajoutez un gestionnaire d'événements pour le changement dans le menu déroulant de filtrage
            $('#filterStatut').change(function () {
                var selectedStatut = $(this).val();

                // Envoyez une requête AJAX pour récupérer les données filtrées
                $.ajax({
                    type: 'POST',
                    url: 'get_filtered_data.php',
                    data: {
                        statut: selectedStatut
                    },
                    success: function (response) {
                        // Mettez à jour le contenu du conteneur de la table avec les données filtrées
                        $('#table-container').html(response);

                        // Si "Tous" est sélectionné, réinitialisez les listes déroulantes dans le tableau
                        if (selectedStatut === 'Tous') {
                            $('.statut').each(function () {
                                var currentStatut = $(this).data('current-statut');
                                $(this).val(currentStatut);
                            });
                        }
                    }
                });
            });

            // Utilisez jQuery pour détecter les changements dans la liste déroulante
            $('.statut').change(function () {
                var id = $(this).data('id');
                var url = $(this).data('url');
                var thematique = $(this).data('thematique');
                var objectif = $(this).data('objectif');
                var miseEnOeuvre = $(this).data('miseenoeuvre');
                var controle = $(this).data('controle');
                var statut = $(this).val();

                // Envoyer la requête AJAX pour mettre à jour la base de données
                $.ajax({
                    type: 'POST',
                    url: 'update_statut.php',
                    data: {
                        id: id,
                        url: url,
                        thematique: thematique,
                        objectif: objectif,
                        miseEnOeuvre: miseEnOeuvre,
                        controle: controle,
                        statut: statut
                    },
                    success: function (response) {
                        console.log(response);
                    }
                });
            });
            $('#exportButton').click(function () {
            exportToPDF();
        });
        });
        function exportToPDF() {
        // Ajustez la hauteur du conteneur ici en fonction de vos besoins
        // Par exemple, réglez la hauteur sur 1000 pixels
        $('#table-container').css('height', '1000px');

        var element = document.getElementById('table-container');

        var opt = {
            margin: 10,
            filename: 'export.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            onAfterHtml2Canvas: function (canvas, pdf) {
                console.log(canvas.toDataURL('image/jpeg'));
            }
        };

        html2pdf().from(element).set(opt).save();
    }
    </script>
<?php
    echo "</table>";
     // Variables pour le calcul du score
     $conforme = 0;
     $enCoursDeDeploiement = 0;
     $nonConforme = 0;
     $nonApplicable = 0;

     
         // Vérification de la présence de 'etat_conformite'
         if (isset($critere['etat_conformite'])) {
             // Logique de calcul du score en fonction de l'état de conformité
             switch ($critere['etat_conformite']) {
                 case 'Conforme':
                     $conforme++;
                     break;
                 case 'En cours de déploiement':
                     $enCoursDeDeploiement++;
                     break;
                 case 'Non conforme':
                     $nonConforme++;
                     break;
                 case 'Non applicable':
                     $nonApplicable++;
                     break;
                 // Ajouter d'autres états si nécessaire
                 default:
                     break;
             }
         } else {
             // Gérer le cas où 'etat_conformite' est absent ou incorrect
             // Éventuellement, afficher un message d'erreur ou appliquer une valeur par défaut
         }
         ?>
         
         <h2>Résultat de l'analyse :</h2>
         <div id="result"></div>

<?php
     // Calcul du score en pourcentage
     $totalCriteres = count($data['criteres']);
     $scoreConformite = ($conforme * 100) / $totalCriteres;

     echo "Score de conformité : " . $scoreConformite . "%";
     // Création du graphique à barres
echo "<canvas id='myBarChart' width='400' height='400'></canvas>";

echo "<script>";
echo "var ctx = document.getElementById('myBarChart').getContext('2d');";
echo "var myBarChart = new Chart(ctx, {
 type: 'bar',
 data: {
     labels: ['Conforme', 'En cours de déploiement', 'Non conforme', 'Non applicable'],
     datasets: [{
         label: 'Score de conformité',
         data: [$conforme, $enCoursDeDeploiement, $nonConforme, $nonApplicable],
         backgroundColor: [
             'rgba(75, 192, 192, 0.2)',
             'rgba(255, 159, 64, 0.2)',
             'rgba(255, 99, 132, 0.2)',
             'rgba(54, 162, 235, 0.2)'
         ],
         borderColor: [
             'rgba(75, 192, 192, 1)',
             'rgba(255, 159, 64, 1)',
             'rgba(255, 99, 132, 1)',
             'rgba(54, 162, 235, 1)'
         ],
         borderWidth: 1
     }]
 },
 options: {
     responsive: true,
     maintainAspectRatio: false,
     title: {
         display: true,
         text: 'Score de conformité en pourcentage'
     },
     scales: {
         yAxes: [{
             ticks: {
                 beginAtZero: true
             }
         }]
     }
 }
});";
echo "</script>";
?>
    </table>
</body>
</html>