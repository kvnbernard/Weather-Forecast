<?php
include "include/cookies.inc.php";
$title = "Prévisions météo";
session_start();
city_cookie();
option_cookie();
include "include/header.inc.php";
count_detailed_hits("weather");
?>

        <section>
            <h2><?php echo getCityName() ?></h2>
            <form method="GET" action="weather.php">
                <fieldset>
                    <legend>Options</legend>
                    <?php
                    //echo "<div class=\"center\">";
                    displayCityForm();
                    if (processCity()) {
                        displayOptions();
                        echo "\t\t\t\t</fieldset>\n";
                        echo "\t\t\t</form>\n";
                        displayWeather();
                    }else{
                        echo "\t\t\t\t</fieldset>\n";
                        echo "\t\t\t</form>\n";
                    }
                    //echo "</div>\n";
                    ?>
        </section>

<?php require_once "include/footer.inc.php"; ?>