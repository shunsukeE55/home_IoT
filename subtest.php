<html>
 <head>
  <title>PHP Subscribed test</title>
 </head>
 <body>
<?php
echo '<p>ver.0.0.1</p>';

require("./phpMQTT.php");

$host = "m11.cloudmqtt.com"; # MQTT ブローカー
$port = 14292; # MQTT ポート番号
$mqtt_clientid = 'mqtt'.date(YmdHis);
$username = "pi";
$password = "1uYBQTs9QnRf";

$topic = "homeiot/thermo";

$mqtt = new phpMQTT($host, $port, "ClientID".rand());

if(!$mqtt->connect(true,NULL,$username,$password)){
  exit(1);
}

//currently subscribed topics
$topics['topic'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){

}

$mqtt->close();
function procmsg($topic,$msg){
  echo "Msg Recieved: $msg";
}

?>
 </body>
</html>