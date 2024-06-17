<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="mastproject3";
    
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
	<title> Consultation </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="Menu">
            <ul class="menu">
                <li class="menuli"><a href="../Accueil/accueil.php">Retour</a></li>
            </ul>
        </div>

    </header>
    <h1>Consultation </h1>
    <?php
        if (empty($_SESSION['id_Patient'])){
        ?>
            <form action="#" method="post">
                <label for="dropdown">Choisissez un patient :</label>
                <select name="dropdown" id="dropdown">
                    <?php
                        $sql = "SELECT id_patient, prenom, nom FROM patient";
                        $result = $connection->query($sql);
                        
                        if ($result->num_rows > 0) {
                            // Afficher les données dans le menu déroulant
                            while($row = $result->fetch_assoc()) {
                                // Utiliser id_patient comme valeur et afficher prénom et nom
                                echo '<option value="' . $row["id_patient"] . '">' . $row["prenom"] . ' ' . $row["nom"] . '</option>';
                            }
                        } else {
                            echo '<option value="">Aucune donnée trouvée</option>';
                        }
                    ?>
                </select>

                <button type="submit" name="signin">Submit</button>
            </form>

                    <a href="../Form/form.html">Enregistrer nouveau patient</a>

                    

        <?php
            // Vérifier si une option a été sélectionnée
            if (isset($_POST['signin'])) {
                // Récupérer l'id_patient depuis le formulaire
                $id_patient = $_POST['dropdown'];
                echo $id_patient; 
                // Stocker l'id_patient en session
                $_SESSION["id_Patient"] = $id_patient;
                
                // Redirection vers consult.php
                header("Location: consult.php");
                exit(); // Assurez-vous de sortir du script après la redirection
            }
        }else{
            $id_patient = $_SESSION["id_Patient"];
            $sql = "SELECT IFNULL(Consultation.date_consult, 'pas d\'information') as date_consult, 
                   IFNULL(Maladie.nom, 'pas d\'information') AS maladie_nom, 
                   IFNULL(Maladie.id_medicament, 'pas d\'information') AS id_medicament, 
                   IFNULL(patient.nom, 'pas d\'information') AS patient_nom
            FROM Consultation
            LEFT JOIN Maladie ON Consultation.id_maladie = Maladie.id
            LEFT JOIN patient ON Consultation.id_patient = patient.id_patient
            WHERE Consultation.id_patient = $id_patient";

            $result = $connection->query($sql);
            
            // Afficher les résultats dans un tableau HTML
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h2>Historique des consultations pour le patient : " . htmlspecialchars($row['patient_nom']) . "</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>Date de Consultation</th>
                            <th>Maladie</th>
                            <th>ID Médicament</th>
                        </tr>";
                do {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["date_consult"]) . "</td>
                            <td>" . htmlspecialchars($row["maladie_nom"]) . "</td>
                            <td>" . htmlspecialchars($row["id_medicament"]) . "</td>
                        </tr>";
                } while ($row = $result->fetch_assoc());
                echo "</table>";
            } else {
                echo "Aucune consultation trouvée pour ce patient.";
            }
        }

        ?>
    <br><br><br><br>
    <form action="#" method="post">
        Symptome
        <input type="text" id="form1" placeholder="Quels sont les symptomes ?" name="symptomes" required>
        <br>
        <label for="search">Choississez une pathologie :</label>
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
                                    options.add(columns[1].trim()); // Ajoute la valeur de la colonne 2 à l'ensemble
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
        Cause de la maladie 
        <input type="text" id="form1" placeholder="Cause de la maladie ?" name="cause">
        <br>
        Es-ce une maladie héréditaire ? 
        <label>
            <input type="checkbox" id="oui" name="choix" value="oui">
            Oui
        </label>

        <label>
            <input type="checkbox" id="non" name="choix" value="non">
            Non
        </label>
        <br>
        <label for="date">Date début :</label>
        <input type="date" id="date" name="date_d">
        <label for="date">Date fin :</label>
        <input type="date" id="date" name="date_f">
        <br>
        Lien avec une autre maladie 
        <input type="text" id="form1" placeholder="Corrélation maladie ?" name="correlation">
        <br>
        <button type="submit" name="consult">Sing In</button>
    </form>
    <?php
    // Vérifier si une option a été sélectionnée
    if (isset($_POST['consult'])) {

        $symptomes=$_POST['symptomes'];
        $nom=$_POST['search'];
        $date_debut=$_POST['date_d'];
        $date_fin=$_POST['date_f'];
        $maladie_correle=$_POST['correlation'];
        $hereditaire=$_POST['choix'];
        $cause=$_POST['cause'];

        $sql_check = "SELECT id FROM Maladie WHERE symptome = ? AND nom = ? AND date_prise = ? AND fin_prise = ? AND maladie_correle = ? AND hereditaire = ? AND cause = ?";
        $stmt_check = $connection->prepare($sql_check);
        $stmt_check->bind_param("ssddsis", $symptome, $nom, $date_prise, $fin_prise, $maladie_correle, $hereditaire, $cause);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $stmt_check->bind_result($id);
            $stmt_check->fetch();
            
            $_SESSION["id_Maladie"] = 
            $stmt_check->close();
            header("Location:../Result/result.php");
        } else {
            // Préparer la requête SQL
            $newId = generateUniqueId($connection);

            if ($newId !== null) {
                // Préparer la requête d'insertion avec des placeholders sécurisés
                $sql = "INSERT INTO Maladie (id, symptome, nom, date_prise, fin_prise, maladie_correle, hereditaire, cause) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                // Préparer et exécuter la requête préparée
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("issddsis", $newId, $symptomes, $nom, $date_debut, $date_fin, $maladie_correle, $hereditaire, $cause);
                
                if ($stmt->execute()) {
                    echo "Nouvelle maladie ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout de la maladie : " . $stmt->error;
                }
                
                // Fermer le statement
                $stmt->close();
            } else {
                echo "Erreur lors de la génération de l'ID unique.";
            }

            $_SESSION["id_Maladie"] = "$newId";
            header("Location:../Result/result.php");
        }
    }
    ?>