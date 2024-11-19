<?php

/**
 * Set to true/false to see/hide debug elements.
 */
define("DEBUG", false);

/**
 * The api key provided by openweathermap.org
 */
define("API_KEY", "1f3d52717caedbf49c7f39dc59562336");

/**
 * The language code we using when calling the openweathermap api.
 */
define("LANG", "fr");
define("UNITS", "metric");

define("SEPARATOR", "&amp;");

define("HOURLY", "hourly");
define("DAILY", "daily");

date_default_timezone_set('Europe/Paris');

define("HITS_FILE", "./stats/hits.txt");
define("DPT_STATS_FILE", "./stats/dpt_stats.csv");
define("OPTION_STATS_FILE", "./stats/options.csv");
define("DETAILED_STATS_FILE", "./stats/detailed_hits.csv");
define("STATS_PATH", "./stats/");
define("GEOAPI_URL", "https://geo.api.gouv.fr/communes/");

/***********CSV QUERIES******/

/**
 * A simple utility method which uses a csv file to return the appropriate informations.
 * The csv file is sorted by regionCode.
 * @author Adel
 * @param string $regionCode
 * @return array $departments
 */
function getDepartments(string $regionCode = "11"): array
{
    $dptData = "./resources/departments.csv";
    $handle = fopen($dptData, "r");

    //Change the following variables if changes are made in the csv file.
    $REGION_CODE = 0;
    $DPT_CODE = 1;
    $DPT_NAME = 2;

    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($handle);
    $departments = array();
    $stop = false;
    $state = 0;
    while ((($data = fgetcsv($handle, 1000, ",")) !== FALSE) && !$stop) {
        if (($state == 0) && ($data[$REGION_CODE] == $regionCode)) {
            $state = 1;
        }
        if ($state == 1) {
            //We stop once we found all the information about a given dpt.
            if ($data[$REGION_CODE] != $regionCode) {
                $stop = true;
            } else {
                $dpt["code"] = $data[$DPT_CODE];
                $dpt["name"] = $data[$DPT_NAME];
                $departments[] = $dpt;
            }
        }
    }
    fclose($handle);
    return $departments;
}

/**
 * A simple utility method which uses a csv file to return the appropriate informations.
 * The csv file is sorted by regionCode.
 * @author Adel
 * @param string $regionCode
 * @return array $departments
 */
function getDepartmentsMap($regionCode = "11")
{
    $dptsMapsData = "./resources/departmentsMaps.csv";
    $handle = fopen($dptsMapsData, "r");

    //Change the following variables if changes are made in the csv file.
    $REGION_CODE = 0;
    $DPT_MAP_PATHNAME = 1;
    $REGION_OVERVIEW_PATHNAME = 2;

    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($handle);
    $departmentsMap = array();
    $stop = false;
    $state = 0;
    while ((($data = fgetcsv($handle, 1000, ",")) !== FALSE) && !$stop) {
        if (($state == 0) && ($data[$REGION_CODE] == $regionCode)) {
            $state = 1;
        }
        if ($state == 1) {
            //We stop once we found all the information about a given dpt.
            if ($data[$REGION_CODE] != $regionCode) {
                $stop = true;
            }
            if ($data[$REGION_CODE] == $regionCode) {

                $departmentsMap["dptMapPathname"] = $data[$DPT_MAP_PATHNAME];
                $departmentsMap["regionOverviewPathname"] = $data[$REGION_OVERVIEW_PATHNAME];
            }
        }
    }
    fclose($handle);
    return $departmentsMap;
}
/**
 * A simple utility method which uses a csv file to return the appropriate informations.
 * @author Adel
 * @param string $dptCode
 * @return array $cities
 */
function getCities(string $dptCode): array
{
    $citiesData = "./resources/cities.csv";
    $handle = fopen($citiesData, "r");

    //Change the following variables if changes are made in the csv file.
    $DPT_CODE = 0;
    $INSEE_CODE = 1;
    $ZIP_CODE = 2;
    $CITY_NAME = 3;
    $GPS_LAT = 4;
    $GPS_LNG = 5;

    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($handle);
    $cities = array();
    $stop = false;
    $state = 0;
    $tmp = "";
    while ((($data = fgetcsv($handle, ",")) !== FALSE) && !$stop) {
        if (($state == 0) && ($data[$DPT_CODE] == $dptCode)) {
            $state = 1;
        }
        if ($state == 1) {
            if ($data[$DPT_CODE] != $dptCode) {
                $stop = true;
            } else {
                $city["code"] = $data[$INSEE_CODE];
                $city["name"] = $data[$CITY_NAME];
                $city["lat"] = $data[$GPS_LAT];
                $city["long"] = $data[$GPS_LNG];
                //Add the city element to the $cities array and avoid duplicate names. 
                if ($city["name"] != $tmp) {
                    $tmp = $city["name"];
                    $cities[] = $city;
                }
            }
        }
    }
    fclose($handle);
    return $cities;
}

/**
 * This utility function uses a sequential search to find the name assiociated with a regionCode in a csv file.
 * @author Adel
 * @return string the region name
 */
function getRegionName(): string
{
    if (isset($_GET["region"])) {
        $regionData = "./resources/regions.csv";
        $regionCode = $_GET["region"];
        $handle = fopen($regionData, "r");

        //Change the following variables if changes are made in the csv file.
        $REGION_CODE = 0;
        $REGION_NAME = 1;

        //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
        fgets($handle);
        $stop = false;
        $name = null;
        while ((($data = fgetcsv($handle, ",")) !== FALSE) && !$stop) {
            if ($data[$REGION_CODE] == $regionCode) {
                $name = $data[$REGION_NAME];
                $stop = true;
            }
        }
        fclose($handle);
        return $name;
    }
}

function getRegionCode($dptCode)
{
    $dptData = "./resources/departments.csv";
    $handle = fopen($dptData, "r");

    //Change the following variables if changes are made in the csv file.
    $REGION_CODE = 0;
    $DPT_CODE = 1;

    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($handle);
    $stop = false;
    $state = 0;
    while ((($data = fgetcsv($handle, 1000, ",")) !== FALSE) && !$stop) {
        if (($state == 0) && ($data[$DPT_CODE] == $dptCode)) {
            $state = 1;
        }
        if ($state == 1) {
            //We stop once we found all the information about a given dpt.
            if ($data[$DPT_CODE] != $dptCode) {
                $stop = true;
            } else {
                $regionCode = $data[$REGION_CODE];
            }
        }
    }
    fclose($handle);
    return $regionCode;
}
/*******NAV********/

function displayButton(): void
{
    if (isset($_SERVER['PHP_SELF'])) {
        $currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);
        if ($currentPage == "weather.php") {
            $proceed = false;
            if (isset($_GET["dpt"])) {
                $dptCode = $_GET["dpt"];
                $proceed = true;
            }
            if ($proceed || isset($_SESSION["dpt"])) {
                if (empty($dptCode)) {
                    $dptCode = $_SESSION["dpt"];
                }
                $regionCode = getRegionCode($dptCode);
                echo "<li><a href=\"./dpt.php?region=$regionCode\">Départements</a></li>\n";
            }
        }
    }
}

/*******DROPDOWN FORMS********/
/**
 * This function displays a dropdown form of the departments in a region using the regionCode in the $_GET superglobal array.
 * @author Adel
 * @return void
 */
function displayDptForm(): void
{
    if (isset($_GET["region"])) {
        $regionCode = $_GET["region"];
        if (DEBUG) {
            echo "<p>regionCode: " . $regionCode . "</p>\n";
        }
        $departments = getDepartments($regionCode);


        echo "<form method=\"GET\" action=\"weather.php\">\n";
        echo "\t<fieldset>\n";
        echo "\t\t<legend>Sélection</legend>\n";
        echo "\t\t<select name=\"dpt\" id=\"dpt\" onChange=\"this.form.submit();\">\n";
        echo "\t\t\t<option value=\"none\" selected=\"selected\" disabled=\"disabled\" hidden=\"hidden\">Sélectionner un département</option>\n";

        for ($i = 0; $i < count($departments); $i++) {
            displayOption($departments[$i]);
        }

        echo "\t\t</select>\n";
        // echo "\t\t\t<input type=\"submit\" value=\"Go!\"/>\n";
        echo "\t</fieldset>\n";
        echo "</form>\n";
    }
}

/**
 *We're going to display options for the dpt and the cities forms, so might as well make it a function ;)
 * @author Adel
 * @param array $arr
 * @return void
 */
function displayOption(array $arr): void
{
    echo "\t\t\t<option value=\"" . $arr["code"] . "\">" . $arr["name"] . "</option>\n";
}

/**
 * This function displays a dropdown form of the cities in a given department using either the result of the dpt form or the $_SESSION
 * @author Adel 
 * @return void
 */
function displayCityForm(): void
{
    $proceed = false;
    if (isset($_GET["dpt"])) {
        $dptCode = $_GET["dpt"];
        count_dpt($dptCode);
        register_dpt($dptCode);
        $proceed = true;
    }
    //Try to display the dropdown form everytime
    if ($proceed || isset($_SESSION["dpt"])) {
        if (DEBUG) {
            echo "<p>dptCode: " . $dptCode . "</p>\n";
        }
        if (empty($dptCode)) {
            $dptCode = $_SESSION["dpt"];
        }
        $cities = getCities($dptCode);


        /* echo "<form method=\"GET\" action=\"weather.php\">\n";
        echo "\t<fieldset>\n";
        echo "\t\t<legend>Options</legend>\n";*/
        echo "<select name=\"city\" id=\"city\" onChange=\"this.form.submit();\">\n";
        echo "\t\t\t\t\t<option value=\"none\" selected=\"selected\" disabled=\"disabled\" hidden=\"hidden\">Sélectionnez une ville</option>\n";

        for ($i = 0; $i < count($cities); $i++) {
            $city = $cities[$i];
            $encodedValue  = http_build_query($city, "", SEPARATOR);
            echo "\t\t\t\t\t<option value=\"" . $encodedValue . "\">" . $city["name"] . "</option>\n";
        }

        echo "\t\t\t\t\t</select>\n";
        //echo "\t\t\t<input type=\"submit\" value=\"Go!\"/>\n";
        /* echo "\t</fieldset>\n";
        echo "</form>\n";*/
    }
}

function displayDptMap(): void
{
    if (isset($_GET["region"])) {
        $regionCode = $_GET["region"];

        $departmentsMap = getDepartmentsMap($regionCode);

        $regionOverviewPathname = $departmentsMap["regionOverviewPathname"];
        echo "<aside>";
        echo "<img class=\"leftOverview\" src=\"$regionOverviewPathname\" alt=\"Region Overview\"/>";
        echo "</aside>";

        require $departmentsMap["dptMapPathname"];
    }
}

/*********API QUERY******/
/**
 * This function does all the preliminary work to display the weather forecast for a given city.
 * @author Adel
 * @return boolean returns false if the weather shouldn't be displayed.
 */
function processCity(): bool
{
    $res = processCityForm();
    if (!$res) {
        $res = processCityCookie();
    }
    return $res;
}
/**
 * This function processes the city form.
 * @author Adel
 * @return bool
 */
function processCityForm(): bool
{
    $result = false;
    if (isset($_GET["city"])) {
        $city = decode_city($_GET["city"]);
        register_city($city);
        processWeather($city);
        $result = true;
    }
    return $result;
}
/**
 * This function processes the city cookie to take the appropriate action:
 * Only display weather information about the city if it's in the current department.
 * @author Adel
 * @return bool
 */
function processCityCookie(): bool
{
    $result = false;
    if (isset($_COOKIE["city"])) {
        $city = decode_city($_COOKIE["city"]);
        if (isInDpt($city["code"])) {
            register_city($city);
            processWeather($city);
            $result = true;
        }
    }
    return $result;
}

/**
 * This function gets the weather data and stores it into the $_SESSION array.
 * @author Adel
 * @param array $city
 * @return void
 */
function processWeather(array $city): void
{
    $weatherData = getWeather($city);
    setSessionWeather($weatherData);
}

/**
 * This functions returns weather information as an associative array.
 * @author Adel
 * @param array $city
 * @return array
 */
function getWeather(array $city): array
{
    if (isset($city["lat"], $city["long"])) {
        $weatherData = queryWeatherAPIGPS($city["lat"], $city["long"]);
        return $weatherData;
    }
}

/**
 * This function registers the current weather information in a session.
 * @author Adel
 * @param array $weatherData
 * @return void
 */
function setSessionWeather(array $weatherData): void
{
    unset($_SESSION["weather"]);
    $_SESSION["weather"] = $weatherData;
}

/**
 * This function registers the current city informations in a session.
 * @author Adel
 * @param array $city
 * @return void
 */
function register_city(array $city): void
{
    unset($_SESSION["city"]);
    $_SESSION["city"] = $city;
}
/**
 * This function registers the current department code in a session.
 * @author Adel
 * @param string $dptCode
 * @return void
 */
function register_dpt(string $dptCode): void
{
    unset($_SESSION["dpt"]);
    $_SESSION["dpt"] = $dptCode;
}

/**
 * This function decodes the string provided by the city dropdown form.
 * @author Adel
 * @param string $encodedValue
 * @return array
 */
function decode_city(string $encodedValue): array
{
    parse_str($encodedValue, $city);
    return $city;
}

/**
 * This function checks if a city is in the current department.
 * @author Adel
 * @param string $zip
 * @return boolean
 */
function isInDpt(string $zip): bool
{
    //We suppose it's true first, as when $_GET["dpt"] is empty, the user is only switching options and we don't need to apply our filter.
    $assertion = true;
    if (isset($_GET["dpt"])) {
        $dpt = $_GET["dpt"];
        $tmp = substr($zip, 0, 2);
        $assertion = strcmp($dpt, $tmp) == 0;
    }
    return $assertion;
}

/**
 * This function sends a query to the openweathermap api using the provided zip code to get weather data as a json string.
 * NOTE: Atm, we don't take into consideration the possibility of a query failing. We'll add additionnal logic later.
 * @author Adel
 * @param string $zip A city zip code.
 * @return array $weatherData An associative array with weather data.
 */
function queryWeatherAPI(string $zip): array
{
    $url = "http://api.openweathermap.org/data/2.5/weather?zip=" . $zip . ",FR&appid=" . API_KEY;
    $json = file_get_contents($url);
    if (DEBUG) {
        echo "<p>" . $url . "</p>\n";
        echo "<p>" . $json . "</p>\n";
    }
    $weatherData = json_decode($json, true);
    return $weatherData;
}

/**
 * This function sends a query to the openweathermap one call api using the provided gps information to get weather data as a json string.
 * @author Adel
 * @param string $lat
 * @param string $long
 * @return array $weatherData An associative array with weather data.
 */
function queryWeatherAPIGPS(string $lat, string $long): array
{
    $weatherData = false;
    $url = "https://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" . $long . "&appid=" . API_KEY . "&lang=" . LANG . "&units=" . UNITS;
    $json = file_get_contents($url);
    if (DEBUG) {
        echo "<p>" . $url . "</p>\n";
        echo "<p>" . $json . "</p>\n";
    }
    if ($json != false) {
        $weatherData = json_decode($json, true);
    }
    return $weatherData;
}

/**
 * This function sends a query to the geo.api.gouv.fr API in order to get general informations on a French city given its INSEE code.
 * @author Adel
 * @param string $inseeCode
 * @return mixed $cityData general information about a city or false if the query failed.
 */
function queryGeoAPI(string $inseeCode)
{
    $url = GEOAPI_URL . $inseeCode;
    $json = file_get_contents($url);
    $cityData = json_decode($json, true);
    return $cityData;
}

/*********RESULTS*****/

/**
 * This function displays a simple form with 2 radio buttons.
 * @author Adel
 * @return void
 */
function displayOptions(): void
{
    if (isset($_SESSION["weather"])) {
        //echo "<form method=\"GET\" action=\"weather.php\">\n";
        // echo "\t<fieldset>\n";
        // echo "\t\t<legend>Options</legend>\n";
        echo "\t\t<label for=\"hourly\">Par heure</label>\n";
        echo "\t\t<input type=\"radio\" name=\"option\" value=\"hourly\" id=\"hourly\" size=\"10\" ". isChecked("hourly") ." onChange=\"this.form.submit();\" />\n";
        echo "\t\t<label for=\"daily\">Par jour</label>\n";
        echo "\t\t<input type=\"radio\" name=\"option\" value=\"daily\" id=\"daily\" size=\"10\" ". isChecked("daily") ." onChange=\"this.form.submit();\" />\n";
        //echo "\t\t<input type=\"submit\" value=\"Go!\"/>\n";
        // echo "\t</fieldset>\n";
        //echo "</form>\n";
    }
}

function isChecked($buttonName)
{
    $tmp = "";
    if (isset($_GET["option"])) {
        if (strcmp($_GET["option"], $buttonName) == 0) {
            $tmp = "checked=\"checked\"";
        }
    }
    return $tmp;
}
/**
 * This function is used to call the appropriate display function depending on which option was selected.
 * The hourly forecast is displayed by default.
 * @author Adel
 * @param string $option must be either "hourly" or "daily"
 * @return void
 */
function displayWeather(string $option = HOURLY): void
{
    displayGeneralInfo();
    if (isset($_GET["option"])) {
        $option = $_GET["option"];
    } elseif (isset($_COOKIE["option"])) {
        $option = $_COOKIE["option"];
    }
    if (isset($_SESSION["weather"])) {
        count_option($option);
        switch ($option) {
            case HOURLY:
                displayNewHourlyForecasts();
                break;
            case DAILY:
                displayDailyForecasts();
                break;
            default:
                # nothing happens...
                break;
        }
    }
}
/**
 * This functions returns the name of the city selected by the user (or a default name "Prévisions par ville");
 * @author Adel
 * @return string
 */
function getCityName(): string
{
    $name = null;
    if (isset($_GET["city"])) {
        parse_str($_GET["city"], $city);
        $name = $city["name"];
    } elseif (isset($_COOKIE["city"])) {
        parse_str($_COOKIE["city"], $city);
        if (isInDpt($city["code"])) {
            $name = $city["name"];
        }
    } elseif (isset($_SESSION["city"])) {
        $name = $_SESSION["city"]["name"];
    }
    if ($name == null) {
        $name = "Prévisions par ville";
    }
    return $name;
}
/**
 * This function uses data provided by the geo api and returns the population.
 * @author Adel
 * @param array $cityData the data provided by geo api as an array
 * @return integer $population or false if the api query failed
 */
function getPopulation($inseeCode)
{
    $cityData = queryGeoAPI($inseeCode);
    $population = $cityData["population"];
    return $population;
}
/**
 * This function displays the population of a French city using its insee code in the $_SESSION array.
 * @author Adel
 * @return void
 */
function displayPopulation(): void
{
    if (isset($_SESSION["city"]["code"])) {
        $inseeCode = $_SESSION["city"]["code"];
        $population = getPopulation($inseeCode);
        echo "\t\t\t<p class=\"center\">La population de " . getCityName() . " est de " . $population . " habitants.</p>\n";
    }
}

function displayGeneralInfo(): void
{
    displayPopulation();
    displaySun();
}

/******************FORECAST DISPLAY***************/


function displaySun(): void
{
    if (isset($_SESSION["weather"])) {
        $sunrise = date("H \h i", $_SESSION["weather"]["current"]["sunrise"]);
        $sunset = date("H \h i", $_SESSION["weather"]["current"]["sunset"]);
        echo "\t\t\t<p class=\"center\"><img src=\"http://openweathermap.org/img/wn/01d.png\" alt=\"Lever\"/>: " . $sunrise . ", <img src=\"http://openweathermap.org/img/wn/01n.png\" alt=\"Coucher\"/>: " . $sunset . "</p>\n";
    }
}

/**
 * @author Adel
 * @param string $icon the icon id. A list of the valid ids can be found on https://openweathermap.org/weather-conditions
 * @return string 
 */
function displayWeatherIllustration(string $icon): string
{
    return "<img src=\"http://openweathermap.org/img/wn/" . $icon . ".png \" alt=\"weather illustration\"/>\n";
}

/**
 * This function displays an Hourly forecast for 48 hours
 * @author Adel
 * @return void
 */
function displayHourlyForecasts(): void
{
    $forecasts = $_SESSION["weather"]["hourly"];

    echo "<table>\n";
    echo "\t<thead>\n";
    echo "\t<tr>\n";
    echo "\t\t<th>Heure</th>\n";
    echo "\t\t<th>Description</th>\n";
    echo "\t\t<th>Température</th>\n";
    echo "\t</tr>\n";
    echo "\t</thead>\n";
    echo "\t<tbody>\n";

    foreach ($forecasts as $key => $forecast) {
        displayHourlyForecast($forecast);
    }

    echo "\t</tbody>\n";
    echo "</table>\n";
}
/**
 * This function displays the weather forecast of a single hour.
 * @author Adel
 * @param array $forecast An associative array with the necessary information to display the forecast(dt, temp, weather, description, icon);
 * @return void
 */
function displayHourlyForecast(array $forecast): void
{

    $time = convertTime($forecast["dt"]);
    $temp = $forecast["temp"];
    $weather = $forecast["weather"][0];
    $description = $weather["description"];
    $icon = $weather["icon"];

    echo "\t<tr>\n";
    echo "\t\t<td>" . $time . "</td>\n";
    echo ("\t\t<td>" . displayWeatherIllustration($icon) . " " . $description . "</td>\n");
    echo ("\t\t<td>" . $temp . " °C</td>\n");
    echo "\t</tr>\n";
}


function displayNewHourlyForecasts(): void
{
    $forecasts = $_SESSION["weather"]["hourly"];
    echo "<div class=\"scroll\">\n";
    echo "<table>\n";
    echo "\t<thead>\n";
    echo "\t<tr>\n";

    foreach ($forecasts as $key => $forecast) {
        $time = convertTime($forecast["dt"]);
        echo "\t\t<th>" . $time . "</th>\n";
    }
    echo "\t</tr>\n";
    echo "\t</thead>\n";
    echo "\t<tbody>\n";

    echo "\t<tr>\n";
    foreach ($forecasts as $key => $forecast) {
        displayNewHourlyForecast($forecast);
    }
    echo "\t</tr>\n";
    echo "\t</tbody>\n";
    echo "</table>\n";
    echo "</div>\n";
}

function displayNewHourlyForecast(array $forecast): void
{

    $temp = $forecast["temp"];
    $weather = $forecast["weather"][0];
    $description = $weather["description"];
    $icon = $weather["icon"];

    echo "\t\t<td>" . $temp . " °C " . displayWeatherIllustration($icon) . " " . $description . "</td>\n";
}


/**
 * This function displays the weather forecast of the next 7 days.
 * @author Adel
 * @return void
 */
function displayDailyForecasts(): void
{
    $forecasts = $_SESSION["weather"]["daily"];

    echo "<table>\n";
    echo "\t<thead>\n";
    echo "\t<tr>\n";
    echo "\t\t<th>Jour</th>\n";
    echo "\t\t<th>Description</th>\n";
    echo "\t\t<th>Température</th>\n";
    echo "\t</tr>\n";
    echo "\t</thead>\n";
    echo "\t<tbody>\n";

    foreach ($forecasts as $key => $forecast) {
        displayDailyForecast($forecast);
    }

    echo "\t</tbody>\n";
    echo "</table>\n";
}
/**
 * This function displays a daily forecast for a single day.
 * @author Adel
 * @param array $forecast
 * @return void
 */
function displayDailyForecast(array $forecast): void
{
    $time = getDay($forecast["dt"]);
    $temp = $forecast["temp"]["day"];
    $weather = $forecast["weather"][0];
    $description = $weather["description"];
    $icon = $weather["icon"];

    echo "\t<tr>\n";
    echo "\t\t<td>" . $time . "</td>\n";
    echo ("\t\t<td>" . displayWeatherIllustration($icon) . " " . $description . "</td>\n");
    echo ("\t\t<td>" . $temp . " °C</td>\n");
    echo "\t</tr>\n";
}

/************************TIME*******************/

/**
 * This utility function returns the hour corresponding provided unix time code
 * @author Adel
 * @param string $dt a unix time code
 * @return string the corresponding hour
 */
function convertTime(string $dt): string
{
    return date("d\/ m H \h", $dt);
}

/**
 * This utility function returns the day in French corresponding to the provided unix time code.
 * @author Adel
 * @param string $dt this should be a unix time code (precondition)
 * @return string The corresponding day of the week in French.
 */
function getDay(string $dt): string
{
    $frDays = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
    $date = getdate($dt);
    $wday = $date["wday"];
    $mday = $date["mday"];
    if ($mday == date("j")) {
        $result = "Aujourd'hui";
    } else {
        $result = $frDays[$wday] . " " . $mday;
    }
    return $result;
}

/***************************STATS*****************************/

/**
 *  A simple hit counter using a text file
 * @return integer the new hit count
 */
function count_hits(): int
{
    //We first open the stream for reading and writing with the mode parameter set to "r+".
    $file = fopen(HITS_FILE, "r+");
    //Then we read the file content. In this case we can afford to load the entire file.
    $hits = intval(fread($file, filesize(HITS_FILE)));
    //Update the counter value;
    $hits++;
    //Then we set the file position indicator back to 0, in order to overwrite its content.
    rewind($file);
    fwrite($file, strval($hits));
    fclose($file);
    return $hits;
}

/**
 * This function keeps track of the department queries using a csv file.
 * @author Adel
 * @param string $dptCode the department code
 * @return void
 */
function count_dpt(string $dptCode): void
{
    //The naive approach is to rewrite the entire file
    $input = fopen(DPT_STATS_FILE, "r");
    $output = fopen(STATS_PATH . "tmp.csv", 'w');
    //Change the following variables if changes are made in the csv file.
    $REGION_CODE = 0;
    $DPT_CODE = 1;
    $COUNT = 2;
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        if (($data[$DPT_CODE] == $dptCode)) {
            //convert from string to int first
            $count = intval($data[$COUNT]);
            //increment the counter value and replace the old value
            $data[$COUNT] = ++$count;
        }
        fputcsv($output, $data);
    }
    fclose($input);
    fclose($output);

    //clean up
    unlink(DPT_STATS_FILE); // Delete obsolete CSV
    rename(STATS_PATH . "tmp.csv", DPT_STATS_FILE); //Rename temporary to new
}

/**
 * This function gives the amount of time a request was made for a given department.
 * It uses a simple sequential search algorithm, reading a csv file.
 * @author Adel
 * @deprecated we now use getDptData() in stats.inc.php
 * @param string $dptCode the department code.
 * @return int|false $count The amount of time a request has been made for the dpt or false if no match was found.
 */
function getDptCount(string $dptCode)
{
    $input = fopen(DPT_STATS_FILE, "r");
    $DPT_CODE = 1;
    $COUNT = 2;
    $stop = false;
    $count = false;
    while ((($data = fgetcsv($input, ",")) !== FALSE) && !$stop) {
        if (($data[$DPT_CODE] == $dptCode)) {
            $count = intval($data[$COUNT]);
            //stop once found.
            $stop = true;
        }
    }
    fclose($input);
    return $count;
}
/**
 * This function stores information about which option was used to display the weather forecast in a csv file.
 * @author Adel
 * @param string $option
 * @return void
 */
function count_option(string $option)
{
    //The naive approach is to rewrite the entire file
    $input = fopen(OPTION_STATS_FILE, "r");
    $output = fopen(STATS_PATH . "tmp2.csv", 'w');
    //Change the following variables if changes are made in the csv file.
    $OPTION = 0;
    $COUNT = 1;
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        if (($data[$OPTION] == $option)) {
            //convert from string to int first
            $count = intval($data[$COUNT]);
            //increment the counter value and replace the old value
            $data[$COUNT] = ++$count;
        }
        fputcsv($output, $data);
    }
    fclose($input);
    fclose($output);

    //clean up
    unlink(OPTION_STATS_FILE); // Delete obsolete CSV
    rename(STATS_PATH . "tmp2.csv", OPTION_STATS_FILE); //Rename temporary to new
}

/**
 * This function keeps a detailed track of hits by page and week in a csv file.
 * @author Adel
 * @param string $slug
 * @return void
 */
function count_detailed_hits(string $slug)
{
    //We need an associative array
    $columns = array("year" => "0", "week" => "1", "index" => "2", "dpt" => "3", "weather" => "4", "api" => "5", "stats" => "6");

    //Get the current year and week to know where the counter should be updated.
    $unix = time();
    $date = date("Y,W", $unix);
    $date = explode(",", $date);
    $year = $date[0];
    $week = $date[1];

    //The naive approach is to rewrite the entire file
    $input = fopen(DETAILED_STATS_FILE, "r");
    $output = fopen(STATS_PATH . "tmp3.csv", 'w');
    $found = false;
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        //Step 1: Find the right line
        if (($data[$columns["year"]] == $year) && ($data[$columns["week"]] == $week)) {
            $found = true;
            //Make sure the slug is valid first.
            if (isset($columns[$slug])) {
                //Prevent access to the first two columns.
                if (($columns[$slug]) > 1) {
                    //convert from string to int first
                    $count = intval($data[$columns[$slug]]);
                    //increment the counter value and replace the old value
                    $data[$columns[$slug]] = ++$count;
                }
            }
        }
        fputcsv($output, $data);
    }
    //If not found, append.
    if (!$found) {

        $data = array($year, $week);
        for ($i = 2; $i < 7; $i++) {
            if ($i == $columns[$slug]) {
                $data[$i] = 1;
            } else {
                $data[$i] = 0;
            }
        }
        fputcsv($output, $data);
    }
    fclose($input);
    fclose($output);

    //clean up
    unlink(DETAILED_STATS_FILE); // Delete obsolete CSV
    rename(STATS_PATH . "tmp3.csv", DETAILED_STATS_FILE); //Rename temporary to new
}
