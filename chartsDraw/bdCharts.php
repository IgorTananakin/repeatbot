<?php
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
require_once 'graph.php';
require_once 'managers.php';
$graph = new Graph(500, 500);
$graph->drawAxes()->drawDivisions(true, true, 20)->getJSONData("https://jsonb.ru/RepeatMessages/messages/2022.12.json", $arr_managers)->addFilter([1862633986, 1928875918, 2137518532, 1660455309,5217432820])->drawTotalChart(true)->drawLabels(['x' => '', 'y' => ''], 1, 10)->drawGraphics(false, false)->drawLegend(2, $arr_managers, false)->showGraph();