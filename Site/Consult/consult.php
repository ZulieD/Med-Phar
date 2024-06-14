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
            
            <div class="Menu">
                <ul class="menu">
                    <li class="menuli"><a href="../Form/form.html">Enregistrer nouveau patient</a></li>
                </ul>
            </div>

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
    <br>
    <form action="#" method="post">
        Symptome
        <input type="text" id="form1" placeholder="Quels sont les symptomes ?" name="uname" required>

        Maladie identifier 
        <input type="text" id="form1" placeholder="Quel est la maladie ?" name="uname">

        Lier à une autre maladie 
        <input type="text" id="form1" placeholder="Corrélation maladie ?" name="uname">

        <button type="submit" name="consult">Sing In</button>
    </form>
    <?php
    // Vérifier si une option a été sélectionnée
    if (isset($_POST['consult'])) {
        $_SESSION["Patient"] = "$nom";
        header("Location:../Result/result.php");
    }
    ?>