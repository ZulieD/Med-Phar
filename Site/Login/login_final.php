<?php
    $host='localhost:3306';
    $username='root';
    $password="Jdaniel2002";
    $database="masterproject5";
    
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

    $sql = "SELECT * FROM Medecin WHERE adresse_email = '$uname' AND mot_de_passe = '$password'";
    $result = $conn->query($sql) or die($conn->error);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["id_Medecin"] = $row['id'];
        $_SESSION['Nom_medecin'] = $row['nom'];
        header("Location: ../Accueil/homepage.html");
    } else {
        echo 'Invalid username or password, please try again';
    }
}

if (isset($_POST['password_change'])) {
    header("Location: ../Login/password.html");
}

function generateUniqueId($conn) {
    $i=1;
    do {
        // Générer un ID unique
        $uniqueId = $i;
        
        // Préparer une requête pour vérifier si cet ID est déjà utilisé
        $sql="SELECT COUNT(*) as count FROM Medecin WHERE id= $uniqueId";
        $result = $conn->query($sql);
        // Si l'ID n'est pas utilisé, quitter la boucle
        $i++;
        $row = $result->fetch_assoc();
        $count = $row['count'];
    } while ($count > 0);
    
    return $uniqueId;
}
function generateUniqueId2($conn) {
    $i=1;
    do {
        // Générer un ID unique
        $uniqueId = $i;
        
        // Préparer une requête pour vérifier si cet ID est déjà utilisé
        $sql="SELECT COUNT(*) as count FROM adresse_cabinet WHERE id= $uniqueId";
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
    $date_naissance=$_POST['date_naissance'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $speciality = $_POST['speciality'];
    $number=$_POST['numbrt'];
    $street=$_POST['street'];
    $city=$_POST['city'];
    $state=$_POST['state'];
    $country=$_POST['country'];

    // Verif adresse : 

    $sql="SELECT * from adresse_cabinet where numero = ? and rue = ? and ville = ? and Departement = ? and Pays = ?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("issss", $number, $street , $city, $state, $country);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $id_adresse=generateUniqueId2($conn);
        $sql = "INSERT into adresse_cabinet (id, numero, rue, ville, Departement, Pays) values (?,?,?,?,?,?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("iissss", $id_adresse, $number, $street , $city, $state, $country);
        $stmt->execute();
    }else {
        $row = $result->fetch_assoc();
        $id_adresse=$row['id'];
    }

    // Verif medecin 

    $sql = "SELECT * FROM Medecin WHERE adresse_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $newid=generateUniqueId($conn);
        $sql = "INSERT INTO Medecin (id, prenom, nom, date_naissance, adresse_email, mot_de_passe, specialite, id_adresse) VALUES (?, ?, ?, ?, ?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssi", $newid, $fname, $lname, $date_naissance, $email, $password, $speciality,$id_adresse);
        $stmt->execute();
        echo 'Registration successful';
        $stmt->close();
        $conn->close();
        $_SESSION["id_Medecin"] = $newid;
        $_SESSION['Nom_medecin'] = $lname;
        header("Location: ../Accueil/homepage.html");
    } else {
        echo 'Email already registered';
        $stmt->close();
        $conn->close();
    }

    
}

?>




