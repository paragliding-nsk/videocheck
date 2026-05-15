<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;

class Video extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    static public function checkNewVideo(){

    $apiKey = config('services.apiKey');
    $xApiKey = config('services.xApiKey');

// Берем список видео из базы, чтобы дальше сравнить со списком, полученным с канала    
    $video1 = Video::all(); 
    $conferenceTreated = 0;
    $countConf=0;

// приводим формат списка видео к идентичному с тем, который получим от youtube
    $k=0;
    foreach ($video1 as $video) {
        $listVideoOld[$k] = $video['ytbID'];
        $k++;
        }
   
// Дальше закомменчен запрос к каналу, который получал количество видео.
// Поскольку все равно supadata получает список из ~25 видео, да еще и с повторами
// просто жестко забил количество принимаемых видео =20, чтобы не тратить лишний запрос.
// Если сервис снова заработает нормально - можно будет раскомментировать блок ниже
// и убрать строку $numb=20;  

//     $curl = curl_init();

// curl_setopt_array($curl, [
//   CURLOPT_URL => "https://api.supadata.ai/v1/youtube/channel?id=UCqTLndb64WSo76e8MCCUkvQ",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 300,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_HTTPHEADER => [
//     "x-api-key: {$xApiKey}"
//   ],
// ]);

// $responseChannel = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   $badAnswer = "cURL Error #:" . $err;
//   return view('badanswer', compact('badAnswer'));
// // } else {
// //   echo $responseChannel;
// }


// $numb = json_decode($responseChannel)->{'videoCount'};
$numb = 20;

// Делаем запрос - получаем список youtube ID видео, первыми идут самые свежие

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.supadata.ai/v1/youtube/channel/videos?limit={$numb}&type=all&id=UCqTLndb64WSo76e8MCCUkvQ",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "x-api-key: {$xApiKey}"
  ],
]);

$responseVideoCount = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  $badAnswer = "cURL Error #:" . $err;
  return view('badanswer', compact('badAnswer'));

}

$mass = json_decode($responseVideoCount);
$i=0;
foreach ($mass->{'videoIds'} as $key => $mass1) {

    $listVideoNew[$i]=$mass1;
    $i++;

}

// Сравниваем полученный список с тем, что уже есть в базе, если ничего нового нет - 
// просто пишем, что ничего нет, если есть - запускаем вторую функцию, которая смотрит содержание

$difference = array_unique(array_diff($listVideoNew, $listVideoOld));

if (empty($difference)){

  return view('nonewvideo'); 
} else {

foreach ($difference as $newVideo){

self::videoTrascript($newVideo);

// После того, как отработала подпрограмма - снова лезем в базу и проверяем,
// является ли конференцие обработанное видео? Если да, то вытаскиваем таймкоды.

  $checkNewConf = Video::where('ytbID', $newVideo)->first();
if ($checkNewConf['isConf'] == 'yes') {

  $content[$countConf]['contentShort'] = $checkNewConf['contentShort'];
  $content[$countConf]['title'] = $checkNewConf['title'];
  $countConf++;

}
}
}

return view('newconf', compact('countConf', 'content'));

}

// Функция, которая вытаскивает данные по видео, проверяет - конференция ли это.
// Если конференция - получает расшифровку видео, немного ее обрабатывает и передает нейросети
// для получения таймкодов. Полченный результат сохраняет в базу.

static public function videoTrascript($newVideo) {

$apiKey = config('services.apiKey');
$apiKey2 = config('services.apiKey2');
$xApiKey = config('services.xApiKey');
$model = config('services.model');
$word = "онференц";

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.supadata.ai/v1/youtube/video?id={$newVideo}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "x-api-key: {$xApiKey}"
  ],
]);

$newVideoDescript = json_decode(curl_exec($curl));
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  $badAnswer = "cURL Error #:" . $err;
  return view('badanswer', compact('badAnswer')); 
// } else {
// var_dump($newVideoDescript);
}

// Проверяем, содержит ли title слово "конференция", если да, то
// получаем транскрипцию видео

if (stripos($newVideoDescript->{'title'}, $word) !== false){
  $isConf = "yes";
// echo '<br> conference </br>';
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.supadata.ai/v1/youtube/transcript?videoId={$newVideo}&text=false&chunkSize=1000&lang=ru",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "x-api-key: {$xApiKey}"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  $badAnswer = "cURL Error #:" . $err;
  return view('badanswer', compact('badAnswer'));
// } else {
//   echo $response;
}
$transcript = json_decode($response);

    $result = json_decode($response, true);
// var_dump($result['content'][0]['text']);

// Пересобираем текст в чуть более удобный формат

$fullText='';
foreach ($result['content'] as $smallText){
  $time = round($smallText['offset']/1000);
  $hour = floor( $time / 3600 );
  $min = ( $time / 60 ) % 60;
  $sec = $time % 60;
  $addMin = (($min<10)?'0'.$min:$min);
  $addSec = (($sec<10)?'0'.$sec:$sec);
    $fullText = $fullText.' [время: '.$hour.':'.$addMin.':'.$addSec.'] '.$smallText['text'];
}

$content = "Ниже приведена транскрипция видеоконфернции, в ней присутствуют вставки вида: [время: 00:12:11] - это таймкоды, где цифры означают время от начала видео в формате
часы:минуты:секунды. Просмотри текст. Из расчета, что текст идет равномерно между таймкодами сделай оглавление из 10-12 пунктов. Каждый пункт должен начинаться с таймкода 
в формате часы:минуты:секунды и состоять из 4-5 слов, первый пункт должен называться Приветствие.В ответе выдай только оглавление с таймкодами. Вот текст: ".$fullText;

// Ваши данные
// $model = 'arcee-ai/trinity-large-preview:free'; // старая - Или другая модель[citation:3]
// $model = 'nvidia/nemotron-nano-12b-v2-vl:free'; // новая
// $model = 'openai/gpt-oss-120b:free'; // еще новая
// $model ='poolside/laguna-m.1:free';

// Передаем текст нейросети

// Тело запроса в формате JSON
$data = [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => $content]
    ],
    'temperature' => 0.7, // Опционально: контроль случайности ответа (0..2)[citation:3]
    'max_tokens' => 5000   // Опционально: ограничение длины ответа
];

// Настройка и отправка cURL-запроса
$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiKey2,
        'Content-Type: application/json',

    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Обработка ответа
if ($httpCode === 200) {
    $result = json_decode($response, true);
    // Извлечение текста ответа модели
    if (isset($result['choices'][0]['message']['content'])) {
        $answer = $result['choices'][0]['message']['content'];
        // echo "Ответ OpenRouter: \n" . htmlspecialchars($answer) . "\n";
    } else {

        $badAnswer = "Не удалось извлечь ответ из JSON.\n";
        return view('badanswer', compact('badAnswer'));       
    }
} else {
    $badAnswer = "Ошибка API (код $httpCode): " . json_decode($response, true);
    return view('badanswer', compact('badAnswer')); 

}

// Если все прошло удачно - сохраныем все результаты в базу

$video = Video::create([  
        'ytbID'=>$newVideo,
        'title'=>$newVideoDescript->{'title'},
        'descript'=>$newVideoDescript->{'description'},
        'uploadDate'=>$newVideoDescript->{'uploadDate'},
        'duration'=>$newVideoDescript->{'duration'},
        'isConf'=>$isConf,
        'contentShort'=>$answer,
        'content'=>$fullText,

    ]);

} else {

// если новое видео есть, но это не конференция - просто добавляем его ID в базу

  $video = Video::create([  
        'ytbID'=>$newVideo,
        'isConf'=>'no',

    ]);
}

return;

}

static public function otladka(){

$countConf=0;

    $checkNewConf = Video::where('id', 286)->first();


  $content[$countConf]['contentShort'] = $checkNewConf['contentShort'];
  $content[$countConf]['title'] = $checkNewConf['title'];
  $countConf++;
    $checkNewConf = Video::where('id', 285)->first();


  $content[$countConf]['contentShort'] = $checkNewConf['contentShort'];
  $content[$countConf]['title'] = $checkNewConf['title'];
  $countConf++;



return view('newconf', compact('countConf', 'content'));
}

}