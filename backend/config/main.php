<?php
use dektrium\user\models\User;
use dektrium\user\controllers\RegistrationController;
use dektrium\user\controllers\SecurityController;
use backend\models\Bonus;
use yii\helpers\Url;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        // 'log',
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['admin'],
            'enableGeneratingPassword' => false,
        ],  
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
            'as access' => [
                'class' => yii2mod\rbac\filters\AccessControl::class
            ],
        ],
    ],

    'components' => [
        'request' => [
            'baseUrl' => '/admin',
            'class' => 'klisl\languages\Request',
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'GwW_tXRK5gxdntqIL4sFWnpm_pC9nxip',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => true
        ],
        
        /*
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        */
        'session' => [
            'name' => 'advanced-backend',
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

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // 'languages' => 'languages/default/index',
                // '' => 'site/index',
                // 'rbac' => 'site/rbac',
                // '<action>' => 'site/<action>',
                // 'questions' => 'questions/index'
            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views/yii2-app',
                    '@dektrium/user/views' => '@app/views/user',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        
        
        /*
        'as AccessBehavior' => [
            'class' => AccessBehavior::className(),
            // 'redirect_url' => '/admin/login',
            // 'login_url' => Yii::$app->user->loginUrl
        ],
        */
    ],
    /*
    'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class
    ],
    */
    'params' => $params,
];
