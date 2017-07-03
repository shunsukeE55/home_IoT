<html>
 <head>
  <title>PHP Callback</title>
 </head>
 <body>
<?php
# MQTT設定
require( "phpMQTT.php" );

$mqtt_host = "m11.cloudmqtt.com"; # MQTT ブローカー
$mqtt_port = 14292; # MQTT ポート番号
$mqtt_clientid = "heroku_php"
$mqtt_username = "pi";
$mqtt_password = "1uYBQTs9QnRf";

$mqtt_topic = "homeiot/thermo";
$mqtt_message = "thermomerter"; # パブリッシュするメッセージ

# MQTT PUBLISH
$mqtt = new phpMQTT( $mqtt_host, $mqtt_port, $mqtt_clientid );
if( $mqtt->connect(true,NULL,$mqtt_username,$mqtt_password) ){
  $mqtt->publish( $mqtt_topic, $mqtt_message, 0 );
  $mqtt->close();
}else{
  echo '<p>error</p>';
}
?>
 </body>
</html>