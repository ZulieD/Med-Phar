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

    if (isset($_POST['consult'])) {
        $id_unique = generateUniqueId($connection);
        $id_medicament = $_SESSION['id_medicament'];
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

