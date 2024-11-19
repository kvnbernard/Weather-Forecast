<?php
$title = "Météo";
include "include/header.inc.php";
count_detailed_hits("index");
?>

        <aside>
            <h3> Les nouveautés </h3>
            <p> Changement de la carte de la page principale </p>
            <p> Ajout de cartes pour choisir le département </p>
            <p> Ajout d'un aperçu de la région sur la page du choix du département </p>
            <p> Avancement de la page de statistiques </p>
            <p>Suppression des doublons dans la liste déroulante des villes (filtre php, donc sans modifier le csv)</p>
            <p>Ajout des cookies pour la dernière ville consultée</p>
            <p>Ajout des cookies de préférence pour l'affichage des résultats</p>
            <p>Ajout de l'affichage de la population de la ville consultée</p>
        </aside>
        <section>
            <h2>Bienvenue!</h2>
            <h3>Sélectionnez votre région!</h3>
            <?php
                require_once "./maps/france.map";
            ?>
        </section>

<?php require_once "include/footer.inc.php"; ?>