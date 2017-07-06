<html>
 <head>
  <title>PHP Callback</title>
 </head>
 <body>
<?php
echo '<p>ver.0.0.5</p>';
# MQTT設定
require( "./phpMQTT.php" );

$mqtt_host = "m11.cloudmqtt.com"; # MQTT ブローカー
$mqtt_port = 14292; # MQTT ポート番号
$mqtt_clientid = "mqtt";
$mqtt_username = "pi";
$mqtt_password = "1uYBQTs9QnRf";

# LINE設定
$accessToken = '5hmeIkICCMUkTHSE/3NWz0OmphzC/r+Qt8FoFx1PWJTopxQ2EIRYZ4f9IEPOOOt9QzZPNKwRiVed5iNfawkJfuUiBjVKcysnqdtooBpnOp+9EgQsh5FlMqJq8IO1mZhL3nDHADPtN7Y5U9fawrGLEwdB04t89/1O/w1cDnyilFU=';

$jsonString = file_get_contents('php://input');
error_log($jsonString);
$jsonObj = json_decode($jsonString);

$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// 送られてきたメッセージの中身からレスポンスのタイプを選択
if ($message->{"text"} == '室温' or $message->{"text"} == '温度' or $message->{"text"} == '湿度') {
    $mqtt_topic = "homeiot/thermo";
    $mqtt_message = 'execute'; # パブリッシュするメッセージ
    //$messageData = [
    //    'type' => 'text',
    //    'text' => '計測中...'
    //];
} else {
    // それ以外は送られてきたテキストをオウム返し
    //$messageData = [
    //    'type' => 'text',
    //    'text' => $message->{"text"}
    //];
}

$response = [
    'replyToken' => $replyToken,
    'messages' => [$messageData]
];
error_log(json_encode($response));

# MQTT PUBLISH
$mqtt = new phpMQTT( $mqtt_host, $mqtt_port, $mqtt_clientid );
if( $mqtt->connect(true,NULL,$mqtt_username,$mqtt_password) ){
  $mqtt->publish( $mqtt_topic, $mqtt_message, 0 );
  $mqtt->close();
}
else
{
  error_log("Fail or time out");
    $messageData = [
        'type' => 'text',
        'text' => '計測失敗'
    ];
}

# MQTT SUBSCRIBE
$mqtt_sub = new phpMQTT( $mqtt_host, $mqtt_port, $mqtt_clientid );
if( $mqtt_sub->connect(true,NULL,$mqtt_username,$mqtt_password) ){
  $topics[$topic] = array("qos"=>0, "function"=>"procmsg");
  $mqtt_sub->subscribe($topics,0);
  $i=1;
  while($mqtt_sub->proc()){
   if($i==1000){ 
       $messageData = [
           'type' => 'text',
           'text' => '計測失敗'
       ];
       break; 
   }
   $i++;
  }
  $mqtt_sub->close();
}
else
{
  error_log("Fail or time out");
    $messageData = [
        'type' => 'text',
        'text' => '計測失敗'
    ];
}
function procmsg($mqtt_topic,$msg){
    $messageData = [
        'type' => 'text',
        'text' => str_replace("??", "%0D%0A", $msg)
    ];
    echo '<p>Msg Recieved: '.str_replace("??", "%0D%0A", $msg).'</p>';
}

# LINE 返答
$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
));
$result = curl_exec($ch);

error_log($result);
curl_close($ch);
?>
 </body>
</html>