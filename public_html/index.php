<?php

require('common.inc');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
