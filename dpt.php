<?php
    $title = "Région";
    include "include/header.inc.php";
    count_detailed_hits("dpt");
?>
        <section>
            <h2><?php echo getRegionName() ?></h2>
            <p>Sélectionnez un département dans la liste ou en cliquant sur la carte de votre région.</p>
            <?php
                echo "<div class=\"center\">";
                    displayDptForm();
                echo "</div>";
                displayDptMap();
            ?>
        </section>

<?php require_once "include/footer.inc.php"; ?>