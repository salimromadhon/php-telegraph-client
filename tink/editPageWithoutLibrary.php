<?php

use GuzzleHttp\Client as Http;

$token = 'ce4c2c49e7205b2e8418848054a422181f84be1b68d660f596580a4eaa28';

$http = new Http(['base_uri' => 'https://api.telegra.ph/']);

// Data to be used.
$title = 'This is the new title.';

$account = $http->request('POST', '/getAccountInfo', [
    'json' => [
        'access_token' => $token,
    ],
])->getBody()->getContents();
$account = json_decode($account, true);

if (is_array($account) && isset($account['result'])) {
    $page = $http->request('POST', '/getPageList', [
        'json' => [
            'access_token' => $token,
        ]
    ])->getBody()->getContents();
    $page = json_decode($page, true);

    if (is_array($page) && isset($page['result']['pages'][0])) {
        $page = $page['result']['pages'][0];

        $originalPage = $http->request('POST', '/getPage/' . $page['path'], [
            'json' => [
                'return_content' => true,
            ],
        ])->getBody()->getContents();
        $originalPage = json_decode($originalPage, true);
        $originalPage = $originalPage['result'];

        $page = $http->request('POST', '/editPage/' . $page['path'], [
            'json' => [
                'access_token' => $token,
                'title' => $title,
                'content' => $originalPage['content'],
            ]
        ])->getBody()->getContents();
        $page = json_decode($page, true);

        if (is_array($page) && isset($page['result'])) {
            $page = $page['result'];
        }
    }
}

if (isset($page)) {
    // Done.
    var_dump($page);
}
