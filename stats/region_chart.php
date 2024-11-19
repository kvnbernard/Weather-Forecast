<?php

/**
 * The following file is based on the jpgraph simple pie plot example.
 * It can be found at https://jpgraph.net/features/src/show-example.php?target=new_bar1.php
 */
include "../include/stats.inc.php";
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_bar.php");

$regionData = getRegionData();

$legend = array();
$data1y = array();
foreach ($regionData as $key => $value) {
    $data1y[] = $value;
    $legend[] = $key;
}

// Create the graph. These two calls are always required
$graph = new Graph(350, 200, 'auto');
$graph->SetScale("textlin");

$theme_class = new UniversalTheme;
$graph->SetTheme($theme_class);


$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($legend);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

// Create the bar plots
$b1plot = new BarPlot($data1y);

// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetColor("white");
$b1plot->SetFillColor("#cc1111");

$graph->title->Set("Hits par région");

// Display the graph
$graph->Stroke();