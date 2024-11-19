<?php
$title = "Statistiques";
include "include/header.inc.php";
count_detailed_hits("stats");
?>

<section>
    <h2>Statistiques générales</h2>
    <div class="center">
        Nombre de hits : <?php echo count_hits() ?>
    </div>
    <div class="center">
        <?php 
        echo "<img src=\"stats/option_chart.php\" alt=\"test\"/>\n";
        echo "<img src=\"stats/hits_chart.php\" alt=\"test\"/>\n";
        echo "<img src=\"stats/region_chart.php\" alt=\"test\"/>\n";
        ?>
    </div>
</section>

<?php require_once "include/footer.inc.php"; ?>