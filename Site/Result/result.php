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
	<title> Resultat  </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="style.css">
</head>