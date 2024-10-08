<?php
function getFormattedDateTime($date) {
    return $date->format('Ymd\THis');
}

function getUpdatedMPDUrl($baseMpdUrl, $startOffset, $endOffset) {
    $timezone = new DateTimeZone('Asia/Kolkata');
    $now = new DateTime('now', $timezone);
    $beginTime = clone $now;
    $beginTime->modify("-$startOffset minutes");
    $endTime = clone $now;
    $endTime->modify("+$endOffset minutes");

    $beginStr = getFormattedDateTime($beginTime);
    $endStr = getFormattedDateTime($endTime);

    return "{$baseMpdUrl}?begin={$beginStr}&end={$endStr}";
}

function fetchRemoteData($apiUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return ['error' => "Curl error: $error_msg"];
    }
    curl_close($ch);

    return json_decode($response, true);
}

function fetchChannelDetails($id, $channels) {
    foreach ($channels['data']['channels'] as $channel) {
        if ($channel['id'] == $id) {
            return $channel;
        }
    }
    return null;
}

$start_time = microtime(true);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $channels = json_decode(file_get_contents('channel.json'), true);

    // Fetch channel details
    $channel = fetchChannelDetails($id, $channels);

    if ($channel) {
        $channelUrl = getUpdatedMPDUrl($channel['manifest_url'], 8640, 2880);

        $apiUrl = "https://sardariptv.serv00.net/tx.php?id={$id}";

        $apiData = fetchRemoteData($apiUrl);

        // Now fetch keyId and key from $apiData, not $apiUrl
        $keyId = $apiData['data']['keyId'] ?? '';
        $key = $apiData['data']['key'] ?? '';

        if (!empty($keyId) && !empty($key)) {
            $result = [
                "title" => "TATA-PLAY | GET-API",
                "owner" => "@sardariptv",
                "developers" => "sardariptv",
                "channel" => "https://t.me/sardariptv",
                "data" => [
                    "channel_id" => $channel['id'],
                    "channel_name" => $channel['name'],
                    "channel_url" => $channelUrl,
                    "keyId" => $keyId,
                    "key" => $key
                ]
            ];

            header('Content-Type: application/json');
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["error" => "Key data not found for the specified channel."], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(["error" => "Channel not found."], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "No ID specified."], JSON_PRETTY_PRINT);
}

$end_time = microtime(true);
$execution_time = $end_time - $start_time;
error_log("Execution time: " . $execution_time . " seconds");
?>
