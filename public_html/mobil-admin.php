<?php

require('common.inc');

$config = require(__DIR__ . '/../config/admin.php');

(new yii\web\Application($config))->run();
