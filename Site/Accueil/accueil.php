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
	<title> Accueil  </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="style.css">
</head>
<?php
    $_SESSION['id_Patient']=null
?>
<body>
<h1>
     <?php
        // Vérifier d'abord si $_SESSION['id_Medecin'] est défini
        if (!empty($_SESSION['Nom_medecin'])) {
            // Récupérer l'ID du médecin depuis la session
            $nom = $_SESSION['Nom_medecin'];
            echo "Bonjour Docteur $nom";
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