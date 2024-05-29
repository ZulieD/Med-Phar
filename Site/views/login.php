<?php
    $host='localhost';
    $username='root';
    $password="T7sq6b982002";
    $database="medical_database";
    
    // Créer une connection
    $connection= new mysqli_connect($host,$username,$password,$database);
    // Check connection
    if ($connection==false)
    {
        die('Error in connection' .mysqli_connect_error()); // echo plusieur fois et ferme le programme
    }
    session_start(); // connection avec toutes les pages pour passer variable
?>

/*
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title> Login </title> <!--Nom de la page -->

	<link rel= "stylesheet" href="styles/style_login.css">
</head>
<body>
    <header>
        <div class="Menu">
            <img class="logo" src="images/logo.png" alt="bug">
            <ul class="menu">
                <li class="menuli"><a href="Acceuil.php">Home</a></li>
                <li class="menuli"><a href="Categories.php">Categories</a></li>
                <li class="menuli"></li><a href="cart.php">Cart</a></li>
                <li class="menuli"></li><a href="faq.php">FAQ</a></li>
                <li class="menuli"></li><a href="login.php">Login</a></li>
                </ul>
        </div>

    </header>
    
    <div class="singin">
       <h1>
           Sing In 
       </h1>
       <form action="#" method="post">
            Username
            <input type="text" id="form1" placeholder="Enter Username" name="uname" required>

            Password
            <input name="password" type="password" id="form1" placeholder="Enter Password" required>

            <button type="submit" name="singin">Sing In</button>
        </form>
        <?php 
            if (isset($_POST['singin']))
            {
                $uname=$_POST['uname'];
                $password=$_POST['password']; 
                $sql ="SELECT firstname, lastname FROM Users WHERE nom_utilisateur='$uname' AND passwords='$password'";
                $result = $connection -> query($sql);
                $row =mysqli_fetch_assoc($result);
                $count=mysqli_num_rows($result);
                if ($count ==1){

                    $_SESSION["Name"] = "$uname";
                    header("Location:Acceuil.php");
                }
                else {
                    echo 'Invalid username or password, please try again';
                }
            }
        ?>

    </div>
    <div class="singup">
        <h1>
           Sing Up 
       </h1>
       <form action="#" method="post">
            Fistname
            <input type="text" id="form1" placeholder="Enter your Firstname" name="fname" required>

            Lastname
            <input type="text" id="form1" placeholder="Enter your Lastname" name="lname" required>
            
            Your adresse <br>
            <input type="int" id="form2" placeholder="Number" name="number" >
            <input type="text" id="form2" placeholder="Street" name="street" required>
            <input type="text" id="form2" placeholder="City" name="city" required>
            <input type="text" id="form2" placeholder="Country" name="country" required>

            <br>
            Username
            <input type="text" id="form1" placeholder="Choose an Username" name="user_name" required>

            Password
            <input type="password"  id="form1"placeholder="Enter your Password" name="pass" required>

            <button type="submit" name="singup">Sing Up
            </button>
        </form>
        <?php 
            if (isset($_POST['singup']))
            {
                $uname=$_POST['user_name'];
                $password=$_POST['pass']; 
                $sql ="SELECT firstname, lastname FROM Users WHERE nom_utilisateur='$uname'";
                $result = $connection -> query($sql);
                
                $row =mysqli_fetch_assoc($result);
                $count=mysqli_num_rows($result);
                
                if ($count == 0){
                    echo 'ok';
                    $fname=$_POST['fname'];
                    $lname=$_POST['lname'];
                    $sql="INSERT INTO Users(firstname, lastname, nom_utilisateur, passwords) VALUES ('$fname','$lname','$uname','$password');";
                    $result = $connection->query($sql) or die($connection->error);
                    
                    $num=$_POST['number'];
                    $str=$_POST['street'];
                    $ci=$_POST['city'];
                    $co=$_POST['country'];
                    $sql="INSERT INTO  Adresse(numbers, street, city, country) VALUES ('$num','$str','$ci','$co');";
                    $result = $connection->query($sql) or die($connection->error);
                    
                    $sql ="SELECT id_adresse FROM Adresse WHERE numbers='$num' and street='$str'and  city='$ci' and country='$co'";
                    $result = $connection->query($sql) or die($connection->error);
                    $row=mysqli_fetch_assoc($result);
                    $count=mysqli_num_rows($result);
                    
                    if ($count != 0)
                        {
                            $id_adresse=$row['id_adresse'];
                            $sql="UPDATE Users set id_adresse='$id_adresse' where nom_utilisateur='$uname'";
                            $result = $connection->query($sql) or die($connection->error);
                        }
                    
                    
                    $_SESSION["Name"] = "$uname";
                    header("Location:Acceuil.php");
                }
                else {
                    echo 'User name already use';
                }
            }
        ?>

            
    </div>
    
    
    <footer>

    </footer>
</body>
<?php

$connection -> close();
?>*/