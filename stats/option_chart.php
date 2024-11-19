<?php

/**
 * The following file is based on the jpgraph simple pie plot example.
 * It can be found at https://jpgraph.net/features/src/show-example.php?target=new_pie1.php
 */
include "../include/stats.inc.php";
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_pie.php");

// Organise data
$tmp = getOptionData();
$data = array();
$legends = array();
foreach ($tmp as $key => $value) {
   //Ignore null values as they wouldn't be relevant in the pie plot.
    if ($value["count"] > 0) {
        $data[] = $value["count"];
        $option = ($value["option"] == "hourly") ? "Par heure" : "Par jour";
        $legends[] = $option;
    }
}

// Create the Pie Graph. 
$graph = new PieGraph(350, 250);

$theme_class = "DefaultTheme";
//$graph->SetTheme(new $theme_class());

// Set A title for the plot
$graph->title->Set("Préférences d'affichage");
$graph->SetBox(true);

// Create
$pieplot = new PiePlot($data);
$graph->Add($pieplot);

$pieplot->ShowBorder();
$pieplot->SetColor('black');
$pieplot->SetLegends($legends);

$graph->Stroke();
