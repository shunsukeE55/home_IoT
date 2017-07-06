<?php

require("./phpMQTT.php");

$mqtt_host = "m11.cloudmqtt.com"; # MQTT ブローカー
$mqtt_port = 14292; # MQTT ポート番号
$mqtt_clientid = 'mqtt'.date(YmdHis);
$mqtt_username = "pi";
$mqtt_password = "1uYBQTs9QnRf";

$mqtt_topic = "homeiot/thermo";

$mqtt = new phpMQTT( $mqtt_host, $mqtt_port, $mqtt_clientid );

if(!$mqtt->connect()){
	exit(1);
}

$topics[$mqtt_topic] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){
		
}


$mqtt->close();

function procmsg($mqtt_topic,$msg){
		echo "Msg Recieved: ".date("r")."\nTopic:{$mqtt_topic}\n$msg\n";
}
	


?>
