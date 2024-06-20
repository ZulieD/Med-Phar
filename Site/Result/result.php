<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="masterproject4";
    
    $connection=mysqli_connect($host,$username,$password,$database);
    // Check connection
    if ($connection==false)
    {
        die('Error in connection' .mysqli_connect_error()); // echo plusieur fois et ferme le programme
    }
    session_start(); // connection avec tous les pages pour passer variable
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title> Result  </title> <!--Nom de la page -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel= "stylesheet" href="style.css">
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
    <h2>Here are the medications recommended for Mr/Mrs’ pathology
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
    <form action="#" method="post">
    <label for="search">Choose the medication to prescribe:</label>
        <input type="text" id="search" name="search" list="suggestions" autocomplete="off">
        <datalist id="suggestions"></datalist>
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
                        let options = new Set();
                        rows.forEach(row => {
                            let trimmedRow = row.trim();
                            if (trimmedRow.length > 0) {
                                let columns = trimmedRow.split(','); // Sépare les colonnes par la virgule
                                if (columns.length > 1) {
                                    options.add(columns[0].trim()); // Ajoute la valeur de la colonne 2 à l'ensemble
                                }
                            }
                        });

                        // Créer les options dans le datalist
                        let datalist = document.getElementById('suggestions');
                        options.forEach(item => {
                            let option = document.createElement('option');
                            option.value = item;
                            datalist.appendChild(option);
                        });
                    });
            });
        </script>
        <br>
        <button type="submit" name="consult">Prescribe</button>
    </form>
    <?php
    if (isset($_POST['consult'])) {
        $medicament=$_POST["search"];
        $id_patient = $_SESSION["id_Patient"];
        //echo $medicament;
        $sql="SELECT * from patient where id_patient= $id_patient";
        $result_3=$connection->query($sql);
        
        if ($result_3->num_rows > 0) {
            $row_3 = $result_3->fetch_assoc();

            $command = escapeshellcmd('python3 test.py ');
            
            // Exécution de la commande
            $output = shell_exec($command);
            echo "Output: <pre>$output</pre>";
        }
        
    }
    ?>



</body>