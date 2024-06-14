<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="mastproject3";

    $conn=mysqli_connect($host,$username,$password,$database);
    // Check connection
    if ($conn==false)
    {
        die('Error in connection' .mysqli_connect_error()); // echo plusieur fois et ferme le programme
    }
    session_start(); // connection avec tous les pages pour passer variable

$sql = "SELECT * FROM patient";
$result = $conn->query($sql);

function generateUniqueId($conn) {
    $i=1;
    do {
        // Générer un ID unique
        $uniqueId = $i;
        
        // Préparer une requête pour vérifier si cet ID est déjà utilisé
        $sql="SELECT COUNT(*) as count FROM patient WHERE id_patient= $uniqueId";
        $result = $conn->query($sql);
        // Si l'ID n'est pas utilisé, quitter la boucle
        $i++;
        $row = $result->fetch_assoc();
        $count = $row['count'];
    } while ($count > 0);
    
    return $uniqueId;
}

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
    $newId = generateUniqueId($conn);

    if ($newId !== null) {
        // Préparer la requête d'insertion avec des placeholders sécurisés
        $sql = "INSERT INTO patient (id_patient, prenom, nom, date_naissance, sexe, contraception, poids, taille, allergie, activite_metier, risque_metier, activite_quotidienne, qualite_alimentation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Préparer et exécuter la requête préparée
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssddssiss", $newId, $prenom, $nom, $date_naissance, $sexe, $contraception, $poids, $taille, $allergie, $activite_metier, $risque_metier, $activite_quotidienne, $qualite_alimentation);
        
        if ($stmt->execute()) {
            echo "Nouveau patient ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du patient : " . $stmt->error;
        }
        
        // Fermer le statement
        $stmt->close();
    } else {
        echo "Erreur lors de la génération de l'ID unique.";
    }

    $_SESSION["id_Patient"] = "$newId";
    header("Location:../Consult/consult.php");
}

$conn->close();
?>
