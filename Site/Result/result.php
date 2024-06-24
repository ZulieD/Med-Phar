<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="masterproject5";
    
    $connection=mysqli_connect($host,$username,$password,$database);
    // Check connection
    if ($connection==false)
    {
        die('Error in connection' .mysqli_connect_error()); // echo plusieur fois et ferme le programme
    }
    session_start(); // connection avec tous les pages pour passer variable

    function generateUniqueId($conn) {
        $i=1;
        do {
            // Générer un ID unique
            $uniqueId = $i;
            
            // Préparer une requête pour vérifier si cet ID est déjà utilisé
            $sql="SELECT COUNT(*) as count FROM Consultation WHERE id= $uniqueId";
            $result = $conn->query($sql);
            // Si l'ID n'est pas utilisé, quitter la boucle
            $i++;
            $row = $result->fetch_assoc();
            $count = $row['count'];
        } while ($count > 0);
        
        return $uniqueId;
    }
    
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title> Result  </title> <!--Nom de la page -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel= "stylesheet" href="styles_result.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
</head>
<body>
    <?php
    //echo $_SESSION["id_Maladie"];
    $id_maladie = $_SESSION["id_Maladie"];
    
    $sql = "SELECT *
            FROM Maladie
            WHERE id = $id_maladie";
    $result = $connection->query($sql);

    //echo $_SESSION['id_Patient'];
    $id_patient=$_SESSION['id_Patient'];
    
    $sql="SELECT * from patient where id_patient= $id_patient";
    $result_2=$connection->query($sql);

    // Afficher les résultats dans un tableau HTML
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($result_2->num_rows > 0) {
            $row_2 = $result_2->fetch_assoc();

            $command = escapeshellcmd('python3 model.py ' . 
                         escapeshellarg($row['nom']) . ' ' . 
                         escapeshellarg($row_2['sexe']) . ' ' . 
                         escapeshellarg($row_2['date_naissance']) . ' ' . 
                         escapeshellarg($row_2['poids']) . ' ' . 
                         escapeshellarg($row_2['taille']) . ' 2>&1');
            $output = shell_exec($command);
            //echo "Output: <pre>$output</pre>";
        }

    }
    
    ?>
    <h2>Here are the medications recommended for the pathology of Mr/Mrs 
        <?php $id_patient = $_SESSION["id_Patient"];
            $sql="SELECT nom from patient 
                where id_patient=$id_patient";
            $result = $connection->query($sql);

            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo $row['nom'];
            }?></h2>
    <?php
    $chemin_fichier_csv = '../../Analyse/df_medicament.csv';
    if (($handle = fopen($chemin_fichier_csv, "r")) !== false) {
        $result = json_decode($output);
        if ($result !== null && is_array($result)) {
            // Itérer sur chaque élément du tableau $result
            foreach ($result as $id_recherche) {
                // Réinitialiser les résultats pour chaque ID
                //echo "id = $id_recherche";
                $medicament_trouve = false;
    
                // Parcourir chaque ligne du fichier CSV
                while (($ligne = fgetcsv($handle, 1000, ",")) !== false) {
                    // Convertir l'ID en entier pour la comparaison
                    $id_ligne = (int) $ligne[2]; // Supposons que l'ID est dans la troisième colonne (index 2)
                    //echo "ligne = $id_ligne";
                    // Vérifier si l'ID correspond à celui recherché
                    if ($id_ligne === $id_recherche) {
                        // Récupérer d'autres valeurs de la ligne si nécessaire
                        $medicament = $ligne[0]; // Supposons que vous voulez récupérer le nom du médicament (première colonne)
        
                        // Afficher les résultats ou effectuer d'autres actions
                        echo "$medicament<br>";
                        $medicament_trouve = true;
                        break; // Arrêter la recherche après avoir trouvé la première correspondance
                    }
                }
        
                // Si aucun médicament correspondant n'est trouvé pour cet ID
                if (!$medicament_trouve) {
                    echo "No medecine found of the id : $id_recherche<br>";
                }
        
                // Réinitialiser le pointeur du fichier CSV pour la prochaine recherche
                fseek($handle, 0);
            }
        fclose($handle); // Fermer le fichier CSV
        }
    } else {
        echo "Error on the opening of the csv.";
    }
    ?>
    <hr class="hr-line">
    <br>
    <form method="POST" action="">
        <input list="suggestions" id="inputField" name="search" placeholder="Type to search...">
        <datalist id="suggestions"></datalist>
        <input type="hidden" id="hiddenField" name="column2_value">
        <button type="submit" name="consult">Prescribe</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('../../Analyse/df_medicament.csv')
                .then(response => response.text())
                .then(data => {
                    // Séparer les lignes du fichier CSV
                    let rows = data.split('\n');
                    
                    // Enlever la première ligne (l'entête)
                    rows.shift();
                    
                    // Enlever les lignes vides et traiter les données restantes
                    let options = {};
                    rows.forEach(row => {
                        let trimmedRow = row.trim();
                        if (trimmedRow.length > 0) {
                            let columns = trimmedRow.split(','); // Sépare les colonnes par la virgule
                            if (columns.length > 2) {
                                options[columns[0].trim()] = columns[2].trim(); // Stocke la paire colonne 0 -> colonne 2
                            }
                        }
                    });

                    // Créer les options dans le datalist
                    let datalist = document.getElementById('suggestions');
                    for (let key in options) {
                        let option = document.createElement('option');
                        option.value = key;
                        datalist.appendChild(option);
                    }

                    // Ajouter un événement pour mettre à jour le champ caché lors de la sélection
                    document.getElementById('inputField').addEventListener('input', function() {
                        let inputValue = this.value;
                        document.getElementById('hiddenField').value = options[inputValue] || '';
                    });
                });
        });
    </script>
    <?php
    if (isset($_POST['consult'])) {
        $medicament = $_POST["search"];
        $id_medicament = $_POST['column2_value'];

        // Stockage dans les variables de session
        $_SESSION['medicament'] = $medicament;
        $_SESSION['id_medicament'] = $id_medicament;

        // Affichage des valeurs des sessions pour débogage
        echo "Médicament: " . $_SESSION['medicament'] . "<br>";
        echo "ID Médicament: " . $_SESSION['id_medicament'] . "<br>";

        // Récupération de l'ID du patient
        $id_patient = $_SESSION["id_Patient"];

        //echo $medicament;
        $sql="SELECT * from patient where id_patient= $id_patient";
        $result_3=$connection->query($sql);
        
        ?>
        <h2>Répartition par sexe des utilisations de Diclectin</h2>
        <canvas id="genderChart" width="40" height="40"></canvas>
        
        <h2>Répartition des décès de Diclectin</h2>
        <canvas id="deathChart" width="40" height="40"></canvas>
        
        <h2>Anomalies congénitales de Diclectin</h2>
        <canvas id="anomalyChart" width="40" height="40"></canvas>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
            // Gender Chart
            var genderCtx = document.getElementById('genderChart').getContext('2d');
            var genderChart = new Chart(genderCtx, {
                type: 'pie',
                data: {
                labels: ['Homme', 'Femme'],
                datasets: [{
                    data: [12, 8],  // Remplacer par les valeurs réelles de `sexe_counts`
                    backgroundColor: ['lightblue', 'lightpink'],
                    hoverOffset: 4
                }]
                },
                options: {
                plugins: {
                    title: {
                    display: true,
                    text: 'Répartition par sexe des utilisations de Diclectin'
                    }
                }
                }
            });

            // Death Chart
            var deathCtx = document.getElementById('deathChart').getContext('2d');
            var deathChart = new Chart(deathCtx, {
                type: 'pie',
                data: {
                labels: ['Mort', 'Vivant'],
                datasets: [{
                    data: [5, 15],  // Remplacer par les valeurs réelles de `sexe_counts`
                    backgroundColor: ['red', 'lightgreen'],
                    hoverOffset: 4
                }]
                },
                options: {
                plugins: {
                    title: {
                    display: true,
                    text: 'Répartition des décès de Diclectin'
                    }
                }
                }
            });

            // Anomaly Chart
            var anomalyCtx = document.getElementById('anomalyChart').getContext('2d');
            var anomalyChart = new Chart(anomalyCtx, {
                type: 'pie',
                data: {
                labels: ['Oui', 'Non'],
                datasets: [{
                    data: [3, 17],  // Remplacer par les valeurs réelles de `sexe_counts`
                    backgroundColor: ['pink', 'lightyellow'],
                    hoverOffset: 4
                }]
                },
                options: {
                plugins: {
                    title: {
                    display: true,
                    text: 'Anomalies congénitales de Diclectin'
                    }
                }
                }
            });
            });
        </script>
        <?php

        
        
    }
    ?>
    <form method="POST" action="">
        <button type="submit" name="valid">Validate the prescription</button>
    </form>
    <?php
    if (isset($_POST['valid'])) {

        echo "Session Médicament: " . $_SESSION['medicament'] . "<br>";
        echo "Session ID Médicament: " . $_SESSION['id_medicament'] . "<br>";
        $medicament=$_SESSION['medicament'];
        $id_maladie = $_SESSION["id_Maladie"];
        $id_unique = generateUniqueId($connection);
        $date_consult = date('Y-m-d'); // Par exemple, la date actuelle
        $id_medecin = $_SESSION["id_Medecin"];
        $id_patient = $_SESSION['id_Patient'];
        $id_medicament=$_SESSION['id_medicament'];
        echo $id_medicament;
        echo $id_maladie;
        $sql_update = "UPDATE Maladie SET id_medicament = ? WHERE id = ?";
        $stmt_update = $connection->prepare($sql_update);
        $stmt_update->bind_param("ii", $id_medicament, $id_maladie);
        if ($stmt_update->execute()) {
            echo "Mise à jour réussie<br>";
        } else {
            echo "Erreur lors de la mise à jour : " . $stmt_update->error . "<br>";
        }
        $stmt_update->close();

        $sql="SELECT * from Consultation where date_consult = ? and   id_medecin = ? and  id_patient = ? and  id_maladie = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("siii", $date_consult, $id_medecin, $id_patient, $id_maladie);
        $stmt->execute();
        $result = $stmt->get_result();
                    
        if ($result->num_rows == 0) {
            $sql="INSERT into Consultation (id, date_consult, id_medecin, id_patient, id_maladie) values ( ?,?,?,?,?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("isiii", $id_unique, $date_consult, $id_medecin, $id_patient, $id_maladie);
            $stmt->execute();
            $stmt->close();
        }

        echo $medicament, ' prescrit';
        // Fermer le statement
        
    }
    ?>
    <li class="menuli"><a href="../Accueil/homepage.html">Finish prescription</a></li>
</body>