<?php
require_once 'graph.php';
require_once 'managers.php';
$graph = new Graph(500, 500);
$graph->drawAxes()->drawDivisions(true, true, 20)->getJSONData("https://jsonb.ru/RepeatMessages/messages/2022.12.json", $arr_managers)->drawLabels(['x' => '', 'y' => ''], 1, 10)->drawGraphics()->drawLegend(2, $arr_managers)->showGraph();