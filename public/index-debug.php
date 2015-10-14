<?php

defined('YII_DEBUG') or define('YII_DEBUG',true);

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file.');
}

// include Yii bootstrap file
require_once(dirname(__FILE__) . '/../vendor/yiisoft/yii/framework/yii.php');

// load base config
$configFilename = dirname(__FILE__).'/../protected/config/common.php';
$config = require_once($configFilename);

// load overrides (if any)
foreach (array('web', 'local') as $override)
{
    $overrideFilename = dirname(__FILE__).'/../protected/config/' . $override . '.php';
    if (is_file($overrideFilename))
    {
        $configOverride = require_once($overrideFilename);
        $config = CMap::mergeArray($config, $configOverride);
    }
}

require dirname(__FILE__) . '/../vendor/autoload.php';

// create a Web application instance and run
Yii::createWebApplication($config)->run();
