<?php
//error_reporting(E_ALL);
//ini_set('error_log', __DIR__ . '/log.txt');
//error_log('Запись в лог', 0);
$data = json_decode(file_get_contents('php://input'), true);
$newChat = $data['my_chat_member']['chat']['title'];
var_dump($data);
$cron = $_GET['cron'];
$crontime = $_GET['crontime'];
// определяем кодировку
header('Content-type: text/html; charset=utf-8');
if (!$cron) $cron = 0;
if (!$crontime) $crontime = 0;
// Создаем объект бота
$bot = new Bot($cron, $crontime);
// Обрабатываем пришедшие данные
$bot->init('php://input');

/**
 * Class Bot
 */
class Bot
{
    private $cron;
    private $crontime;
    private $pathData = '/data.json';

    function __construct($cron, $crontime)
    {
        $this->cron = $cron;
        $this->crontime = $crontime;
    }

    // <bot_token> - созданный токен для нашего бота от @BotFather
    private $botToken = "1915978869:AAG1j7fOMEk218saD6yY2usCltly36wOyYk";

    private $arr_managers = [
        1656594297 => [
            'name' => 'Linebet Africa FR',
            'counter' => 0
        ],
        1862633986 => [
            'name' => 'Nikita Sergeyvich',
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
        2137518532 => [
            'name' => 'Linebet BD',
            'counter' => 0
        ],
        1928875918 => [
            'name' => 'Nelson Dsouza',
            'counter' => 0
        ],
        5217432820 => [
            'name' => 'Linebet BD2',
            'counter' => 0
        ],
        5343624925 => [
            'name' => 'Linebet BD 3',
            'counter' => 0
        ],
        5391936892 => [
            'name' => 'LINEBET Somalia/Djibouti',
            'counter' => 0
        ]
    ];
    private $all_count = 0;
    // адрес для запросов к API Telegram
    private $apiUrl = "https://api.telegram.org/bot";

    
    // -1001447096198 -тест
    // -1001395237869 - Чат Германа
    public function init($data_php)
    {
        // создаем массив из пришедших данных от API Telegram
        $data = $this->getData($data_php);

        //включаем логирование будет лежать рядом с этим файлом
//        $this->setFileLog( $data, "log.txt" );

        if (isset($data['callback_query'])) {
            $chat_id = -1001395237869;
            $callback = $data['callback_query']['data'];
            $id = $data['callback_query']['message']['message_id'];
            if ($callback == "Оставить") {
                $array = $this->getDataArray();
                $array[$id]['clear'] = false;
                file_put_contents(__DIR__ . $this->pathData, json_encode($array));
                $this->editMessageText($chat_id, $id, "Платежные данные партнера уже привязаны,сообщение оставлено");
            }
        }
        if (!$data) $data = array();
        if (array_key_exists('message', $data) && !isset($data['callback_query'])) {
            $chat_id = $data['message']['chat']['id'];
            $message = $data['message']['text'];
            if ($chat_id == "-1001395237869") {

                $y = date('Y');
                $m = 12;

                // проверяем существование файла
                if (!file_exists("messages/$y.$m.json")) {
                    file_put_contents("messages/$y.$m.json", '');
                }
                $id = $data['message']['message_id'];

                $id_tg = $data['message']['from']['id'];
                $date = $data['message']['date'];
                $arr = [
                    "id" => $id,
                    "date" => $date,
                    "text" => $message,
                    "id_tg" => $id_tg

                ];
                $flag = 1;
                $files = glob("messages/*");
                $matches6 = preg_match("/[0-9]{7}/", $message, $id_6_mes);
                $matches9 = preg_match("/[0-9]{9}/", $message, $id_9_mes);
                if(stristr($id_9_mes[0], $id_6_mes[0]) !== false){
                    $matches6 = preg_match("/[0-9]{6}/", $message, $id_6_mes);
                }
                $noSpaceMessage = str_replace(" ", '', $message);

                if ($id_6_mes[0] && $id_9_mes[0] && strlen(utf8_decode($noSpaceMessage)) <= 43 && strlen(utf8_decode($noSpaceMessage)) >= 15 && stristr($message, "Z") == false && stristr($id_9_mes[0], $id_6_mes[0]) == false) {
                    foreach ($files as $file) {
                        $jsonMessage = file_get_contents("$file");
                        if (empty($jsonMessage)) {
                            $jsonMessage = array();
                        } else {
                            $jsonMessage = json_decode($jsonMessage, true);
                        }
                        for ($i = 0; $i < count($jsonMessage); $i++) {
                            $json_text = $jsonMessage[$i]['text'];
                            $json_id = $jsonMessage[$i]['id'];
                            $matches = preg_match("/[0-9]{7}/", $json_text, $id_6);
                            $matches = preg_match("/[0-9]{9}/", $json_text, $id_9);
                            if(stristr($id_9[0], $id_6[0]) !== false){
                                $matches = preg_match("/[0-9]{6}/", $json_text, $id_6);
                            }
                            if ($id_6[0] && $id_9[0] && strlen(utf8_decode($json_text)) <= 43 && strlen(utf8_decode($json_text)) >= 15 && stristr($json_text, "Z") == false && stristr($id_9[0], $id_6[0]) == false) {

                                if ($id_6_mes[0] == $id_6[0] && $id_9_mes[0] == $id_9[0] && $matches6 == 1 && $matches9 == 1) {
                                    $buttons = $this->getInlineKeyBoard([[["text" => "❗Оставить❗", "callback_data" => "Оставить"]]]);
                                    $idNotifications1 = $this->sendMessageReply(-1001395237869, "Платежные данные партнера уже привязаны, сообщение удалится автоматически через 1 минуту, если нужно его оставить нажмите «❗Оставить❗»", $id, $buttons);
                                    $idNotifications2 = $this->sendMessageReply(-1001395237869, "Вот это сообщение", $json_id);
                                    $this->saveData($id, $idNotifications1, $idNotifications2);
                                    $flag = 0;
                                    break;
                                } elseif ($id_6_mes[0] == $id_6[0] && $matches6 == 1 && $matches9 == 1) {
                                    $flag = $this->sendResponse($id, $json_id, "Данный партнер уже привязал аккаунт игрока");
                                    break;
                                } elseif ($id_9_mes[0] == $id_9[0] && $matches6 == 1 && $matches9 == 1) {
                                    $flag = $this->sendResponse($id, $json_id, "Этот аккаунт игрока уже привязан к другому партнеру! Требуется проверка!");
                                    break;
                                }
                            }

                        }

                    }
                    if ($flag) {
                        $jsonMessage = file_get_contents("messages/$y.$m.json");
                        if (empty($jsonMessage)) {
                            $jsonMessage = array();
                        } else {
                            $jsonMessage = json_decode($jsonMessage, true);
                        }


                        array_push($jsonMessage, $arr);
                        file_put_contents("messages/$y.$m.json", json_encode($jsonMessage));
                    }
                }

            }

        }

        if ($message == '/info_test'){
//            $this->requestToTelegram(['chat_id' => 659025951, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/bdCharts.php?' . hash('sha256',  date("d.m.Y h:i:s"))], 'sendPhoto');
        }
        if ($this->cron == 1 || $message == "/info_week" && !isset($data['callback_query'])) {

            $y = date('Y');
            $m = 12;
            $json = file_get_contents("messages/$y.$m.json");
            $json = json_decode($json, true);
            $return = $this->date_search($json);
            usort($return, function ($a, $b) {
                return ($b['counter'] - $a['counter']);
            });
            $text_managers = "Статистика за неделю: \n\n";
            $place = 1;
            foreach ($return as $value) {
                $name = $value['name'];
                $counter = $value['counter'];
                if ($place == 1) {
                    $text_managers .= "$place. $name = $counter 🏆 \n";
                } else {
                    $text_managers .= "$place. $name = $counter \n";
                }
                $place++;
            }
            $this->sendMessage(-1001395237869, $text_managers);
//            $this->sendMessage(659025951, $text_managers);
            $this->sendCharts();
        }
        if ($this->crontime == 1 && !isset($data['callback_query'])) {
            $array = $this->getDataArray();
            if ($array) {
                $end_date = new DateTime(date("d-m-Y H:i"));
                foreach ($array as $key => $value) {
                    $start_date = new DateTime(date("d-m-Y H:i", $value['time']));
                    $since_start = $start_date->diff($end_date);
                    if ($since_start->i >= 1) {
                        if ($value['clear'] === true) {
                            for($i = 0; $i<count($value['message']);$i++){
                                $this->deleteMessage(-1001395237869, $value['message'][$i]);
                            }
                        }
                        unset($array[$key]);
                    }
                }
                file_put_contents(__DIR__ . $this->pathData, json_encode($array));
            }
        }
        //отправка графиков
//        if ($this->cron || $data['message']['text'] == "/getcharts") {
//            $this->sendCharts();
//        }

    }
    public function sendCharts($groupChatId="-1001395237869", $userIds=["122815990","882013448"]) {
        $lastMessage = "";
        $lastMessage = json_decode(file_get_contents('lastChart.txt'), true);
        foreach ($userIds as $userId) {
            foreach($this->arr_managers as $id => $manager) {
                $this->requestToTelegram(['chat_id' => $userId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/singleManager.php?' . hash('sha256',  date("d.m.Y h:i:s")) . "&manager=$id"], 'sendPhoto');
            }
            $this->requestToTelegram(['chat_id' => $userId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/totalChart.php?' . hash('sha256',  date("d.m.Y h:i:s"))], 'sendPhoto');
            $this->requestToTelegram(['chat_id' => $userId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/bdCharts.php?' . hash('sha256',  date("d.m.Y h:i:s"))], 'sendPhoto');
            $this->requestToTelegram(['chat_id' => $userId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/uzCharts.php?' . hash('sha256',  date("d.m.Y h:i:s"))], 'sendPhoto');
            $text_managers = "Общая статистика = $this->all_count";
            $this->sendMessage($userId, $text_managers);

        }
        if (!isset($lastMessage['result']['message_id'])) file_put_contents('lastChart.txt', $this->requestToTelegram(['chat_id' => $groupChatId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/totalChart.php?' . hash('sha256',  date("d.m.Y h:i:s"))], 'sendPhoto'));
        else file_put_contents('lastChart.txt', $this->requestToTelegram(['chat_id' => $groupChatId, 'photo' => $_SERVER['SERVER_NAME'] . '/RepeatMessages/chartsDraw/totalChart.php?' . hash('sha256',  date("d.m.Y h:i:s")), 'reply_to_message_id' => $lastMessage['result']['message_id']], 'sendPhoto'));
    }

    /**
     * сохранения данных в data.json
     * @param $id, $idNotifications1, $idNotifications2
     */
    private function saveData($id, $idNotifications1, $idNotifications2)
    {
        $array = $this->getDataArray();
        $array[$idNotifications1] = [
            "message" => [$id, $idNotifications1, $idNotifications2],
            "time" => time(),
            "clear" => true
        ];
        file_put_contents(__DIR__ . $this->pathData, json_encode($array));
    }

    /**
     * получение данных о сообщениях
     * @return array
     */
    private function getDataArray()
    {
        $json = file_get_contents(__DIR__ . $this->pathData);
        if ($json) {
            $array = json_decode($json, true);
        } else {
            $array = [];
        }
        return $array;
    }

    /**
     * отправка ответа на повторения
     * @param $id, $json_id, $message
     * @return int
     */
    private function sendResponse($id, $json_id, $message)
    {
        $this->sendMessageReply(-1001395237869, $message, $id);
        $this->sendMessageReply(-1001395237869, "Вот это сообщение", $json_id);
        return $flag = 0;
    }

    /**
     * удаление сообщения
     * @param $chatId, $messageID
     */
    private function deleteMessage($chatId, $messageID)
    {
        $content = [
            'chat_id' => $chatId,
            'message_id' => $messageID,
        ];
        // отправляем запрос на удаление
        $this->requestToTelegram($content, "deleteMessage");
    }

    /**
     * изменения сообщения
     * @param $chatId, $messageId, $message, $buttons = false
     */
    private function editMessageText($chatId, $messageId, $message, $buttons = false)
    {
        $dataSend = array(
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'reply_markup' => $buttons,
            'parse_mode' => 'HTML'
        );
        $this->requestToTelegram($dataSend, "editMessageText");
    }

    /**
     * подсчёт кол-во сообщений для каждого менеджера
     * @param $arr
     * @return array
     */
    private function date_search($arr)
    {
        $arr_managers = $this->arr_managers;
        for ($i = 0; $i <= count($arr); $i++) {

            if ($arr_managers[$arr[$i]['id_tg']]) {
                $date = date("Y-m-d H:i", $arr[$i]['date']);
                $dateDiff = date_diff(new DateTime(date("Y-m-d 15:00", time())), new DateTime($date));
                if ($dateDiff->days < 7 && $dateDiff->invert == 1) {
                    $arr_managers[$arr[$i]['id_tg']]['counter']++;
                    $this->all_count++;
                }
            }

        }
        return ($arr_managers);
    }

    /**
     * клавиатура
     * @param $data
     * @return array
     */
    private function getKeyBoard($data)
    {
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => false,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }

    /**
     * inline клавиатура
     * @param $data
     * @return array
     */
    private
    function getInlineKeyBoard($data)
    {
        $keyboard = array(
            "inline_keyboard" => $data,
            "one_time_keyboard" => false,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }

    /**
     * функция отправки текстового сообщения
     * @param $chat_id, $text
     * @return string
     */
    private function sendMessage($chat_id, $text)
    {
        $id_message = $this->requestToTelegram([
            'chat_id' => $chat_id,
            'text' => $text,
            "parse_mode" => "HTML",
            "disable_web_page_preview" => true,
        ], "sendMessage");
        $rezultArray = json_decode($id_message, true);
        return ($rezultArray['result']['message_id']);
    }

    /**
     * функция ответа текстового сообщения
     * @param $chat_id, $text, $id, $buttons = false
     * @return string
     */
    private function sendMessageReply($chat_id, $text, $id, $buttons = false)
    {
        $id_message = $this->requestToTelegram([
            'chat_id' => $chat_id,
            'text' => $text,
            "parse_mode" => "HTML",
            "disable_web_page_preview" => true,
            "reply_to_message_id" => $id,
            'reply_markup' => $buttons,
        ], "sendMessage");
        $rezultArray = json_decode($id_message, true);
        return ($rezultArray['result']['message_id']);
    }

    /**
     * функция логирования в файл
     * @param $data,$file
     */
    private function setFileLog($data, $file)
    {
        $fh = fopen($file, 'a') or die('can\'t open file');
        ((is_array($data)) || (is_object($data))) ? fwrite($fh, print_r($data, TRUE) . "\n") : fwrite($fh, $data . "\n");
        fclose($fh);
    }

    /**
     * Парсим что приходит преобразуем в массив
     * @param $data
     * @return mixed
     */
    private function getData($data)
    {
        return json_decode(file_get_contents($data), TRUE);
    }

    /** Отправляем запрос в Телеграмм
     * @param $data
     * @param string $type
     * @return mixed
     */
    private function requestToTelegram($data, $type)
    {
        $result = null;

        if (is_array($data)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->botToken . '/' . $type);
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }

}

//var_dump($bot);
$chat = $bot->getChat()->getId();
var_dump($chat);

// define('BOT_TOKEN','12345:abcde');//replace with your bot token
 $command_prefix_url='https://api.telegram.org/bot' . '1915978869:AAG1j7fOMEk218saD6yY2usCltly36wOyYk' ;

$update = json_decode(file_get_contents('php://input')); //retrieves data sent by telegram

$chat_id=$update->message->chat->id; //retrives `chat_id`

$rep = json_decode(file_get_contents($command_prefix_url . '/SendMessage?chat_id=' .
    $chat_id    . '&text=' . urldecode('Hello '.(string)$chat_id)));
    var_dump($rep);
?>