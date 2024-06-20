<?php
    $host="localhost";
    $username="root";
    $password="";
    $database="masterproject_database";
    $port = 3307;
    
    // Créer une connection
    $conn = new mysqli($host, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
    session_start(); // Démarrer la session

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Med&Phar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <a href="#" class="logo">Med&Phar</a>
        <div class="navbar-right">
            <a href="form.html">Formulaire Patient</a>
            <a href="login.html">Se connecter</a>
            <a href="about_us.html">About Us</a>
            <a href="contact_us.html">Contact Us</a>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <h2>About Us</h2>
            <p>Med&Phar is dedicated to providing the best healthcare solutions...</p>
        </div>
        <div class="main-content">
            <div class="content-section">
                <h1>Accueil Med&Phar</h1>

                <section class="appointments">
                    <h2>Vos Rendez-vous</h2>
                    <ul>
                        <?php
                        $sql = "SELECT c.id, c.date, c.time, p.prenom AS patient_prenom, p.nom AS patient_nom, m.prenom AS medecin_prenom, m.nom AS medecin_nom, mal.nom AS maladie_nom
                                FROM Consultation c
                                JOIN Patient p ON c.id_patient = p.id_patient
                                JOIN Medecin m ON c.id_medecin = m.id
                                JOIN Maladie mal ON c.id_maladie = mal.id
                                ORDER BY c.date, c.time";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<li>" . date('d M Y', strtotime($row['date'])) . " - " . $row['time'] . " - Patient: " . $row['patient_prenom'] . " " . $row['patient_nom'] . " - Médecin: " . $row['medecin_prenom'] . " " . $row['medecin_nom'] . " - Maladie: " . $row['maladie_nom'] . "</li>";
                            }
                        } else {
                            echo "<li>No appointments found.</li>";
                        }

                        $conn->close();
                        ?>
                    </ul>
                </section>

                <section class="news">
                    <h2>Nouvelles Médicales</h2>
                    <article>
                        <h3>Découverte d'un nouveau traitement pour le diabète</h3>
                        <p>Une récente étude montre des résultats prometteurs...</p>
                    </article>
                    <article>
                        <h3>Les dernières avancées en cardiologie</h3>
                        <p>Les cardiologues ont développé une nouvelle technique...</p>
                    </article>
                </section>

                <div class="buttons">
                    <button onclick="location.href='form.html'">Nouvelle Consultation</button>
                    <button onclick="location.href='analyse.html'">Analyse</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
