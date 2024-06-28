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
            <div class=" effect form-container">
                <h1>Add a side effect</h1>
                <form method="POST" action="add_effect.php ">
                    <input list="suggestions" id="inputField" name="search" placeholder="Type to search...">
                    <datalist id="suggestions"></datalist>
                    <input type="hidden" id="hiddenField" name="column2_value">
                    <button type="submit" name="consult">Submit</button>
                </form>
                <br>
            </div>
        </div>

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
                if (isset($_POST['consult'])) {
                    if (isset($_GET['id_medicament'])) {
                        $id_unique = generateUniqueId($connection);
                        $id_medicament = $_GET['id_medicament'];
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
                    } else {
                        echo "Aucun ID de médicament fourni.";
                    }
                }
            ?>
    
    </div>
        
</body>

