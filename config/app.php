<?php

return [
    'db' => require_once(__DIR__ . '/db.php'),
    'params' => [
        'debug' => true,
        'name' => 'AuthTestApp1',
        'defaultcontroller' => 'Site',
        'defaultaction' => 'Index',
    ],
];
