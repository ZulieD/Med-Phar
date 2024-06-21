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

    if (isset($_POST['submit'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $date_naissance = $_POST['date_naissance'];
        $uname = $_POST['uname'];
        $password = $_POST['password'];
        
        // Prepare SQL statement with placeholders to prevent SQL injection
        $sql = "SELECT * FROM Medecin WHERE prenom = ? AND nom = ? AND date_naissance = ? AND adresse_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fname, $lname, $date_naissance, $uname);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // If exactly one row matches, update the password
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $sql_update = "UPDATE Medecin SET mot_de_passe = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $password, $id);
            $stmt_update->execute();
            echo 'Your new password is saved. You can go back to the login page.';
            header("Location: ../Login/login_final.html");
        } else {
            echo "We don't have an account registered under this information.";
        }
    }
    