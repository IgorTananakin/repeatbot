<?php
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
require_once 'graph.php';
require_once 'managers.php';
$manager = isset($_GET['manager']) ? $_GET['manager'] : 0;
$graph = new Graph(500, 500);
$graph->drawAxes()->drawDivisions(true, true, 20)->getJSONData("https://jsonb.ru/RepeatMessages/messages/2022.12.json", $arr_managers)->addFilter([$manager])->drawLabels(['x' => '', 'y' => ''], 1, 10)->drawGraphics(true, true)->drawLegend(2, $arr_managers, false)->showGraph();