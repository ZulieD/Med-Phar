<?php
    $host="localhost";
    $username="root";
    $password="";//"T7sq6b982002";
    $database="masterproject_database";
    $port = 3307;
    
    // Créer une connection
    $conn = new mysqli($host, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
    session_start(); // Démarrer la session


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

    $sql = "SELECT * FROM Medecin WHERE nom = ? AND mot_de_passe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $uname, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION["Name"] = $uname;
       // header("Location: Acceuil.php");
       echo 'succeed';
    } else {
        echo 'Invalid username or password, please try again';
    }
    $stmt->close();
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
        $sql = "INSERT INTO Medecin (prenom, nom, adresse_email, mot_de_passe, specialite) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fname, $lname, $email, $password, $speciality);
        $stmt->execute();
        echo 'Registration successful';
        $stmt->close();
$conn->close();
    } else {
        echo 'Email already registered';
        $stmt->close();
$conn->close();
    }

    
}

?>




