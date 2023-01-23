<?php
use yii\db\Query;
use yii\web\Cookie;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use common\models\Languages;
use common\models\Stores;
use dvizh\filter\models\Product;
use dvizh\filter\models\FilterVariant;
use dektrium\user\models\User;
use sitronik\treemenu\models\TreeMenu;
use dektrium\user\controllers\RegistrationController;
use dektrium\user\controllers\SecurityController;
use backend\models\Bonus;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'assetsAutoCompress',
        // 'log',
        'languagesDispatcher',
        'devicedetect',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'sourceLanguage' => 'ru',
    'language' => 'ru',
    'on beforeRequest' => function () {
        // слеши
        $pathInfo = Yii::$app->request->pathInfo;
        $query = Yii::$app->request->queryString;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $url = '/' . substr($pathInfo, 0, -1);
            if ($query) {
                $url .= '?' . $query;
            }
            Yii::$app->response->redirect($url, 301);
            Yii::$app->end();
        }
        
        $langLinks = [];
        $pathParts = explode('/', $pathInfo);
        array_shift($pathParts);
        $pathNoLang = join('/', $pathParts);
        foreach (Yii::$app->urlManager->languages as $lang) {
            $langLinks[] = $lang . '/' . $pathNoLang . ($query ? '?' . $query : '');
        }
        Yii::$app->params['langLinks'] = $langLinks;

    },
    'on beforeAction' => function () {
        Yii::$app->params['languages'] = array_combine(Yii::$app->urlManager->languages, Yii::$app->urlManager->languages);
        
        $redirect = false;
        
        $store_id = Yii::$app->params['default_store_id'];
        
        // кладём дефолтный тип магазина в куки
        if (!Yii::$app->request->cookies->has('store')) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'store_id',
                'value' => $store_id,
            ]));
            // $redirect = true;
        }
        
        if ($redirect) {
            Yii::$app->response->redirect(Yii::$app->request->absoluteUrl, 301)->send();
            Yii::$app->end();
        }

        Yii::$app->params['store_id'] = Yii::$app->request->cookies->getValue('store_id', $store_id);
        
        // валюта
        $store = Stores::findOne(Yii::$app->params['store_id'])->currency;
        if ($store) {
            Yii::$app->params['currency'] = $store->country->currency;
            Yii::$app->params['locale'] = $store->country->iso;
        }
        
        // menu
        $menu = TreeMenu::find()
            ->where([
                'active' => 1
            ])
            ->asArray()
            ->all();
        if ($menu) {
            foreach ($menu as $k => $menuItem) {
                $menu[$k]['current'] = $menuItem['url'] == '/'.Yii::$app->request->pathInfo ? true : false;
            }
        }
        Yii::$app->params['menu'] = $menu;
// echo Yii::$app->request->pathInfo; exit;
    },
    /*
    'on beforeAction' => function () {
        // кладём валюту магазина в параметры
        $currency = Stores::getDb()->cache(
            fn() => Stores::findOne([
                'lang' => explode('/', Yii::$app->request->url)[1],
                'type' => Yii::$app->params['store_type']
            ])->currency
        );
        
        if ($currency) {
            Yii::$app->params['currency'] = $currency;
        }
    },
    */
    'on afterAction' => function () {
        // переадресация главных страниц на языковую локаль
        if (strpos(Yii::$app->request->absoluteUrl, Yii::$app->language) === false) {
            $homeUrl = Url::home(true);
            $localeUrl = preg_replace("#/$#", "", str_replace($homeUrl, $homeUrl . Yii::$app->language . '/', Yii::$app->request->absoluteUrl));

            Yii::$app->response->redirect($localeUrl, 301);
            Yii::$app->end();
        }
        
        // кладём язык в базу
        if (!Yii::$app->user->isGuest) {
            $user = User::findOne(Yii::$app->user->identity->id);
            $user->lang = Yii::$app->language;
            $user->save();
        }
    },
    'on beforeRender' => function () {

    },
    
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['admin'],
            'enableGeneratingPassword' => false,
            'controllerMap' => [
                // 'registration' => [
                    // 'class' => RegistrationController::className(),
                    // 'on ' . RegistrationController::EVENT_AFTER_CONFIRM => function ($e) {
                        // if (Yii::$app->user->identity->referal) {
                            // if ($user = User::findOne(base64_decode(Yii::$app->user->identity->referal))) {
                                // $addBonus = new Bonus();
                                // $addBonus->attributes = [
                                    // 'active' => 1,
                                    // 'user_id' => $user->id,
                                    // 'type' => 1,
                                    // 'amount' => 5,
                                    // 'reason' => 0,
                                    // 'description' => (string) Yii::$app->user->id,
                                    // 'created_at' => date('Y-m-d H:i:s'),
                                    // 'updated_at' => date('Y-m-d H:i:s'),
                                // ];
                                // $addBonus->save();
                            // }
                        // }
                    // },
                // ],
                'security' => [
                    'class' => SecurityController::className(),
                    'on ' . SecurityController::EVENT_AFTER_LOGIN => function ($e) {
                        Yii::$app->response->redirect(Url::to(['/account']))->send();
                    }
                ],
            ],
        ],     
    ],
    
    'components' => [
    
        'request' => [
            'baseUrl' => '',
            // 'class' => 'klisl\languages\Request',
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'Rb96MyfFjM3RfzzXQDD5kzgwzlPYFbv6',
            // 'enableCookieValidation' => false,
        ],
        
        // 'cache' => [
            // 'class' => 'yii\caching\FileCache',
            // 'class' => 'yii\caching\DummyCache',
            // 'defaultDuration' => 86400,
        // ],
        
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\bootstrap\BootstrapAssetPlugin' => false,
                'dvizh\filter\assets\Asset' => false,
                'dvizh\cart\assets\WidgetAsset' => [
                    'css' => [],
                    'depends' => [],
                ],
                'dvizh\filter\assets\FrontendAsset' => false,
                'dvizh\filter\assets\FrontendAjaxAsset' => false,
            ],
            'linkAssets' => true,
        ],

        'session' => [
            'name' => 'frontend',
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'tbl_session',
            'timeout' => 60*60*24*30, // 30 дней
            'cookieParams' => [
                'lifetime' => 60*60*24*30
            ]
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
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user',
                    '@vendor/dvizh/yii2-order/src/widgets/views' => '@frontend/views/yii2-order',
                    '@vendor/dvizh/yii2-filter/src/widgets' => '@frontend/views/yii2-filter',
                ],
            ],
        ],
        
        'languagesDispatcher' => [
            'class' => 'cetver\LanguagesDispatcher\Component',
            'languages' => function () {
                return Yii::$app->urlManager->languages;
            },
            // Order is important
            'handlers' => [
                [
                    // Detects a language based on host name
                    'class' => 'cetver\LanguagesDispatcher\handlers\HostNameHandler',
                    'request' => 'request', // optional, the Request component ID.                    
                    'hostMap' => function () {
                        $langs = Yii::$app->urlManager->languages;
                        $hostMap = [];
                        foreach ($langs as $lang) {
                            // $hostMap[Url::home(true) . ($lang == Yii::$app->sourceLanguage ? '' : $lang)] = $lang;
                            $hostMap[Url::to(['/'], true) . $lang] = $lang;
                        }
                        return $hostMap;
                    }
                    // 'hostMap' => [ // An array that maps hostnames to languages or a callable function that returns it.
                        // 'en.example.com' => 'en',
                        // 'ru.example.com' => 'ru'
                    // ]
                ],
                [
                    // Detects a language from the query parameter.
                    'class' => 'cetver\LanguagesDispatcher\handlers\QueryParamHandler',
                    'request' => 'request', // optional, the Request component ID.
                    'queryParam' => 'language' // optional, the query parameter name that contains a language.
                ],
                [
                    // Detects a language from the session.
                    // Writes a language to the session, regardless of what handler detected it.
                    'class' => 'cetver\LanguagesDispatcher\handlers\SessionHandler',
                    'session' => 'session', // optional, the Session component ID.
                    'key' => 'language' // optional, the session key that contains a language.
                ],
                [
                    // Detects a language from the cookie.
                    // Writes a language to the cookie, regardless of what handler detected it.
                    'class' => 'cetver\LanguagesDispatcher\handlers\CookieHandler',
                    'request' => 'request', // optional, the Request component ID.
                    'response' => 'response', // optional, the Response component ID.
                    'cookieConfig' => [ // optional, the Cookie component configuration.
                        'class' => 'yii\web\Cookie',
                        'name' => 'language',
                        'domain' => '',
                        'expire' => strtotime('+1 year'),
                        'path' => '/',
                        'secure' => true | false, // depends on Request::$isSecureConnection
                        'httpOnly' => true,
                    ]
                ],
                [
                    // Detects a language from an authenticated user.
                    // Writes a language to an authenticated user, regardless of what handler detected it.
                    // Note: The property "identityClass" of the "User" component must be an instance of "\yii\db\ActiveRecord"
                    'class' => 'cetver\LanguagesDispatcher\handlers\UserHandler',
                    'user' => 'user',  // optional, the User component ID.
                    'languageAttribute' => 'language_code' // optional, an attribute that contains a language.
                ],
                [
                    // Detects a language from the "Accept-Language" header.
                    'class' => 'cetver\LanguagesDispatcher\handlers\AcceptLanguageHeaderHandler',
                    'request' => 'request', // optional, the Request component ID.
                ],
                [
                    // Detects a language from the "language" property.
                    'class' => 'cetver\LanguagesDispatcher\handlers\DefaultLanguageHandler',
                    'language' => 'ru' // the default language.
                    /*
                    or
                    'language' => function () {
                        return \app\models\Language::find()
                            ->select('code')
                            ->where(['is_default' => true])
                            ->createCommand()
                            ->queryScalar();
                    },
                    */
                ]

            ],
        ],
        
        'assetsAutoCompress' => [
            'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled' => false, // YII_ENV_DEV ? false : true,
            'readFileTimeout' => 3,
            'jsCompress' => true,
            'jsCompressFlaggedComments' => true,
            'cssCompress' => true,
            'cssFileCompile' => true,
            'cssFileRemouteCompile' => false,
            'cssFileCompress' => true,
            'cssFileBottom' => false,
            'cssFileBottomLoadOnJs' => false,
            'jsFileCompile' => true,
            'jsFileRemouteCompile' => false,
            'jsFileCompress' => true,
            'jsFileCompressFlaggedComments' => false,
            'noIncludeJsFilesOnPjax' => false,
            'htmlFormatter' => [
                'class' => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
                'extra' => true,
                'noComments' => true,
                'maxNumberRows' => 5000000,
            ],
        ],
        
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],

        'urlManager' => [           
            'class' => 'cetver\LanguageUrlManager\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'languages' => function () {
                return Languages::find()
                    ->select('code')
                    ->where([
                        'active' => 1
                    ])
                    ->column();
            },
            'existsLanguageSubdomain' => false,
            'blacklist' => [

            ],
            'queryParam' => 'language',            
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'normalizeTrailingSlash' => true,
                'collapseSlashes' => true,
            ],
            'rules' => [
                // 'languages' => 'languages/default/index', //для модуля мультиязычности KLISL
                
                '/' => 'site/index',
                
                'login' => 'user/security/login',
                'logout' => 'user/security/logout',
                'register' => 'user/registration/register',
                'resend' => 'user/registration/resend',
                'request' => 'user/recovery/request',
                // 'account' => 'site/account',
                // 'account/edit' => 'user/settings/profile',
                'account' => 'user/settings/profile',
                
                'join/<referal>' => 'site/join',
                
                // 'catalog' => 'catalog/index',
                // 'catalog/<slug>' => 'catalog/index',
                // 'catalog/<slug>/<collection>' => 'catalog/index',
                // '<catalog:(slug)>' => 'catalog/index',
                
                'catalog' => 'catalog/index',
                'catalog/<path:[\w_\/-]+>' => 'catalog/category',
                
                // 'catalog' => 'catalog/index',
                // 'catalog/<path:[\w_\/-]+>' => 'catalog/products',
                
                // [
                    // 'pattern' => 'catalog/<path:[\w_\/-]+>',
                    // 'route' => 'catalog',
                    // 'defaults' => [
                        // 'path' => null,
                    // ],
                // ],
                
                
                // 'catalog/<path:[\w_\/-]+>' => 'catalog/index',
                'product/<slug>' => 'product/index',
                
                'checkout' => 'checkout/index',
                'checkout/pay' => 'checkout/pay',
                'checkout/error' => 'checkout/error',
                'checkout/success' => 'checkout/success',
                
                'cookies-notification-shown' => 'site/cookies-notification-shown',

                // 'about' => 'site/about',

                'orders' => 'orders/index',
                'orders/<id>' => 'orders/view',
                
                'blog' => 'site/blog',
                'news' => 'news/index',
                'news/<slug>' => 'news/post',
                
                'actions' => 'actions/index',
                'actions/<slug>' => 'actions/view',
                
                'synchro' => 'synchro/index', // !!!!!!!!!!!!!!!!!!!!!!!!!
                
                // 'facebook-conversions' => 'facebook-conversions/index',
                
                'sitemap' => 'site/sitemap',
                
                'curl' => 'curl/index',
                
                'wishlist' => 'wishlist/index',

                // '<controller>/<action>' => '<controller>/<action>',
                '<slug>' => 'pages/index',
                
                
                
                // '<lang:([a-z]{2,3}(-[A-Z]{2})?)>/<controller>/<action>/' => '<controller>/<action>',
                // '<action:(contact|language|about|signup|test)>' => 'site/<action>',
            ],
            
        ],

    ],

    'params' => $params,
];
