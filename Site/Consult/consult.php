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

    function generateUniqueId($conn) {
        $i=1;
        do {
            // Générer un ID unique
            $uniqueId = $i;
            
            // Préparer une requête pour vérifier si cet ID est déjà utilisé
            $sql="SELECT COUNT(*) as count FROM maladie WHERE id= $uniqueId";
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
	<title> Consultation </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="styles_consult.css">
    
</head>
<body>
    <header>
        <div class="Menu">
            <ul class="menu">
                <li class="menuli"><a href="../Accueil/accueil.php">Go back</a></li>
            </ul>
        </div>

    </header>
    <h1>Consultation </h1>
    <?php
        ob_start();
        if (empty($_SESSION['id_Patient'])){
        ?>
            <form action="#" method="post">
                <label for="dropdown">Pick a patient :</label>
                <select name="dropdown" id="dropdown">
                <?php
                    $id_medecin = $_SESSION["id_Medecin"];
                    $sql = "SELECT patient.id_patient, prenom, nom FROM patient 
                            INNER JOIN Consultation ON Consultation.id_patient = patient.id_patient
                            WHERE Consultation.id_medecin = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("i", $id_medecin);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        // Afficher les données dans le menu déroulant
                        while($row = $result->fetch_assoc()) {
                            // Utiliser id_patient comme valeur et afficher prénom et nom
                            echo '<option value="' . htmlspecialchars($row["id_patient"]) . '">' . htmlspecialchars($row["prenom"]) . ' ' . htmlspecialchars($row["nom"]) . '</option>';
                        }
                    } else {
                        echo '<option value="">No data found</option>';
                    }

                    $stmt->close();
        
                ?>

                </select>

                <button type="submit" name="signin">Submit</button>
            </form>

                    <a href="../Form/form.html">Register a new patient</a>

                    

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
                ob_end_flush();
            }
        ?>
        <?php
        }else{
            ob_start();
            $id_patient = $_SESSION["id_Patient"];
            $sql="SELECT prenom, nom, date_naissance, sexe, contraception, poids, taille, allergie, activite_metier, risque_metier, activite_quotidienne, qualite_alimentation from patient 
                where id_patient=$id_patient";
            $result = $connection->query($sql);

            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo "<h2>Personnal information of patient : " . htmlspecialchars($row['nom']) . "</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>Fisrt Name</th>
                            <th>Last Name</th>
                            <th>Date of birth</th>
                            <th>Sex</th>
                            <th>Contraception</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Allergies</th>
                            <th>Job activity level</th>
                            <th>Job risk level </th>
                            <th>Daily activity level </th>
                            <th>Quality od diet</th>
                        </tr>";
                    echo "<tr>
                            <td>" . htmlspecialchars($row["prenom"]) . "</td>
                            <td>" . htmlspecialchars($row["nom"]) . "</td>
                            <td>" . htmlspecialchars($row["date_naissance"]) . "</td>
                            <td>" . htmlspecialchars($row["sexe"]) . "</td>
                            <td>" . htmlspecialchars($row["contraception"]) . "</td>
                            <td>" . htmlspecialchars($row["poids"]) . "</td>
                            <td>" . htmlspecialchars($row["taille"]) . "</td>
                            <td>" . htmlspecialchars($row["allergie"]) . "</td>
                            <td>" . htmlspecialchars($row["activite_metier"]) . "</td>
                            <td>" . htmlspecialchars($row["risque_metier"]) . "</td>
                            <td>" . htmlspecialchars($row["activite_quotidienne"]) . "</td>
                            <td>" . htmlspecialchars($row["qualite_alimentation"]) . "</td>
                        </tr>";
                    echo "</table>";
            
            ?>

            <br>
            <section class="faq-container">
                <h3 class="faq-page"> Change patient's informations</h3>
                <form action="#" method="post" class="faq-body">
                    <label for="poids">Contraception :</label>
                    <input type="text" id="contraception" name="contraception" placeholder="Entrez la nouvelle contraception ">
                    <br>
                    <label for="taille">Weight :</label>
                    <input type="text" id="poids" name="poids" placeholder="Entrez la nouveau poids">
                    <br>
                    <label for="taille">Height :</label>
                    <input type="text" id="taille" name="nouvelle_taille" placeholder="Entrez la nouvelle taille">
                    <br>
                    <label for="taille">Allergies :</label>
                    <input type="text" id="allergie" name="allergie" placeholder="Entrez la nouvelle allergie">
                    <br>
                    <div class="form-group">
                    <label for="job-type">Work</label>
                    <select id="job-type" name="job-type" >
                        <option value="" disabled selected>Select your job type</option>
                        <option value="student">Student</option>
                        <option value="unemployed">Unemployed</option>
                        <option value="employed">Employed</option>
                        <option value="self-employed">Self-employed</option>
                        <option value="retired">Retired</option>
                        <option value="other">Other</option>
                    </select>
                    </div>
                    <div class="form-group">
                        <label for="activite_metier">Job Activity Level</label>
                        <input type="number" id="activite_metier" name="activite_metier">
                    </div>
                    <div class="form-group">
                        <label for="risque_metier">Job Risk Level</label>
                        <select id="risque_metier" name="risque_metier">
                            <option value="" disabled selected>Select your risk level</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="very-high">Very High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activite_quotidienne">Daily Activity Level</label>
                        <input type="number" id="activite_quotidienne" name="activite_quotidienne">
                    </div>
                    <div class="form-group">
                        <label for="qualite_alimentation">Quality of Diet</label>
                        <input type="number" id="qualite_alimentation" name="qualite_alimentation">
                    </div>
                    <button type="submit" name="modifier">Changes informations</button>
                </form>
            <hr class="hr-line">
            </section>
            <script src="consult.js"></script>
            <?php
                } else {
                    echo "No data found for this patient.";
                }

                

                // Traitement de la modification du poids et de la taille
                if (isset($_POST['modifier'])) {
                    $contraception = isset($_POST['contraception']) ? $_POST['contraception'] : null;
                    $poids = isset($_POST['poids']) ? $_POST['poids'] : null;
                    $taille = isset($_POST['nouvelle_taille']) ? $_POST['nouvelle_taille'] : null;
                    $allergie = isset($_POST['allergie']) ? $_POST['allergie'] : null;
                    $job_type = isset($_POST['job-type']) ? $_POST['job-type'] : null;
                    $activite_metier = isset($_POST['activite_metier']) ? $_POST['activite_metier'] : null;
                    $risque_metier = isset($_POST['risque_metier']) ? $_POST['risque_metier'] : null;
                    $activite_quotidienne = isset($_POST['activite_quotidienne']) ? $_POST['activite_quotidienne'] : null;
                    $qualite_alimentation = isset($_POST['qualite_alimentation']) ? $_POST['qualite_alimentation'] : null;

                    // Préparer la requête SQL de mise à jour
                    $sql_update = "UPDATE patient SET ";
                    $params = array();

                    if (!empty($contraception)) {
                        $sql_update .= "contraception=?, ";
                        $params[] = $contraception;
                    }
                    if (!empty($poids)) {
                        $sql_update .= "poids=?, ";
                        $params[] = $poids;
                    }
                    if (!empty($taille)) {
                        $sql_update .= "taille=?, ";
                        $params[] = $taille;
                    }
                    if (!empty($allergie)) {
                        $sql_update .= "allergie=?, ";
                        $params[] = $allergie;
                    }
                    if (!empty($job_type)) {
                        $sql_update .= "activite_metier=?, ";
                        $params[] = $job_type;
                    }
                    if (!empty($activite_metier)) {
                        $sql_update .= "activite_metier=?, ";
                        $params[] = $activite_metier;
                    }
                    if (!empty($risque_metier)) {
                        $sql_update .= "risque_metier=?, ";
                        $params[] = $risque_metier;
                    }
                    if (!empty($activite_quotidienne)) {
                        $sql_update .= "activite_quotidienne=?, ";
                        $params[] = $activite_quotidienne;
                    }
                    if (!empty($qualite_alimentation)) {
                        $sql_update .= "qualite_alimentation=?, ";
                        $params[] = $qualite_alimentation;
                    }

                    // Retirer la virgule et l'espace à la fin de la requête SQL
                    $sql_update = rtrim($sql_update, ", ");

                    // Ajouter la condition WHERE pour le patient spécifique
                    $sql_update .= " WHERE id_patient=?";
                    $params[] = $id_patient;

                    // Préparer et exécuter la requête de mise à jour
                    $stmt_update = $connection->prepare($sql_update);
                    if ($stmt_update) {
                        // Lier les paramètres et exécuter la requête
                        $types = str_repeat("s", count($params)); // Tous les paramètres sont des chaînes de caractères
                        $stmt_update->bind_param($types, ...$params);
                        $stmt_update->execute();
                        
                        // Vérifier si la mise à jour a réussi
                        if ($stmt_update->affected_rows > 0) {
                            echo "<p>Information update.</p>";
                        } else {
                            echo "<p>No change made.</p>";
                        }
                        $stmt_update->close();
                        header("Location: consult.php");
                        exit(); // Assurez-vous de sortir du script après la redirection
                        
                    } else {
                        echo "<p>Error on the update.</p>";
                    }

                    
                }
            
            ob_end_flush();
            ?>

            <?php
            ob_start();
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
                echo "<h2>History of consultations for the patient : " . htmlspecialchars($row['patient_nom']) . "</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>Date of Consultation</th>
                            <th>Disease</th>
                            <th>Name Medicine</th>
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
                echo "No data found for this patient.";
            }
        }
        ob_end_flush();
        ?>
    <br><br><br><br>
    <form action="#" method="post">
        Symptoms
        <input type="text" id="form1" placeholder="What are the symptoms ?" name="symptomes" required>
        <br>
        <label for="search">Choose a pathology:</label>
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
        Cause of illness 
        <input type="text" id="form1" placeholder="Cause of illness ?" name="cause">
        <br>
        Is it a hereditary disease?
        <label>
            <input type="checkbox" id="oui" name="choix" value="oui">
            Yes
        </label>

        <label>
            <input type="checkbox" id="non" name="choix" value="non">
            No
        </label>
        <br>
        <label for="date">Start Date :</label>
        <input type="date" id="date" name="date_d" required>
        <label for="date">End Date :</label>
        <input type="date" id="date" name="date_f" required>
        <br>
        Link to another disease
        <input type="text" id="form1" placeholder="Corrélation maladie ?" name="correlation">
        <br>
        <button type="submit" name="consult">Sing In</button>
    </form>
    <?php
    ob_start();
    // Vérifier si une option a été sélectionnée
    if (isset($_POST['consult'])) {

        $symptomes=$_POST['symptomes'];
        $nom=$_POST['search'];
        $maladie_correle=$_POST['correlation'];
        $hereditaire=$_POST['choix'];
        $cause=$_POST['cause'];
        $date_d = $_POST['date_d'];
        $date_f = $_POST['date_f'];

        $newId = generateUniqueId($connection);

        if ($newId !== null) {
            // Préparer la requête d'insertion avec des placeholders sécurisés
            $sql = "INSERT INTO Maladie (id, symptome, nom, debut_prise, fin_prise, maladie_correle, hereditaire, cause) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Préparer et exécuter la requête préparée
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssssssis", $newId, $symptomes, $nom, $date_d, $date_f, $maladie_correle, $hereditaire, $cause);
            $stmt->execute();
            
            // Fermer le statement
            $stmt->close();
        } else {
            echo "Error on the id.";
        }

        $_SESSION["id_Maladie"] = "$newId";
        header("Location:../Result/result.php");
        exit();
    }
    ob_end_flush();
    ?>