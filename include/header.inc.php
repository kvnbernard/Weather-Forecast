<?php
    include "include/functions.inc.php";
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <title><?php echo $title ?></title>
        <meta name="author" content="Adel Abbas, Kevin Bernard" />
        <meta name="date" content=<?php echo "\"" . date('D, d M Y') . "\"" ?> />
        <meta name="keywords" content="Weather forecast, Region, City, CY, <?php echo $title ?>" />
        <meta name="description" content="" />
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./standard.css" />
    </head>

    <body>
        <header>
           <div class="container">
                <img class="maximized" src="./images/clouds.jpg" alt="Clouds"/>
                <div class="centered">
                    <h1>Météo</h1>
                </div>
            </div>
            <nav>
                <ul id="nav">
                    <li><a href="./index.php">Accueil</a></li>
                    <?php displayButton();?>
                    <li><a href="./stats.php">Statistiques</a></li>
                </ul>
            </nav>
        </header>
        <?php count_hits() ?>