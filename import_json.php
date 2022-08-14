<?php

//$json = file_get_contents( "result.json" );
//$json = json_decode( $json, true );

//$j = 0;
//foreach ( $json[ 'messages' ] as $value ) {
//    if ( !empty($value[ 'text' ]) ) {
//        $matches = preg_match( "/[0-9]{6}/", (string) $value[ 'text' ], $id_6 );
//        $matches = preg_match( "/[0-9]{9}/", (string) $value[ 'text' ], $id_9 );
//        $noSpaceMessage = str_replace(" ", '', $value[ 'text' ]);
//        if ( $id_6[ 0 ] && $id_9[ 0 ] && strlen( utf8_decode( $noSpaceMessage ) ) <= 35 && strlen( utf8_decode( $noSpaceMessage ) ) >= 15 && stristr( $noSpaceMessage, "Z" ) == false && stristr( $id_9[ 0 ], $id_6[ 0 ] ) == false ) {
//
//          $id = $value['from_id'];
//          $id = str_replace("user", '', $value['from_id']);
//          $date = str_replace("T", ' ', $value['date']);
//          $year= explode('-',$date);
//          $date = strtotime($date);
//
//
//          if($year[0] == 2022) {
//              $arr[] = [
//                  "id" => $value['id'],
//                  "date" => $date,
//                  "text" => $value['text'],
//                  "id_tg" => $id
//              ];
//              $j++;
//          }
//
////            echo $datetime = date("Y-m-d", strtotime( $date ." -7 days")) . "<br>";
////                            echo "$id_6[0] | $id_9[0] <br>";
////            print_r( $value );
//
//        }
//    }
//
//}
//echo $j;
//echo "<pre>";
//print_r($arr);
//echo "</pre>";

//file_put_contents( "messages/2022.12.json", json_encode( $arr ) );


//$return = date_search($json);
//usort($return, function($a, $b){
//    return ($b['counter'] - $a['counter']);
//});
//$text_managers = "Статистика за неделю: <br><br>";
//$place = 1;
//foreach($return as $value) {
//    $name = $value['name'];
//    $counter = $value['counter'];
//    if($place == 1) {
//        $text_managers .= "$place.$name = $counter 🏆 <br>";
//    }else{
//        $text_managers .= "$place.$name = $counter <br>";
//    }
//    $place++;
//}
//echo $text_managers;



//echo "<pre>";
//print_r( $return );
//echo "</pre>";
//echo "<pre>";
//print_r( $json );
//echo "</pre>";
//
//$y = date( 'Y' );
//$m = date( 'm' );

// проверяем существование файла
//if ( !file_exists( "messages/$y.$m.json" ) ) {
//  file_put_contents( "messages/$y.$m.json", '' );
//}
//foreach ( $json[ 'messages' ] as $value ) {
//  $text = $value[ 'text' ];
//  if ( gettype( $text ) == "string" ) {
//    if ( strpos( $text, "ПП" ) !== false || strpos( $text, "пп" ) !== false ) {
//      $arr[] = [
//        "id" => $value[ 'id' ],
//        "text" => $text
//      ];
//
//    }
//  }
//}
//$jsonMessage = file_get_contents( "messages/$y.$m.json" );
//if ( empty( $jsonMessage ) ) {
//  $jsonMessage = array();
//} else {
//  $jsonMessage = json_decode( $jsonMessage, true );
//}
//
//
//array_push( $jsonMessage, $arr );
//file_put_contents( "messages/$y.$m.json", json_encode( $jsonMessage ) );
//      echo "<pre>";
//      print_r($jsonMessage);
//      echo "</pre>";




function date_search($arr) {

    $arr_managers = [
       1656594297 => [
           'name' => 'Linebet Africa FR',
           'counter' => 0
       ],
       1862633986 => [
           'name' => 'Nikita Sergeyvich',
           'counter' => 0
       ],
       1295698464 => [
           'name' => 'Linebet Partners Uzbekistan',
           'counter' => 0
       ],
       802243803 => [
           'name' => 'Linebet Partners',
           'counter' => 0
       ],
       2034540659 => [
           'name' => 'Linebet India',
           'counter' => 0
       ],
       1440214573 => [
           'name' => 'LB Glav Admin .',
           'counter' => 0
       ],
       1660455309 => [
           'name' => 'Віктор Петрович',
           'counter' => 0
       ],
       569032193 => [
           'name' => 'LineBet',
           'counter' => 0
       ],
       2137518532 => [
           'name' => 'Linebet BD',
           'counter' => 0
       ]        
    ];
        
       
    for($i = 0; $i <= count($arr); $i++) {
            
        if($arr_managers[$arr[$i]['id_tg']]) {
            $date = date("Y-m-d",$arr[$i]['date']);
            $dateDiff = date_diff(new DateTime(), new DateTime($date))->days;
            if($dateDiff <= 7) {
                $arr_managers[$arr[$i]['id_tg']]['counter']++;
            }
        }
   
    }
    return($arr_managers);
}