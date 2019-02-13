<?php

use yii\web\UrlNormalizer;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'homeUrl' => '/',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'extensions' => YII_DEBUG ? [
                        '\Twig_Extension_Debug',
                    ] : [],
                    'globals' => [
                        'url' => '\yii\helpers\Url',
                        'html' => '\yii\helpers\Html',
                        'Templates' => ['class' => '\common\models\Templates'],
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => UrlNormalizer::ACTION_REDIRECT_PERMANENT, // используем постоянный редирект
            ],
            'rules' => [
                [
                    'pattern' => '/categories/',
                    'route' => 'site/categories',
                ],
                [
                    'pattern' => '/<cat:[\w-]+>/add/',
                    'route' => 'site/add'
                ],
                [
                    'pattern' => '/add/',
                    'route' => 'site/add'
                ],
                [
                    'pattern' => '/<cat:[\w-]+>/',
                    'route' => 'site/index',
                ],
            ],
        ],
    ],
    'params' => $params,

];
