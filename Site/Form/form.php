<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "masterproject_database";
    $port = 3307;

    // Créer une connexion
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
    session_start();

$sql = "SELECT * FROM patient";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $contraception = $_POST['contraception'];
    $poids = $_POST['poids'];
    $taille = $_POST['taille'];
    $allergie = $_POST['allergie'];
    $activite_metier = $_POST['activite_metier'];
    $risque_metier = $_POST['risque_metier'];
    $activite_quotidienne = $_POST['activite_quotidienne'];
    $qualite_alimentation = $_POST['qualite_alimentation'];

    // Préparer la requête SQL
    $sql = "INSERT INTO patient (prenom, nom, date_naissance, sexe, contraception, poids, taille, allergie, activite_metier, risque_metier, activite_quotidienne, qualite_alimentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("sssssidiiiii", $prenom, $nom, $date_naissance, $sexe, $contraception, $poids, $taille, $allergie, $activite_metier, $risque_metier, $activite_quotidienne, $qualite_alimentation);

    if ($stmt->execute()) {
        echo "Les données du patient ont été enregistrées avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement des données du patient : " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
