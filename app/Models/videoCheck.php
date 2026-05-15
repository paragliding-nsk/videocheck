<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;

use App\Models\Medcard;

class Video extends Model
{
    use SoftDeletes;

    protected $table = 'videos';
    protected $guarded = [];
    
  
    static public function checkNewVideo($xApiKey){
    $videos = Video::all();

    $k=0;
    foreach ($videos as $video) {
        $listVideoOld[$k] = $video['ytbID'];
        $k++;
        }
    

    $curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.supadata.ai/v1/youtube/channel?id=UCqTLndb64WSo76e8MCCUkvQ",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "x-api-key: {$xApiKey}"
  ],
]);

$responseChannel = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $responseChannel;
}


$numb = json_decode($responseChannel)->{'videoCount'};
echo $numb;
// $numb = 264;
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
  echo "cURL Error #:" . $err;
} else {
  echo $responseVideoCount;
}

$mass = json_decode($responseVideoCount);
$i=0;
foreach ($mass->{'videoIds'} as $key => $mass1) {
    // echo "Ключ: $key => Значение старое: $mass1<br/>";
    $listVideoNew[$i]=$mass1;
    $i++;

}

$difference = array_diff($listVideoNew, $listVideoOld);

if (empty($difference)){
  echo "новых видео нет";
} else {
echo "Элементы в A, которых нет в B:\n";
// print_r($difference);
foreach ($difference as $newVideo){
echo $newVideo . "<br>";
videoTrascript($newVideo);

}
}
return redirect()->route('patients.show', $patient->id);

}


static public function videoTrascript($newVideo) {

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

$newVideoDescript[$n] = json_decode(curl_exec($curl));
$err = curl_error($curl);

curl_close($curl);

// $newVideoDescript = json_decode('{"id":"7vAXIl6erBU","description":"Хотите изменить к лучшему Россию и весь мир эффективными действиями? Вы можете присоединиться и поддержать нашу работу,
//  вся контактная информация по ссылке https://vk.cc/cpIHV0\n\n- Объединяемся, используем Киберплан https://universo.pro\n\n- Группа в VK.COM https://vk.com/diverslaboristo\n- 
//  Омский консенсус https://vk.com/omsk_kons\n- Universo Grandaringo https://vk.com/grandaringo\n- Universo Platformo https://vk.com/universo_pro\n\nТайм-коды:",
//  "title":"Беспилотный транспорт на войне и мире (Конференция 248)","channel":{"id":"UCqTLndb64WSo76e8MCCUkvQ","name":"Генеральный разнорабочий"},"tags":[],
//  "thumbnail":"https://i.ytimg.com/vi/7vAXIl6erBU/maxresdefault.jpg","uploadDate":"2026-01-16T00:00:00.000Z","viewCount":362,"likeCount":4,"isLive":false,
//  "duration":2856,"transcriptLanguages":["ru"]}');

print "<br>".$newVideoDescript[$n]->{'title'}."<br>";

if ($err) {
  echo "cURL Error #:" . $err;
} else {
var_dump($newVideoDescript);
}
if (stripos($newVideoDescript[$n]->{'title'}, $word) !== false){
// if (stripos($titleVideo, $word) !== false){
  $isConf = "yes";
  echo "<br> конференция! <br>";

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
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
$transcript = json_decode($response);

    $result = json_decode($response, true);
// var_dump($result['content'][0]['text']);
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

$model = 'mistral-small-latest'; // Или другая модель
$endpoint = 'https://api.mistral.ai/v1/chat/completions';

$content = "Ниже приведена транскрипция видеоконфернции, она состоит из фрагментов, разделенных фигурными скобками. В конце каждого фрагмента есть пометки offset - это таймкод
 начала фрагмента в миллисекундах и duration - это продолжительность фрагмента в миллисекундах. Собери фрагменты в один текст, пересчитай миллисекунды в формат часы:минуты:секунды
и сделай оглавление из 12-15 пунктов. Каждый пункт должен начинаться с таймкода в формате часы:минуты:секунды и состоять из 6-7 слов, первый пункт должен называться Приветствие.
В ответе выдай только оглавление с таймкодами, никаких служебных символов (зведочек, скобок) использовать не нужно. Вот текст: ".$response;

// 2. Формируем тело запроса в формате JSON
$data = [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => $content]
    ],
    'temperature' => 0.7,
    'max_tokens' => 1000
];

// 3. Создаем и настраиваем cURL-запрос
$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true, // Возвращать ответ в строку
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

// 4. Отправляем запрос и получаем ответ
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// 5. Проверяем наличие ошибок cURL
if (curl_errno($ch)) {
    die('Ошибка cURL: ' . curl_error($ch));
}

curl_close($ch);

// 6. Обрабатываем ответ
if ($httpCode !== 200) {
    die("HTTP ошибка {$httpCode}: {$response}");
}

// 7. Декодируем JSON и извлекаем ответ модели
$result = json_decode($response, true);

if (isset($result['choices'][0]['message']['content'])) {
    $answer = $result['choices'][0]['message']['content'];
    echo "Ответ Mistral: \n" . $answer . "\n";
} else {
    echo "Не удалось извлечь ответ. Полный ответ API: \n";
    print_r($result);
}

// 8. Информация об использовании токенов (опционально)
if (isset($result['usage'])) {
    echo "\nИспользовано токенов: " . $result['usage']['total_tokens'] . "\n";
}
}
}    
    

    static public function index() {
        $patients = Patient::all();
        return view('patient.index', compact('patients'));
    }

    static public function alreadyPatient($patient) {
        $medcards = Medcard::where('patient_id', $patient['id'])->get();
        return view('patient.alreadyPat', compact('patient', 'medcards'));
    }

    static public function showPatient($patient) {
        $medcards = Medcard::where('patient_id', $patient['id'])->get();
        return view('patient.show', compact('patient', 'medcards'));
    }

    static public function searchPatient($searchPatData) {    
        $patients = Patient::where('lastName', 'like', $searchPatData['lastName'].'%' )->orderBy('lastname', 'asc')->orderBy('firstname', 'asc')->orderBy('midname', 'asc')->get();
    
        if(!empty($patients[0])){
            foreach($patients as $patient) {
                $patient['mounthOfBirth'] = self::$mounses[intval($patient['mounthOfBirth'])-1];
            }
        return view('patient.patientsList', compact('patients'));
        } else {
            return view('patient.emptyList');
        }
    }

    static public function editPatient($patient) {
        $medcards = Medcard::where('patient_id', $patient['id'])->get();
        return view('patient.edit', compact('patient', 'medcards'));

    }

    static public function preDeletePatient($patient) {
        $medcards = Medcard::where('patient_id', $patient['id'])->get();
        return view('patient.preDelete', compact('patient', 'medcards'));

    }

    public static function deletePatient($patient) {
        $patient->delete();
        return redirect()->route('start.index');
    }

    static public function updatePatient($patient) {
        $newPatData = request()->validate([
            'lastName'=>'',
            'firstName'=>'',
            'midName'=>'',
            'phoneNumber'=>'',
            'dayOfBirth'=>'',
            'mounthOfBirth'=>'',
            'yearOfBirth'=>'',
        ]);       
        $patient ->update($newPatData);

        return redirect()->route('patients.show', $patient->id);
    }

    static public function searchPatient2() {
        $patients=Patient::orderBy('lastName')->get();
         return view('search2', compact('patients'));
        // $oldPatients = DB::connection('old_patients')->table('patients')->get();
        // $name=$oldPatients[0]->firstname;
        // var_dump($oldPatients[0]);
    }

}