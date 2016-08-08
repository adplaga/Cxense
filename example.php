<?php
include './vendor/autoload.php';
include './src/Cxense.php';
$username = 'USERNAME';
$apiKey = 'API KEY';
$siteId = 'SITE ID';
$requestPath = 'CXENSE API END POINT';

$request = new Cxense($username, $apiKey, $siteId, $requestPath);


//dmp/traffic/event
$options3 = [
    'start' => strtotime('-1 hour'),
    'siteIds' => [
        $siteId
    ],
    'fields' => [
        "eventId",
        "userId",
        "url",
        "host",
        "query",
        "referrerUrl",
        "browser",
        "browserVersion",
        "browserLanguage",
        "browserTimezone",
        "customParameters",
        "externalUserIds",
        "country",
        "region",
        "city",
        "metrocode",
        "company",
        "connectionSpeed",
        "exitLinkUrl",

    ],
    "externalUserIdTypes" => ["iim"]
];


$results = $request->getData($options3);

print_r($results['events']);

$events = array();


$i = 0;
foreach($results['events'] as $data) {
    $events[$i]['time'] = $data['time'];
    $events[$i]['eventId'] = $data['eventId'];
    $events[$i]['externalUserId'] = (!empty($data['externalUserIds'][0])) ? $data['externalUserIds'][0]['id'] : 'null';
    $events[$i]['browser'] = $data['browser'];
    $events[$i]['browserVersion'] = $data['browserVersion'];
    $events[$i]['url'] = $data['url'];
    $events[$i]['referrerUrl'] = (!empty($data['referrerUrl'])) ? $data['referrerUrl'] : 'null' ;
    $events[$i]['city'] = (!empty($data['city'])) ? $data['city'] : 'null' ;
    $events[$i]['country'] = (!empty($data['country'])) ? $data['country'] : 'null' ;
    $events[$i]['region'] = (!empty($data['region'])) ? $data['region'] : 'null' ;
    $events[$i]['metrocode'] = (!empty($data['metrocode'])) ? $data['metrocode'] : 'null' ;
    $events[$i]['company'] = (!empty($data['company'])) ? $data['company'] : 'null' ;
    $i++;
}

//print_r($events);

$fp = fopen('cxense_events.csv', 'w');
$header = array('time', 'eventId', 'externalUserId', 'browser', 'browserVersion', 'url', 'referrerUrl', 'city', 'country', 'region', 'metrocode', 'company' );
$show_header = true;

foreach($events as $key=>$line) {
    if($show_header) {
        fputcsv($fp, $header);
    }
    fputcsv($fp, $line);
    $header = false;
}

fclose($fp);

