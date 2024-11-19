<?php

/**
 * The following file is based on the jpgraph simple pie plot example.
 * It can be found at https://jpgraph.net/features/src/show-example.php?target=new_line1.php
 */
include "../include/stats.inc.php";
require_once("../jpgraph/jpgraph.php");
require_once("../jpgraph/jpgraph_line.php");

$indexData = getHitsData("index", true);
$datay1 = array();
$xaxis = array();
foreach ($indexData as $key => $value) {
    $datay1[] = $value;
    $xaxis[] = $key;
}

//This was recommanded by the php documentation to avoid any issues with the $value and $key variables.
unset($value, $key);
$statsData = getHitsData("stats");
$datay2 = array();
foreach ($statsData as $key => $value) {
    $datay2[] = $value;
}

unset($value, $key);
$weatherData = getHitsData("weather");
$datay3 = array();
foreach ($weatherData as $key => $value) {
    $datay3[] = $value;
}

// Setup the graph
$graph = new Graph(300, 250);
$graph->SetScale("textlin");

$theme_class = new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Historique des hits par page');
$graph->SetBox(false);

$graph->SetMargin(40, 20, 36, 63);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($xaxis);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('index');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('statistiques');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#FF1493");
$p3->SetLegend('prévisions');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();
