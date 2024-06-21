<?php
    $host = 'localhost:3306';
    $username = 'root';
    $password = "Jdaniel2002";
    $database = "masterproject5";

    $connection = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if ($connection == false) {
        die('Error in connection' . mysqli_connect_error()); // echo plusieurs fois et ferme le programme
    }

    session_start(); // connexion avec toutes les pages pour passer variable

    $_SESSION['id_Patient'] = null;

    if (isset($_SESSION['Nom_medecin'])) {
        $nom = $_SESSION['Nom_medecin'];
        $message = "Nice to see you, Docteur $nom";
        echo $message;
    } else {
        echo "ID de médecin non défini";
    }

?>



    