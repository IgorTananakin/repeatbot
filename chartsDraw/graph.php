<?php
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
class Graph {
    private $graph = null; //graph image
    // image size
    private $width;
    private $height;
    // colours
    private $white;
    private $black;
    private $gray;
    private $chartColours;
    // begin and end cords
    private $x;
    private $y;
    // lines width
    private $lineWidth;
    private $chartLineWidth;
    //graphics data
    private $charts;
    private $totalChart;
    private $extremumPoints;
    private $coefficients;
    //divsSettings
    private $mainDivs;
    private $minorDivs;
    private $mainDivsStep;
    private $minorDivsStep;
    //Charts colours
    private $managerColour;

    public function __construct($width=100, $height=100) {
        $this->width = $width;
        $this->height = $height;
        $this->graph = imagecreatetruecolor($this->width, $this->height + 100);
        imagefill($this->graph, 0, 0, 0xFFFFFF); 
        $this->white = imagecolorallocate($this->graph, 255, 255, 255);
        $this->black = imagecolorallocate($this->graph, 0, 0, 0);
        $this->gray = imagecolorallocate($this->graph, 200, 200, 200);
        $this->chartColours[0] = imagecolorallocate($this->graph, 100, 0, 100);
        $this->chartColours[1] = imagecolorallocate($this->graph, 255, 0, 0);
        $this->chartColours[2] = imagecolorallocate($this->graph, 0, 255, 0);
        $this->chartColours[3] = imagecolorallocate($this->graph, 0, 0, 255);
        $this->chartColours[4] = imagecolorallocate($this->graph, 0, 170, 170);
        $this->chartColours[5] = imagecolorallocate($this->graph, 255, 0, 255);
        $this->chartColours[6] = imagecolorallocate($this->graph, 170, 170, 0);
        $this->chartColours[7] = imagecolorallocate($this->graph, 255, 170, 170);
        $this->chartColours[8] = imagecolorallocate($this->graph, 255, 170, 0);
        $this->chartColours[9] = imagecolorallocate($this->graph, 0, 100, 255);
        $this->chartColours[10] = imagecolorallocate($this->graph, 0, 170, 255);
        $this->x['begin'] = $this->width * 0.05 > 10 ? $this->width * 0.05 : 10;
        $this->y['begin'] = $this->height * 0.05 > 10 ? $this->height * 0.05 : 10;
        $this->x['end'] = $this->width * 0.05 > 10 ? $this->width * 0.95 : $this->width-10;
        $this->y['end'] = $this->height * 0.05 > 10 ? $this->height * 0.95 : $this->height-10;
        $this->lineWidth = ($this->width + $this->height) / 2 * 0.01;
        if ($this->lineWidth < 1) $this->lineWidth = 1;
        if ($this->lineWidth > 5) $this->lineWidth = 5;
        $this->chartLineWidth = $this->lineWidth / 2;
        if ($this->chartLineWidth < 1) $this->chartLineWidth = 1;
        $this->extremumPoints['values']['min']['x'] = 0;
        $this->extremumPoints['values']['max']['x'] = 0;
        $this->extremumPoints['values']['min']['y'] = 0;
        $this->extremumPoints['values']['max']['y'] = 0;
        $this->coefficients['x'] = 1;
        $this->coefficients['y'] = 1;
        $this->mainDivs = true;
        $this->minorDivs = false;
        $this->mainDivsStep = 20;
        $this->minorDivsStep = 5;
    }
    public function drawAxes() {
        imagesetthickness($this->graph, $this->lineWidth);
        imageline($this->graph, $this->x['begin'], $this->y['end'], $this->x['end'], $this->y['end'], $this->black);
        imageline($this->graph, $this->x['begin'], $this->y['begin'], $this->x['begin'], $this->y['end'], $this->black);
        $xArrow[0] = $this->x['end'] - $this->lineWidth / 2.5;
        $xArrow[1] = $this->y['end'] - $this->lineWidth * 1.2;
        $xArrow[2] = $this->x['end'] + $this->lineWidth * 2;
        $xArrow[3] = $this->y['end'];
        $xArrow[4] = $this->x['end'] - $this->lineWidth / 2.5;
        $xArrow[5] = $this->y['end'] + $this->lineWidth * 1.2;
        imagefilledpolygon($this->graph, $xArrow, 3, $this->black);
        $xArrow[0] = $this->x['begin'] - $this->lineWidth * 1.2; 
        $xArrow[1] = $this->y['begin'] - $this->lineWidth / 2.5; 
        $xArrow[2] = $this->x['begin']; 
        $xArrow[3] = $this->y['begin'] - $this->lineWidth * 2;
        $xArrow[4] = $this->x['begin'] + $this->lineWidth * 1.2;
        $xArrow[5] = $this->y['begin'] - $this->lineWidth / 2.5;
        imagefilledpolygon($this->graph, $xArrow, 3, $this->black);
        return $this;
    }

    public function drawDivisions(bool $mainDivs, bool $minorDivs, $mainDivsStep = 10, $minorDivsStep = 5) {
        $this->mainDivs = $mainDivs;
        $this->minorDivs = $minorDivs;
        $this->mainDivsStep = $mainDivsStep;
        $this->minorDivsStep = $minorDivsStep;
        if ($mainDivs) {
            imagesetthickness($this->graph, $this->lineWidth / 1.5);
            for ($step = $this->x['begin'] + $mainDivsStep; $step < $this->x['end']; $step += $mainDivsStep) {
                imageline($this->graph, $step, $this->y['end'] - $this->lineWidth, $step, $this->y['end'], $this->black);
            }
            for ($step = $this->y['begin'] + $mainDivsStep; $step < $this->y['end']; $step += $mainDivsStep) {
                imageline($this->graph, $this->x['begin'], $step, $this->x['begin']  + $this->lineWidth, $step , $this->black);
            }
            imagesetthickness($this->graph, 1);
            for ($step = $this->x['begin'] + $mainDivsStep; $step < $this->x['end']; $step += $mainDivsStep) {
                imagedashedline($this->graph, $step, $this->y['begin'] - $this->lineWidth, $step, $this->y['end'], $this->gray);
            }
            for ($step = $this->y['begin'] + $mainDivsStep; $step < $this->y['end']; $step += $mainDivsStep) {
                imagedashedline($this->graph, $this->x['begin'], $step, $this->x['end'], $step , $this->gray);
            }
            
        }
        if ($minorDivs) {
            imagesetthickness($this->graph, 1);
            for ($step = $this->x['begin'] + $minorDivsStep; $step < $this->x['end']; $step += $minorDivsStep) {
                imageline($this->graph, $step, $this->y['end'] - $this->lineWidth, $step, $this->y['end'], $this->black);
            }
            for ($step = $this->y['begin'] + $minorDivsStep; $step < $this->y['end']; $step += $minorDivsStep) {
                imageline($this->graph, $this->x['begin'], $step, $this->x['begin']  + $this->lineWidth, $step , $this->black);
            }
        }
        return $this;
    }
    
    public function getJSONData($url, $managers = []) {
        $data = file_get_contents($url);
        $data = json_decode($data, true);
        $firstDay = strtotime(date('d.m.y'));
        $lastDay = strtotime(date('d.m.y', strtotime('01.09.20')));
        $allMessages = [];
        $managersResult = [];
        foreach ($data as $message) {
            $managersResult[$message['id_tg']] = [];
            $allMessages[$message['id_tg']][]= $message['date'];
            if ($firstDay > $message['date']) $firstDay = $message['date'];
            if ($lastDay < $message['date']) $lastDay = $message['date'];
        }
        $firstTuesday = $firstDay;
        $lastTuesday = $lastDay;
        while (date("w", $firstTuesday) != 2) $firstTuesday = strtotime('+1 day', $firstTuesday);
        if (date("H", $firstTuesday) > 15) while (date("H", $firstTuesday) != 15) $firstTuesday = strtotime('-1 hours', $firstTuesday);
        else while (date("H", $firstTuesday) != 15) $firstTuesday = strtotime('+1 hours', $firstTuesday);
        while (date("i", $firstTuesday) != 0) $firstTuesday = strtotime('-1 minutes', $firstTuesday);
        while (date("s", $firstTuesday) != 0) $firstTuesday = strtotime('-1 seconds', $firstTuesday);
        while (date("w", $lastTuesday) != 2) $lastTuesday = strtotime('-1 day', $lastTuesday);
        
        foreach ($managersResult as $managerID => $managerResult) {
            for ($tuesday = $firstTuesday; $tuesday < strtotime('+1 days', $lastTuesday); $tuesday = strtotime('+7 days', $tuesday)) {
                $managersResult[$managerID][$tuesday] = 0;
            }
        }
        foreach ($managersResult as $managerID => $managerResult) {
            foreach ($managerResult as $tuesday => $value) {
                foreach ($allMessages[$managerID] as $number => $message) {
                    if ($tuesday >= $message) {
                        $managersResult[$managerID][$tuesday]++;
                        unset($allMessages[$managerID][$number]);
                    }
                    if ($tuesday < $message) break;
                }
            }
        }
        $outputData = [];
        foreach ($managersResult as $managerID => $managerResult) {
            $outputData[$managerID] = [];
            foreach ($managerResult as $tuesday => $value) {
                $outputData[$managerID][] = ['x' => date("d.m", $tuesday), 'y' => $value];
            }
        }
        $finalBeginDate = $firstTuesday;
        $maxSize = 0;
        foreach ($outputData as $manager => $data) {
            if (sizeof($outputData[$manager]) > 10) {
                $outputData[$manager] = array_slice($outputData[$manager], sizeof($outputData[$manager]) - 10);
            }
            if (strtotime($outputData[$manager][0]['x']) > $finalBeginDate) $finalBeginDate = strtotime($outputData[$manager][0]['x']);
            if ($maxSize < sizeof($outputData[$manager])) $maxSize = sizeof($outputData[$manager]);
        }
        foreach ($managers as $id => $manager) {
            if (!isset($outputData[$id])) {
                $outputData[$id] = [];
                for ($point = 0; $point < $maxSize; ++$point) {
                    $currentWeek = '+' . 7 * $point . ' days';
                    $outputData[$id][] = ['x' => date("d.m", strtotime($currentWeek, $finalBeginDate)), 'y' => 0];
                }
            }
        }
        $this->getData($outputData);
        return $this;
    }

    public function getData(array $data) {
        $this->charts = $data;
        foreach ($data as $chart) {
            if (sizeof($chart) > $this->extremumPoints['values']['max']['x']) {
                $this->extremumPoints['values']['max']['x'] = sizeof($chart);
                $this->extremumPoints['values']['max']['chart'] = $chart;
            } 
            foreach ($chart as $point) {
                if ($point['y'] > $this->extremumPoints['values']['max']['y']) $this->extremumPoints['values']['max']['y'] = $point['y'];
            } 
        }
        $this->coefficients['x'] = ($this->x['end'] - $this->x['begin']) / $this->extremumPoints['values']['max']['x'];
        $this->coefficients['y'] = ($this->y['end'] - $this->y['begin']) / $this->extremumPoints['values']['max']['y'];
        
        return $this;
    }

    public function addFilter($managers) {
        foreach ($this->charts as $manager => $chart) {
            if (!in_array($manager, $managers)) unset($this->charts[$manager]);
        }
        return $this;
    }

    public function drawLabels(array $labels, $stepX = 2,  $stepY = 100) {
        imagestringup($this->graph, 5, $this->x['begin'] - $this->lineWidth * 4, $this->y['begin'] + $this->lineWidth * 8, $labels['y'], $this->black);
        imagestring($this->graph, 5, $this->x['end'] - $this->lineWidth * 8, $this->y['end'] + $this->lineWidth, $labels['x'], $this->black);
        $stepsTotal = sizeof($this->extremumPoints['values']['max']['chart']) / $stepX;
        $strStep = ($this->x['end'] - $this->x['begin']) / $stepsTotal;
        for ($step = 0, $stepG = $this->x['begin']; $step < sizeof($this->extremumPoints['values']['max']['chart']); $step += $stepX, $stepG += $strStep) {
            imagestring($this->graph, 3, $stepG - 15, $this->y['end'] + $this->lineWidth, $this->extremumPoints['values']['max']['chart'][$step]['x'], $this->black);
        }
        $stepsTotal = $this->extremumPoints['values']['max']['y'] / $stepY;
        $strStep = ($this->y['begin'] - $this->y['end']) / $stepsTotal;
        for ($step = 0, $stepG = $this->y['end']; $step <= $this->extremumPoints['values']['max']['y']; $step += $stepY, $stepG += $strStep) {
            imagestring($this->graph, 2, $this->x['begin'] - $this->lineWidth - 15, $stepG - 5, $step, $this->black);
        }
        return $this;
    }

    public function drawGraphics(bool $isVisible = true, bool $useColours = false) {
        imagesetthickness($this->graph, $this->chartLineWidth);
        $colourNumber = 0;
        foreach ($this->charts as $manager => $chart) {
            if ($colourNumber == sizeof($this->chartColours)) {
                $this->chartColours []= imagecolorallocate($this->graph, random_int(0, 255), random_int(0, 255), random_int(0, 255));
            }
            $currentColour = $this->chartColours[$colourNumber];
            $red = $this->chartColours[1];
            $green = $this->chartColours[2];
            $colourNumber++;
            $this->managerColour[$manager] = $currentColour;
            if ($isVisible) {
                for ($pointNum = 0; $pointNum < sizeof($chart) - 1; ++$pointNum) {
                    if ($useColours) {
                        if ($chart[$pointNum]['y'] > $chart[$pointNum + 1]['y']) $currentColour = $red;
                        if ($chart[$pointNum]['y'] < $chart[$pointNum + 1]['y']) $currentColour = $green;
                        if ($chart[$pointNum]['y'] == $chart[$pointNum + 1]['y']) $currentColour = $this->black;
                    }
                    imageline($this->graph, $this->x['begin'] + $pointNum * $this->coefficients['x'], $this->y['end'] - $chart[$pointNum]['y'] * $this->coefficients['y'], $this->x['begin'] + ($pointNum + 1) * $this->coefficients['x'], $this->y['end'] - $chart[$pointNum + 1]['y'] * $this->coefficients['y'], $currentColour);
                }
            }
        }
        return $this;
    }

    public function drawLegend($rows = 2, $managers = [], $useColours = true) {
        $currentX = $this->x['begin'] - 20;
        $currentY = $this->y['end'] + 30;
        $currentRow = 0;
        
        foreach ($this->managerColour as $manager => $colour) {
            if (!isset($managers[$manager]['name'])) continue;
            if (mb_strlen($managers[$manager]['name']) > 23) {
                $managers[$manager]['name'] = mb_substr($managers[$manager]['name'], 0, 20);
                $managers[$manager]['name'] .= "...";
            }
            if (!$useColours) $colour = $this->black;
            imagettftext($this->graph, 10, 0, $currentX, $currentY, $colour, 'fonts/arial.ttf', $managers[$manager]['name']);
            if ($currentRow < $rows) {
                ++$currentRow;
                $currentX += 150;
            } else {
                $currentX = $this->x['begin'] - 20;
                $currentY += 15;
                $currentRow = 0;
            }
        }
        
        return $this;
    }

    private function calculateTotalChart() {
        foreach($this->extremumPoints['values']['max']['chart'] as $point) {
            $this->totalChart []= ['x' => $point['x'], 'y' => 0];
        }
        foreach ($this->charts as $chart) {
            for ($point = 0; $point < sizeof($chart); ++$point) {
                $this->totalChart[$point]['y'] += $chart[$point]['y'];
            }
        }
        foreach($this->totalChart as $point) {
            if ($point['y'] > $this->extremumPoints['values']['max']['y']) $this->extremumPoints['values']['max']['y'] = $point['y'];
        }
        $this->coefficients['y'] = ($this->y['end'] - $this->y['begin']) / $this->extremumPoints['values']['max']['y'];
    }

    public function drawTotalChart(bool $useColours = false) {
        $this->calculateTotalChart();
        imagesetthickness($this->graph, $this->chartLineWidth);
        $currentColour = $this->black;
        $red = $this->chartColours[1];
        $green = $this->chartColours[2];
        for ($pointNum = 0; $pointNum < sizeof($this->totalChart) - 1; ++$pointNum) {
            if ($useColours) {
                if ($this->totalChart[$pointNum]['y'] > $this->totalChart[$pointNum + 1]['y']) $currentColour = $red;
                if ($this->totalChart[$pointNum]['y'] < $this->totalChart[$pointNum + 1]['y']) $currentColour = $green;
                if ($this->totalChart[$pointNum]['y'] == $this->totalChart[$pointNum + 1]['y']) $currentColour = $this->black;
            }
            imageline($this->graph, $this->x['begin'] + $pointNum * $this->coefficients['x'], $this->y['end'] - $this->totalChart[$pointNum]['y'] * $this->coefficients['y'], $this->x['begin'] + ($pointNum + 1) * $this->coefficients['x'], $this->y['end'] - $this->totalChart[$pointNum + 1]['y'] * $this->coefficients['y'], $currentColour);
        }
        return $this;
    }

    public function showGraph() {
        header("Content-type: image/png");
        imagepng($this->graph);
        imagedestroy($this->graph); 
    }
}



