<?php
define("DPT_STATS_FILE", "../stats/dpt_stats.csv");
define("OPTION_STATS_FILE", "../stats/options.csv");
define("DETAILED_STATS_FILE", "../stats/detailed_hits.csv");

/**
 * This function reads dpt_stats.csv and returns data about the departments queries as an array
 * @author Adel
 * @return array
 */
function getDptData(): array
{
    $input = fopen(DPT_STATS_FILE, "r");
    $DPT_CODE = 1;
    $COUNT = 2;
    $dptData = array();
    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($input);
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        $count = intval($data[$COUNT]);
        $cell = array();
        $cell["dpt"] = $data[$DPT_CODE];
        $cell["count"] = $count;
        $dptData[] = $cell;
    }
    fclose($input);
    return $dptData;
}
/**
 * This function reads options.csv and returns data about the display options as an array
 * @author Adel
 * @return array
 */
function getOptionData(): array
{
    $input = fopen(OPTION_STATS_FILE, "r");
    $OPTION = 0;
    $COUNT = 1;
    $optionData = array();
    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($input);
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        $count = intval($data[$COUNT]);
        $cell = array();
        $cell["option"] = $data[$OPTION];
        $cell["count"] = $count;
        $optionData[] = $cell;
    }
    fclose($input);
    return $optionData;
}

function getHitsData(string $slug): array
{
    $input = fopen(DETAILED_STATS_FILE, "r");
    $columns = array("year" => "0", "week" => "1", "index" => "2", "dpt" => "3", "weather" => "4", "api" => "5", "stats" => "6");
    $hitsData = array();
    fgets($input);
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        $count = intval($data[$columns[$slug]]);
        $key = "Sem." . $data[$columns["week"]];
        $hitsData[$key] = $count;
    }
    fclose($input);
    return $hitsData;
}

function getRegionData(): array
{
    $regionNames = array(
        "11" => "IDF", "24" =>  "Centre-Val de Loire", "27" => "Bourgogne-Franche-Comté", "28" =>  "Normandie", "32" =>  "Hauts-De-France",
        "44"  => "Grand Est", "52"  => "Pays de la Loire", "53" =>  "Bretagne", "75" =>  "Nouvelle-Aquitaine",
        "76" =>  "Occitanie", "84" =>  "Auvergne-Rhône-Alpes", "93"  => "PACA", "94" =>  "Corse"
    );

    $regionData = array();

    $input = fopen(DPT_STATS_FILE, "r");
    $REGION_CODE = 0;
    $COUNT = 2;
    //We call fgets once to skip the first line of our csv as it doesn't contain relevant information.
    fgets($input);
    while ((($data = fgetcsv($input, ",")) !== FALSE)) {
        $count = intval($data[$COUNT]);
        $region = $data[$REGION_CODE];

        /*
        if (isset($regionNames[$region])) {
            $key = $regionNames[$region];
            if (isset($regionData[$key])) {
                $regionData[$key] += $count;
            } else {
                $regionData[$key] = $count;
            }
        }*/

        if (isset($regionData[$region])) {
            $regionData[$region] += $count;
        } else {
            $regionData[$region] = $count;
        }
    }

    fclose($input);

    return $regionData;
}
