<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
// include Yii bootstrap file
require_once(dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php');

// load base config
$configFilename = dirname(__FILE__).'/config/common.php';
$config = require_once($configFilename);

// load overrides (if any)
foreach (array('console', 'local') as $override)
{
    $overrideFilename = dirname(__FILE__).'/config/' . $override . '.php';
    if (is_file($overrideFilename))
    {
        $configOverride = require_once($overrideFilename);
        $config = CMap::mergeArray($config, $configOverride);
    }
}

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

$app = Yii::createConsoleApplication($config);
$app->commandRunner->addCommands(YII_PATH . '/cli/commands');
$env = @getenv('YII_CONSOLE_COMMANDS');

if (!empty($env)) {
    $app->commandRunner->addCommands($env);
}

$app->run();
