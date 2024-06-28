<?php
    $host = 'localhost:3306';
    $username = 'root';
    $password = "Jdaniel2002";
    $database = "masterproject5";

    $connection = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if ($connection == false) {
        die('Error in connection' . mysqli_connect_error()); // echo plusieurs fois et ferme le programme
    }

    session_start(); // connexion avec toutes les pages pour passer variable

    $_SESSION['id_Patient'] = null;

    if (isset($_SESSION['Nom_medecin'])) {
        $nom = $_SESSION['Nom_medecin'];
        $message = "Nice to see you, Docteur $nom";
        echo $message;
    } else {
        echo "ID de médecin non défini";
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Patient Information Form</title>
    <link rel="stylesheet" href="../styles.css">

    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclure jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        /* Styles CSS optionnels pour le calendrier */
        .ui-datepicker {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="#" class="logo">Med&Phar</a>
        <div class="navbar-right">
            <a href="../Login/login_final.html">Log out</a>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <h1>About Med&Phar</h1>
            <p><strong>Who?</strong></p>
            <p>Med&Phar is a dedicated team working towards enhancing the daily tasks of physicians, providing crucial support during consultations.</p>
            
            <p><strong>When?</strong></p>
            <p>Med&Phar operates every day, providing continuous support for medical professionals.</p>

            <p><strong>How?</strong></p>
            <p>By utilizing logistic and graphical data analysis, Med&Phar offers support through a web platform accessible to all specialties, employing technologies like SQL, Python, HTML, and PHP.</p>

            <p><strong>What?</strong></p>
            <p>Med&Phar provides daily support for doctors with:</p>
            <ul>
                <li>Patient Form</li>
                <li>Logistic and graphical data analysis</li>
                <li>Medication information</li>
                <li>Personalized advice and follow-up</li>
                <li>Risk analysis associated with prescriptions</li>
                <li>Assistance in prescription and patient management</li>
                <li>Unique analysis for diagnostics</li>
            </ul>

            <p><strong>Why?</strong></p>
            <p>The goal of Med&Phar is to improve the efficiency of medication prescriptions and to provide unique diagnostic analysis, supporting doctors in their daily practice.</p>
            <br>
        </div>
        <div class="main-content">
            <div class="content-section">
                <h1 id="message"></h1>
                <section class="appointments">
                    <h1>Your appointments</h1>
                    <!-- Tableau centré des rendez-vous -->
                    <div class="center-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Hour</th>
                                    <th>Patient</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11 juillet 2024</td>
                                    <td>10:00</td>
                                    <td>Alice Dubois</td>
                                </tr>
                                <tr>
                                    <td>12 juillet 2024</td>
                                    <td>11:00</td>
                                    <td>Luc Robert</td>
                                </tr>
                                <tr>
                                    <td>15 juillet 2024</td>
                                    <td>09:00</td>
                                    <td>Sophie Garcia </td>
                                <!-- Ajoutez plus de rendez-vous au besoin -->
                            </tbody>
                        </table>
                    </div>


                <div class="buttons">
                    <button onclick="location.href='../Consult/consult.php'">New consultation</button>
                </div>

            </section>
            <h2>Medical News</h2>
            <section class="carousel">
                <div class="slick-carousel">
                    <div class="slide-item">
                        <div class="slide-content">
                            <img src="image1.png" alt="Image 1">
                            <div class="text">
                                <h3>Arrhythmia: a new consensus to support continued activity in athletes</h3>
                                <p>According to a new consensus from the Heart Rhythm Society, physicians should rely on shared clinical decision-making to help manage the return to play of athletes with arrhythmia.</p>
                                <a href="https://www.univadis.fr/viewarticle/s/arythmie-nouveau-consensus-accompagner-poursuite-2024a1000btw?_gl=1*12fyt5o*_up*MQ..*_ga*OTM1ODI5MzQ4LjE3MTk0OTk2OTA.*_ga_BR3MV9G8Q9*MTcxOTQ5OTY4OS4xLjAuMTcxOTQ5OTY4OS4wLjAuMA..">view article</a>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item">
                        <div class="slide-content">
                            <img src="image2.png" alt="Image 2">
                            <div class="text">
                                <h3>Hepatotoxicity of drugs: eleven molecules deemed benign requalified as risky by a new study</h3>
                                <p>The classification of hepatotoxic drugs based on the number of incidents is not representative of the real risk. A new study re-evaluates 194 drugs according to the size of the population using them. Among them, 17 molecules are highly toxic, and the risk of statins is lower.</p>
                                <a href="https://www.lequotidiendumedecin.fr/actu-medicale/hepatotoxicite-des-medicaments-onze-molecules-jugees-benignes-requalifiees-risque-par-une-nouvelle">view article</a>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item">
                        <div class="slide-content">
                            <img src="image3.png" alt="Image 3">
                            <div class="text">
                                <h3>Europe warns of risk of second cancer after CAR-T cell therapy in onco-hematology</h3>
                                <p>Following in the footsteps of the FDA, the European Medicines Agency (EMA) is examining the risk of second cancers following CAR-T cell therapy. </p>
                                <a href="https://www.lequotidiendumedecin.fr/actu-medicale/leurope-alerte-son-tour-sur-le-risque-de-second-cancer-apres-une-therapie-par-car-t-cells-en-onco">view article</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.slick-carousel').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1200, // Temps d'affichage de chaque diapositive en millisecondes
                dots: false,
                arrows: false,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear'
            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Inclure jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Inclure Slick Carousel -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
</body>
</html>




    