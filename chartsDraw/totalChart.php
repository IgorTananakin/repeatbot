<?php
require_once 'graph.php';
$graph = new Graph(500, 500);
$graph->drawAxes()->drawDivisions(true, true, 20)->getJSONData("https://jsonb.ru/RepeatMessages/messages/2022.12.json")->drawTotalChart(true)->drawLabels(['x' => '', 'y' => ''], 1, 10)->showGraph();