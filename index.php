<?php

$updatedList = [];
$apiKey = "AIzaSyCJZbA8xGOeS161rMKA6Lei9AoiS8YTFgc";

$listApi = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=5.1198992,-1.2973537&radius=3500&type=hospital&keyword=hospital&key=".$apiKey;


function getData($url) {
    $curl = curl_init();
    $opt = [
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['content-type: application/json']
    ];
    curl_setopt_array($curl, $opt);
    $results = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($results, true);
}

$list = getData($listApi);

foreach ($list['results'] as $target) {
    $detailApi = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$target['place_id']}&fields=name,formatted_phone_number&key=".$apiKey;

    $innerList = getData($detailApi);

    array_push(
        $updatedList, [
            "name" => $target['name'],
            "phone" => isset($innerList['result']['formatted_phone_number']) ? $innerList['result']['formatted_phone_number'] : "None",
            "location" => $target['vicinity']
        ]
    );
}

echo json_encode($updatedList);





?>

