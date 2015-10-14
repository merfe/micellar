<?php

return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'Micellar',
    'sourceLanguage' => 'en',
    'language' => 'ru',

    'import' => array(
        'application.models.admin.*',
    ),
    
    'defaultController' => 'home',
    
    'modules' => array(
        'ycm' => array(
            'registerModels' => array(
                'Основная структура'=> array(
                    'application.models.admin.*',
                )
            ),
            'uploadCreate' => true,
            'redactorUpload' => true,
        ),
    ),

    'components' => array(
        'ih'=>array(
            'class'=>'CImageHandler',
        ),
        'session' => array (
            'class' => 'CHttpSession',
            'autoStart' => true,
            'cookieMode' => 'only',
            'timeout' => 24 * 60 * 60,
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => ( defined('YII_DEBUG') && YII_DEBUG),
            'rules' => array(
                'gii' => 'gii',
                'ycm' => 'ycm',

                '<controller:\w+>/<id:\d+>' 	=> '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' 	=> '<controller>/<action>',
                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
    ),

    'params' => array(
    )
);
