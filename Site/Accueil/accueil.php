<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="masterproject2";
    
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
	<title> Accueil  </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="style.css">
</head>
<?php
    $_SESSION['id_Patient']=null
?>
<body>
<h1>
    Bonjour Docteur <?php
        // Vérifier d'abord si $_SESSION['id_Medecin'] est défini
        if (isset($_SESSION['id_Medecin'])) {
            // Récupérer l'ID du médecin depuis la session
            $id_medecin = $_SESSION['id_Medecin'];
            
            // Préparer la requête SQL pour obtenir le nom du médecin
            $sql = "SELECT nom FROM Medecin WHERE id = $id_medecin";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                // Récupérer la première ligne de résultat (normalement, il ne devrait y en avoir qu'une)
                $row = $result->fetch_assoc();
                echo htmlspecialchars($row['nom']);
            } else {
                echo "Médecin introuvable";
            }
        } else {
            echo "ID de médecin non défini";
        }
    ?>
</h1>

    <header>
        <div class="Menu">
            <ul class="menu">
                <li class="menuli"><a href="../Consult/consult.php">Consultation</a></li>
                <li class="menuli"><a href="../Login/login.html">Retour</a></li>
            </ul>
        </div>

    </header>