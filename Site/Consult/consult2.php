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
    ob_start();
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

	<link rel= "stylesheet" href="../styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
</head>
<body>
    <div class="navbar">
        <a href="#" class="logo">Med&Phar</a>
        <div class="navbar-right">
            <a href = "../Consult/consult.php"> Go back</a>
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
            <div class="consult form-container">
                    <h1> Consultation </h1>
                    <form action="#" method="post">
                        <div class="form-group">
                            Symptoms
                            <input type="text" id="form1" placeholder="What are the symptoms ?" name="symptomes" required>
                        </div> 
                        <br>
                        <div class="form-group">
                            <label for="search">Choose a pathology:</label>
                            <input type="text" id="search" name="search" list="suggestion" autocomplete="off">
                            <datalist id="suggestion"></datalist>
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
                                            let datalist = document.getElementById('suggestion');
                                            options.forEach(item => {
                                                let option = document.createElement('option');
                                                option.value = item;
                                                datalist.appendChild(option);
                                            });
                                        });
                                });
                            </script>
                        </div>
                        <br>
                        <div class="form-group">
                            Cause of illness 
                            <input type="text" id="form1" placeholder="Cause of illness ?" name="cause">
                        </div>
                        <br>
                        <div class="form-group">
                            Is it a hereditary disease?
                            <label>
                                <input type="checkbox" id="oui" name="choix" value="oui">
                                Yes
                            </label>

                            <label>
                                <input type="checkbox" id="non" name="choix" value="non">
                                No
                            </label>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="date">Start Date :</label>
                            <input type="date" id="date" name="date_d" required>
                        </div>
                        <div class="form-group">
                            <label for="date">End Date :</label>
                            <input type="date" id="date" name="date_f" required>
                        </div>
                        <br>
                        <div class="form-group">
                            Link to another disease
                            <input type="text" id="form1" placeholder="Corrélation maladie ?" name="correlation">
                        </div>
                        <br>
                        <button type="submit" name="consult">Find medecine</button>
                    </form>
                </div>
                <?php
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
                    $_SESSION['id_medicament']= null;
                    $_SESSION["id_Maladie"] = "$newId";
                    header("Location:../Result/result.php");
                    exit();
                }
                ob_end_flush();
                ?>
            </div>
        </div>
    </div>
        
</body>

