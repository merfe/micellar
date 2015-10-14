<?php

Yii::setPathOfAlias('vendor', dirname(__FILE__) . '/../../vendor/');
$preload = array('log', 'pinba');
if (defined('YII_DEBUG') && YII_DEBUG)
    $preload[] = 'debug';

return array(
    'basePath' => dirname(__FILE__) . '/../../protected',
    'runtimePath' => dirname(__FILE__) . '/../../runtime',
    'name' => 'Heads and Hands',
    'language' => 'ru',
    
    'timeZone' => 'Europe/Moscow',

    'preload' => $preload,

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.admin.*',
    ),
    
    'components' => array(
        'debug' => array(
            'class' => 'vendor.zhuravljov.yii2-debug.Yii2Debug',
            'internalUrls' => false,
        ),
        'db' => array(
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'tablePrefix' => '',
            'schemaCachingDuration'=>3600,
            'enableParamLogging'    => defined('YII_DEBUG') && YII_DEBUG ? true : 0,
            'enableProfiling'       => defined('YII_DEBUG') && YII_DEBUG ? true : 0,
        ),
        'pinba' => array(
            'class' => 'ext.yii-pinba.Pinba',
            'on' => false,
            'fixScriptName' => true,
            'scriptName' => null,
            'hostName' => null,
            'serverName' => null,
            'schema' => null
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                'error_logger' => array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, debug',
                    'categories' => 'exception.*, system.*, php',
                    'logFile' => 'application.log',
                    'maxLogFiles' => 10,
                    'maxFileSize' => 155360,
                ),
            ),
        ),
        'mailer' => array(
            'class' => 'Mailer',
        ),
    ),
);
