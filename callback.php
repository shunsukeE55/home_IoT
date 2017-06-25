<?php

$accessToken = '5hmeIkICCMUkTHSE/3NWz0OmphzC/r+Qt8FoFx1PWJTopxQ2EIRYZ4f9IEPOOOt9QzZPNKwRiVed5iNfawkJfuUiBjVKcysnqdtooBpnOp+9EgQsh5FlMqJq8IO1mZhL3nDHADPtN7Y5U9fawrGLEwdB04t89/1O/w1cDnyilFU=';

$jsonString = file_get_contents('php://input');
error_log($jsonString);
$jsonObj = json_decode($jsonString);

$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// �����Ă������b�Z�[�W�̒��g���烌�X�|���X�̃^�C�v��I��
if ($message->{"text"} == '����' or $message->{"text"} == '���x' or $message->{"text"} == '���x') {
    $messageData = [
        'type' => 'text',
        'text' => $message->{"text"}
    ];
} else {
    // ����ȊO�͑����Ă����e�L�X�g���I�E���Ԃ�
    $messageData = [
        'type' => 'text',
        'text' => $message->{"text"}
    ];
}

$response = [
    'replyToken' => $replyToken,
    'messages' => [$messageData]
];
error_log(json_encode($response));

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