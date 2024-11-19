<?php
    $title = "Region selection test";
    include "include/header.inc.php";
?>

        <section>
            <h2>Choix la région</h2>
            <form method="GET" action="dpt.php">
                <fieldset>
                    <legend> formulaire simple</legend>
                    <label for="regionCodeField">Saisissez un code de région</label>
                    <input type="text" name="regionCode" id="regionCodeField" size="10" />
                    <input type="submit" value="Go!"/>
                </fieldset>
            </form>
        </section>

<?php require_once "include/footer.inc.php"; ?>