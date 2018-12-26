<?php
return [
    'api'      => env('MITAKE_API', 'http://smexpress.mitake.com.tw:9600/SmSendGet.asp'),
    'username' => env('MITAKE_USERNAME', ''),
    'password' => env('MITAKE_PASSWORD', ''),
    'encoding' => env('MITAKE_ENCODING', 'big5'),
];
