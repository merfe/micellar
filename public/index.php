<?php

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
