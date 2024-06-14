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
    session_start(); 


$sql = "SELECT * FROM medecin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Affichez les données de chaque ligne
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["nom"]. "<br>";
    }
} else {
    echo "0 results";
}

// Connection login
if (isset($_POST['signin'])) {
    $uname = $_POST['uname'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Medecin WHERE adresse_email = ? AND mot_de_passe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $uname, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION["id_Medecin"] = $row['id'];
       header("Location: ../Accueil/accueil.php");
       echo 'succeed';
    } else {
        echo 'Invalid username or password, please try again';
    }
    $stmt->close();
}
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
// Connection création de compte
if (isset($_POST['signup'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $speciality = $_POST['speciality'];

    $sql = "SELECT * FROM Medecin WHERE adresse_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $newid=generateUniqueId($conn);
        $sql = "INSERT INTO Medecin (id, prenom, nom, adresse_email, mot_de_passe, specialite) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $newid, $fname, $lname, $email, $password, $speciality);
        $stmt->execute();
        echo 'Registration successful';
        $stmt->close();
        $conn->close();
        $_SESSION["id_Medecin"] = $newid;
    } else {
        echo 'Email already registered';
        $stmt->close();
$conn->close();
    }

    
}

?>




