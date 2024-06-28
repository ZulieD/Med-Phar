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
            $sql="SELECT COUNT(*) as count FROM maladie WHERE id= $uniqueId";
            $result = $conn->query($sql);
            // Si l'ID n'est pas utilisé, quitter la boucle
            $i++;
            $row = $result->fetch_assoc();
            $count = $row['count'];
        } while ($count > 0);
        
        return $uniqueId;
    }
    function generateUniqueId2($conn) {
        $i=1;
        do {
            // Générer un ID unique
            $uniqueId = $i;
            
            // Préparer une requête pour vérifier si cet ID est déjà utilisé
            $sql="SELECT COUNT(*) as count FROM Reaction WHERE id= $uniqueId";
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consultation</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>

</head>

<body>
    <div class="navbar">
        <a href="#" class="logo">Med&Phar</a>
        <div class="navbar-right">
            <a href = "../Accueil/accueil.php"> Go back</a>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">

        </div>
    </div>

    <div class="container">
        <div class="sidebar"><h1>About Med&Phar</h1>
                <p><strong>Who?</strong></p>
                <p>Med&Phar is a dedicated team working towards enhancing the daily tasks of physicians, providing crucial support during consultations.</p>
                
                <p><strong>When?</strong></p>
                <p>Med&Phar operates every day, providing continuous support for medical professionals.</p>

                <p><strong>How?</strong></p>
                <p>By utilizing logistic and graphical data analysis, Med&Phar offers support through a web platform accessible to all specialties, employing technologies like SQL, Python, HTML, and PHP.</p>

                <p><strong>What?</strong></p>
                <p>Med&Phar provides daily support for doctors with:</p>
                <ul>
                    <li>Patient Form</li>
                    <li>Logistic and graphical data analysis</li>
                    <li>Medication information</li>
                    <li>Personalized advice and follow-up</li>
                    <li>Risk analysis associated with prescriptions</li>
                    <li>Assistance in prescription and patient management</li>
                    <li>Unique analysis for diagnostics</li>
                </ul>

                <p><strong>Why?</strong></p>
                <p>The goal of Med&Phar is to improve the efficiency of medication prescriptions and to provide unique diagnostic analysis, supporting doctors in their daily practice.</p>
                <br>        
            </div>
        <div class="main-content">
            <?php
                ob_start();
                if (empty($_SESSION['id_Patient'])){
                    $id_medecin = $_SESSION["id_Medecin"];
                ?>

                <div class="information form-container">
                    <h1> Information of the patient </h1>
                    <form action="#" method="post">
                    <div class="form-group">
                        <label for="dropdown">Pick a patient :</label>
                        <select name="dropdown" id="dropdown">
                    </div>
                        <?php
                            
                            $sql = "SELECT DISTINCT patient.id_patient, prenom, nom FROM patient 
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
                        <button type="submit" name="register">Register a new patient</button>
                    </form>
                </div>
                            

                <?php
                    // Vérifier si une option a été sélectionnée
                    if (isset($_POST['register'])){
                        header("Location: ../Form/form.html");
                    }
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
                    ?>
                    <div class="information form-container">
                        <h1> Information of the patient </h1>
                        <?php
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
                            <div class="form-group">
                                <label for="poids">Contraception :</label>
                                <input type="text" id="contraception" name="contraception" placeholder="Entrez la nouvelle contraception ">
                            </div>    
                            <br>
                            <div class="form-group">
                                <label for="taille">Weight :</label>
                                <input type="text" id="poids" name="poids" placeholder="Entrez la nouveau poids">
                            </div>   
                            <br>
                            <div class="form-group">
                                <label for="taille">Height :</label>
                                <input type="text" id="taille" name="nouvelle_taille" placeholder="Entrez la nouvelle taille">
                            </div>    
                            <br>
                            <div class="form-group">
                                <label for="taille">Allergies :</label>
                                <input type="text" id="allergie" name="allergie" placeholder="Entrez la nouvelle allergie">
                            </div>  
                            <br>
                            <div class="form-group">
                                <label for="activite_metier">Job Level Activity - 1 to 5</label>
                                <input type="number" id="activite_metier" name="activite_metier">
                            </div>
                            <div class="form-group">
                                <label for="risque_metier">Job Risk Level - 1 to 5</label>
                                <input type="number" id="risque_metier" name="risque_metier">
                            </div>
                            <div class="form-group">
                                <label for="activite_quotidienne">Daily Activity Level - 1 to 5</label>
                                <input type="number" id="activite_quotidienne" name="activite_quotidienne">
                            </div>
                            <div class="form-group">
                                <label for="qualite_alimentation">Quality of Diet - 1 to 5</label>
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
                    function getMedicationsFromCSV($csvFilePath) {
                        $medications = array();
                        
                        if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
                            // Skip the header row
                            fgetcsv($handle, 1000, ",");
                            
                            // Read the data rows
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $id = $data[2]; // Assuming the ID is in the first column
                                $name = $data[0]; // Assuming the name is in the second column
                                $medications[$id] = $name;
                            }
                            
                            fclose($handle);
                        }
                        
                        return $medications;
                    }

                    $medications = getMedicationsFromCSV('../../Analyse/df_medicament.csv');
                    $effet_secondaire= getMedicationsFromCSV('../../Analyse/df_effet_secondaire.csv');

                    
                    // Afficher les résultats dans un tableau HTML
                    // Read the CSV file to get the medications array
                    
                    $sql = "SELECT IFNULL(Consultation.date_consult, 'pas d\'information') as date_consult, 
                                IFNULL(Maladie.nom, 'pas d\'information') AS maladie_nom, 
                                IFNULL(Maladie.id_medicament, 'pas d\'information') AS id_medicament, 
                                IFNULL(patient.nom, 'pas d\'information') AS patient_nom,
                                IFNULL(Reaction.id_effet_secondaire,'pas d\'information') AS side_effect
                            FROM Consultation
                            LEFT JOIN Maladie ON Consultation.id_maladie = Maladie.id
                            LEFT JOIN patient ON Consultation.id_patient = patient.id_patient
                            LEFT JOIN Reaction ON Consultation.id_patient = Reaction.id_patient and Maladie.id_medicament=Reaction.id_medicament
                            WHERE Consultation.id_patient = $id_patient";

                    $result = $connection->query($sql);
                    echo "<h2>History of consultations for the patient : </h2>";
                    // Afficher les résultats dans un tableau HTML
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        
                        echo "<table border='1'>
                                <tr>
                                    <th>Date of Consultation</th>
                                    <th>Disease</th>
                                    <th>Name Medicine</th>
                                    <th> Side effect </th>
                                </tr>";
                        do {
                            $id_medicament= $row["id_medicament"];
                            $_SESSION['id_medicament']=$id_medicament;
                            $medicament_name = isset($medications[$row["id_medicament"]]) ? $medications[$row["id_medicament"]] : 'Unknown';
                            $effet_secondaire_name = isset($effet_secondaire[$row["side_effect"]]) ? $effet_secondaire[$row["side_effect"]] : 'Unknown';
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["date_consult"]) . "</td>
                                    <td>" . htmlspecialchars($row["maladie_nom"]) . "</td>
                                    <td>" . htmlspecialchars($medicament_name) . "</td>
                                    <td>" . htmlspecialchars($effet_secondaire_name) . "</td>
                                </tr>";
                        } while ($row = $result->fetch_assoc());
                        echo "</table>";
                        ?>
                        <section class="faq-container">
                            <h3 class="faq-page">Add a side effect</h3>
                            <form method="POST" action=" ">
                                <div class="form-group">
                        <?php
                        $result->data_seek(0);

                        echo "<select name='consultation_history'>";
                        $is_first_row = true;
                        do {
                            if ($is_first_row) {
                                // Ignorer la première ligne
                                $is_first_row = false;
                                continue;
                            }
                            $id_medicament = $row["id_medicament"];
                            $medicament_name = isset($medications[$row["id_medicament"]]) ? $medications[$row["id_medicament"]] : 'Unknown';
                            $option_text = htmlspecialchars($row["date_consult"]) . " - " . htmlspecialchars($row["maladie_nom"]) . " - " . htmlspecialchars($medicament_name);
                            echo "<option value='" . htmlspecialchars($row["id_medicament"]) . "'>" . $option_text . "</option>";
                        } while ($row = $result->fetch_assoc());
                        echo "</select>";
                        ?>
                                </div>
                                <div class="form-group">
                                    <input list="suggestions" id="inputField" name="search" placeholder="Type to search...">
                                    <datalist id="suggestions"></datalist>
                                    <input type="hidden" id="hiddenField" name="column2_value">

                                </div>
                                <button type="submit" name="effect">Submit</button>

                            </form>
                        </section>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                fetch('../../Analyse/df_effet_secondaire.csv')
                                    .then(response => response.text())
                                    .then(data => {
                                        Papa.parse(data, {
                                            header: true,
                                            skipEmptyLines: true,
                                            complete: function(results) {
                                                let options = {};
                                                results.data.forEach(row => {
                                                    options[row.PT_NAME_FR.trim()] = row.id_effet_secondaire.trim();
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
                                            }
                                        });
                                    });
                            });
                            </script>
                        <?php
                    } else {
                        echo "No data found for this patient.";
                    }

                }
    
                ?>
                
                <hr class="hr-line">
                <br>
                
                
                <?php 
                    if (isset($_POST['effect'])) {
            
                        $id_unique = generateUniqueId2($connection);
                        $id_medicament = $_POST['consultation_history'];
                        $id_effet_secondaire = $_POST['column2_value'];
                        //echo $_POST['search'];
                        //echo $id_effet_secondaire;
                        $id_patient=$_SESSION['id_Patient'];
                        $sql="INSERT into Reaction (id, id_medicament, id_patient, id_effet_secondaire)  values(?,?,?,?) ";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param("iiii", $id_unique, $id_medicament, $id_patient, $id_effet_secondaire);
                        $stmt->execute();
                        
                        // Fermer le statement
                        $stmt->close();
                        header("Location: consult.php");
                    }
                    
                ?>
            </div>
            <div class="consult form-container">
                <form method="POST" action="">
                    <button type="submit" name="finish">Consultation</button>
                </form>
            </div>
            <?php
                if (isset($_POST['finish'])) {
                    header("Location: ../Consult/consult2.php");
                    exit();
                }
                ?>
        </div>
            
    </div>
</body>
