<?php
use yii\di\ServiceLocator;
use kartik\datecontrol\Module;
use dektrium\user\models\User;
use dektrium\user\controllers\RegistrationController;
use dvizh\shop\models\Category;
use common\models\Bonus;
// use common\models\Settings;

$config = [
    'id' => 'appId',
    'name' => 'appName',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'sourceLanguage' => 'ru',
    'language' => 'ru',
    'bootstrap' => [
        'dvizh\order\Bootstrap',
        'config',
    ],
    
    // 'on beforeRequest' => function () {
        // Yii::$app->mailer->transport->host = Yii::$app->config->get('host', 'mailer');
        // Yii::$app->mailer->transport->username = Yii::$app->config->get('username', 'mailer');
        // Yii::$app->mailer->transport->password = Yii::$app->config->get('password', 'mailer');
        // Yii::$app->mailer->transport->port = Yii::$app->config->get('port', 'mailer');

        // print_r(Yii::$app->config->get('actionsTypes'));
        // $settings = Yii::$app->settings::findAll([
            // 'active' => 1
        // ]);
        // if ($settings) {
            // foreach ($settings as $setting) {
                // $settingName = $setting->formattedName;
                // $settingValue = $setting->formattedValue;
                // if ($setting->category) {
                    // Yii::$app->params[$setting->category][$settingName] = $settingValue;
                // } else {
                    // Yii::$app->params[$settingName] = $settingValue;
                // }
                // if (property_exists(Yii::$app, $settingName)) {
                    // Yii::$app->{$settingName} = $settingValue;
                // }
            // }
        // }
// echo \yii\helpers\VarDumper::dump(Yii::$app->params, 99, true);
    // },
    
    'modules' => [
        // 'settings' => [
            // 'class' => 'yii2mod\settings\Module',
        // ],
        'gallery' => [
            'class' => 'agapofff\gallery\Module',
            'imagesStorePath' => dirname(dirname(__DIR__)).'/frontend/web/images/store',
            'imagesCachePath' => dirname(dirname(__DIR__)).'/frontend/web/images/cache',
            'graphicsLibrary' => 'GD',
            'placeHolderPath' => dirname(dirname(__DIR__)).'/frontend/web/images/placeholder.png',
            'adminRoles' => [
                'admin',
                'manager',
            ],
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['admin'],
            'enableGeneratingPassword' => false,
            'controllerMap' => [
                'registration' => [
                    'class' => RegistrationController::className(),
                    'on ' . RegistrationController::EVENT_AFTER_CONFIRM => function ($e) {
                        if (Yii::$app->params['bonus']['referal'] && Yii::$app->user->identity->referal) {
                            if ($user = User::findOne(base64_decode(Yii::$app->user->identity->referal))) {
                                $addBonus = new Bonus();
                                $addBonus->attributes = [
                                    'active' => 1,
                                    'user_id' => $user->id,
                                    'type' => 1,
                                    'amount' => Yii::$app->params['bonus']['referal'],
                                    'reason' => 0,
                                    'description' => (string)Yii::$app->user->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                                $addBonus->save();
                            }
                        }
                    },
                ],
            ],
        ],        
        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module'
        ],
        'shop' => [
            'class' => 'dvizh\shop\Module',
            'adminRoles' => [
                'administrator',
                'superadmin',
                'admin',
                'manager'
            ],
            'defaultPriceTypeId' => 1,
        ],
        'filter' => [
            'class' => 'dvizh\filter\Module',
            'adminRoles' => [
                'administrator',
                'superadmin',
                'admin',
                'manager'
            ],
            'relationFieldName' => 'category_id',
            'relationFieldValues' =>
                function() {
                    return Category::buildTextTree();
                },
        ],
        'field' => [
            'class' => 'dvizh\field\Module',
            'relationModels' => [
                'dvizh\shop\models\Product' => 'Продукты',
                'dvizh\shop\models\Category' => 'Категории',
                'dvizh\shop\models\Producer' => 'Производители',
            ],
            'adminRoles' => [
                'administrator',
                'superadmin',
                'admin',
                'manager'
            ],
        ],
        'relations' => [
            'class' => 'dvizh\relations\Module',
            'fields' => ['code'],
        ],
        'cart' => [
            'class' => 'dvizh\cart\Module',
        ],
        'tree' => [
            'class' => 'dvizh\tree\Module',
            'adminRoles' => ['@'],
        ],
        'order' => [
            'class' => 'dvizh\order\Module',
            'layoutPath' => 'frontend\views\layouts',
            'successUrl' => '/checkout/pay',
            'adminNotificationEmail' => Yii::$app->params['adminEmail'],
            'as order_filling' => 'dvizh\order\behaviors\OrderFilling',
            'showCountColumn' => false,
            'orderStatuses' => [
                'new' => 'Новый',
                'approve' => 'Подтвержден',
                'paid' => 'Оплачен',
                'cancel' => 'Отменен',
                'process' => 'В обработке', 
                'done' => 'Выполнен',
            ],
            'superadminRole' => 'admin',
            'orderColumns' => [
                'client_name',
                'phone',
                'email',
                'shipping_type_id',
            ],
            'robotEmail' => Yii::$app->params['senderEmail'],
            'robotName' => Yii::$app->params['senderName'],
            'adminNotificationEmail' => true,
            'clientEmailNotification' => true,
        ],
        'promocode' => [
            'class' => 'dvizh\promocode\Module',
            'informer' => 'dvizh\cart\widgets\CartInformer', // namespace to custom cartInformer widget
            'informerSettings' => [], //settings for custom cartInformer widget
            'clientsModel' => 'dektrium\user\models\User', //Модель пользователей
            //Указываем модели, к которым будем привязывать промокод
            'targetModelList' => [
                'Категории' => [
                    'model' => 'dvizh\shop\models\Category',
                    'searchModel' => 'dvizh\shop\models\category\CategorySearch'
                ],
                'Продукты' => [
                    'model' => 'dvizh\shop\models\Product',
                    'searchModel' => 'dvizh\shop\models\product\ProductSearch'
                ],            
            ],
        ],
       'treemenu' =>  [
            'class' => 'sitronik\treemenu\Module',
        ]
    ],
    'components' => [
        'config' => [
            'class' => 'common\components\Config', // Settings::className()
        ],
        // 'settings' => [
            // 'class' => 'yii2mod\settings\components\Settings',
        // ],
        /*
        'request' => [
            'baseUrl' => '',
            'class' => 'klisl\languages\Request',
        ],
        */

        'cache' => [
            // 'class' => 'yii\caching\FileCache',
            'class' => 'yii\caching\DummyCache',
        ],

        // интернационализация через базу данных
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true,
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    'enableCaching' => false,
                    // 'cachingDuration' => 3600,
                    'sourceLanguage' => 'ru'
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true,
                    'sourceMessageTable' => '{{%source_message}}',
                    'messageTable' => '{{%message}}',
                    'enableCaching' => false,
                    // 'cachingDuration' => 3600,
                    'sourceLanguage' => 'ru'
                ],
            ],
        ],
        
        'mailer' => function () {
            return Yii::createObject([
                'class' => 'yii\swiftmailer\Mailer',
                'useFileTransport' => false,
                'viewPath' => '@common/mail',
                'htmlLayout' => 'layouts/html',
                'textLayout' => 'layouts/text',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => Yii::$app->config->get('host', 'mailer'), // 'smtp.mail.ru',
                    'username' => Yii::$app->config->get('username', 'mailer'),
                    'password' => Yii::$app->config->get('password', 'mailer'),
                    'port' => Yii::$app->config->get('port', 'mailer'),
                    'encryption' => 'ssl',
                    'streamOptions' => [ 
                        'ssl' => [ 
                            'allow_self_signed' => true, 
                            'verify_peer' => false, 
                            'verify_peer_name' => false, 
                        ], 
                    ],
                ],
            ]);
        },
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'class' => 'klisl\languages\UrlManager',
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
                'normalizeTrailingSlash' => true,
                'collapseSlashes' => true,
            ],
            // 'rules' => [
                // 'languages' => 'languages/default/index',
                // '/' => 'site/index',
                // '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
                // '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>',
                // 'admin/user/admin' => 'user/admin',
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                // '<controller:\w+>' => '<controller>/index',
            // ]
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'defaultRoles' => ['guest', 'user'],
        ],
        
		'formatter' => [
			'dateFormat' => 'dd.MM.yyyy',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
            'timeFormat' => 'HH:mm',
			// 'decimalSeparator' => ',',
			// 'thousandSeparator' => ' ',
			// 'currencyCode' => 'RUB',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
                NumberFormatter::MAX_FRACTION_DIGITS => 0,
            ],
            // 'numberFormatterSymbols' => [
                // NumberFormatter::CURRENCY_SYMBOL => '&#8364;',
            // ],
            'language' => 'ru'
		],
        
        'fileStorage' => [
            'class' => '\trntv\filekit\Storage',
            // 'baseUrl' => '@storageUrl/source',
            'baseUrl' => Yii::$app->urlManager->hostInfo . '/images/source',
            'filesystem'=> function() {
                $adapter = new \League\Flysystem\Adapter\Local(dirname(dirname(__DIR__)).'/frontend/web/images/source');
                return new League\Flysystem\Filesystem($adapter);
            },
        ],
        
        'cart' => [
            'class' => 'dvizh\cart\Cart',
            'currency' => 'р.',
            'currencyPosition' => 'after',
            'priceFormat' => [2, '.', ''],
        ],
        
        'treeSettings' => [
            'class' => 'dvizh\tree\TreeSettings',
            'models' => [
                '\dvizh\shop\models\Category' => [
                    'orderField' => 'sort asc',
                ],
            ],
        ],
        
    ],
];


return $config;