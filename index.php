<?php
$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'elrTlEnZYv9BqQTLFDG+PsaT3VdBjCzs9/nhqkNNGFaHQDveBfVE2xL0ddW+PGl1sK/tCikVIoIq8ZcPaPIkgNIWdRO/QeEEENO0+UzmaKZrcZbCc9DDQ8cyoNuVN3Z0R4ewRaMjlDmMD3rePRDxnQdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '47bc90719fa07a6a119bea4d462a29f6';
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

// coin API
$getData = json_decode(file_get_contents('https://api.coinmarketcap.com/v2/ticker/?limit=10'), TRUE);
if(!empty($getData['data'])){
    $priceList =[];
    foreach($getData['data'] as $val){
    $priceList[$val['symbol']] = 'Current Price: ' . $val['quotes']['USD']['price'] . ' USD '; 
    }
}

// answer
if (!is_null($request_array['events'])) {
        foreach ($events['events'] as $event) {
            if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
    
    $data = [
        'to' => $event['source']['userId'],
        'messages' =>[ 'type' => 'text',
                'text' => json_encode($priceList) ]
    ];
    $post_body = json_encode($data);
    $send_result = send_reply_message($API_URL.'/push', $POST_HEADER, $post_body);
            }
        }
}
echo "OK";
function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>