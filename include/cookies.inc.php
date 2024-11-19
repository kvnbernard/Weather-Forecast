<?php
define("MONTH", 60 * 60 * 24 * 30);
define("DOMAIN", "weather/");

/******************************COOKIES***********************************/

/**
 * This function saves the last consulted city on the user's browser via a cookie.
 * @author Adel
 * @return void
 */
function city_cookie(): void
{
    if (isset($_GET["city"])) {
        //Stores the cookie for a month;
        $time = time() + MONTH;
        setcookie("city", $_GET["city"],  $time, "weather/");
    }
}
/**
 * 
 * @author Adel
 * @return void
 */
function option_cookie(): void {
    if (isset($_GET["option"])) {
        //Stores the cookie for a month;
        $time = time() + MONTH;
        setcookie("option", $_GET["option"],  $time, "weather/");
    }
}
?>