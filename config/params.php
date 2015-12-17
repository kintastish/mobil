<?php

$contentHandlersConfig = require(__DIR__ . '/handlers.php');
return [
    // 'adminEmail' => 'admin@example.com',
    'contentHandlers' => $contentHandlersConfig //['cat', 'page', 'file', 'gallery'],
];
