<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
    use frontend\assets\AppAsset;
    // use common\widgets\Alert;
    use yii\web\View;
    use dvizh\cart\widgets\CartInformer;
    use dvizh\cart\widgets\ElementsList;

    AppAsset::register($this);
    
    $this->registerLinkTag([
        'rel' => 'canonical',
        'href' => Url::canonical()
    ]);

    if (Yii::$app->params['title']) {
        $this->title = Yii::$app->params['title'];
    }

    if (Yii::$app->params['description']) {
        $this->registerMetaTag([
            'name' => 'description',
            'content' => Yii::$app->params['description']
        ]);
    }
    
    
    // fonts preload
    $fonts = [
        'names' => [
            'CourierNew' => [
                '',
                'Bold',
            ],
            'GothamPro' => [
                '',
                'Bold',
                'Light',
            ],
        ],
        'extensions' => [
            'eot',
            'ttf',
            'woff',
            'woff2',
            'svg',
        ]
    ];
    foreach ($fonts['names'] as $fontName => $fontTypes) {
        foreach ($fontTypes as $fontType) {
            foreach ($fonts['extensions'] as $fontExtension) {
                $this->registerLinkTag([
                    'rel' => 'preload',
                    'as' => 'font',
                    'href' => '/fonts/' . $fontName . '/' . $fontName . $fontType . '.' . $fontExtension,
                    'type' => 'font/' . $fontExtension,
                    'crossorigin' => true,
                ]);     
            }
        }
    }
    
    
    // OPEN GRAPH
    $this->registerMetaTag([
        'property' => 'og:title',
        'content' => $this->title
    ]);
    if (Yii::$app->params['description']) {
        $this->registerMetaTag([
            'property' => 'og:description',
            'content' => Yii::$app->params['description']
        ]);        
    }
    $this->registerMetaTag([
        'property' => 'og:locale',
        'content' => Yii::$app->language
    ]);
    $this->registerMetaTag([
        'property' => 'og:site_name',
        'content' => Yii::$app->id
    ]);
    $this->registerMetaTag([
        'property' => 'og:type',
        'content' => 'website'
    ]);
    $this->registerMetaTag([
        'property' => 'og:updated_time',
        'content' => Yii::$app->formatter->format('now', 'datetime')
    ]);
    $this->registerMetaTag([
        'property' => 'og:url',
        'content' => Url::canonical()
    ]);


    // кладём валюту текущего языка в параметры
    // Yii::$app->params['currency'] = \common\models\Languages::findOne([
        // 'code' => Yii::$app->language
    // ])->currency;
    
    // echo yii\helpers\VarDumper::dump(Yii::$app->params, 99, true);
    
    // получаем языки
    $langs = new cetver\LanguageSelector\items\MenuLanguageItems([
        'languages' => Yii::$app->params['languages'],
    ]);
    $langs = $langs->toArray();
    
    
    // меню
    $menuItems = [
        [
            'label' => Yii::t('front', 'Девочки'),
            'url' => Url::to(['/catalog/girls-3-8']),
            'class' => 'caret',
        ],
        [
            'label' => Yii::t('front', 'Мальчики'),
            'url' => Url::to(['/catalog/boys-3-8']),
            'class' => 'caret',
        ],
        [
            'label' => Yii::t('front', 'О бренде'),
            'url' => Url::to(['/about']),
            'class' => '',
        ],
        [
            'label' => Yii::t('front', 'Блог'),
            'url' => Url::to(['/blog']),
            'class' => '',
        ],
    ];
    
    // меню
    $footerMenuItems = [
        [
            'label' => Yii::t('front', 'Главная'),
            'url' => Url::to(['/']),
            'class' => '',
        ],
        [
            'label' => Yii::t('front', 'Подписка'),
            'url' => Url::to(['/subscribtion']),
            'class' => '',
        ],
        [
            'label' => Yii::t('front', 'Каталог'),
            'url' => Url::to(['/catalog']),
            'class' => '',
        ],
        [
            'label' => Yii::t('front', 'Блог'),
            'url' => Url::to(['/blog']),
            'class' => '',
        ],
        [
            'label' => Yii::t('front', 'Преимущества'),
            'url' => Url::to(['/about']),
            'class' => '',
        ],
    ];
    
    if (Yii::$app->user->isGuest) {
        $footerMenuItems[] = [
            'label' => Yii::t('front', 'Авторизация'),
            'url' => Url::to(['/login']),
            'class' => '',
        ];
    } else {
        $footerMenuItems[] = [
            'label' => Yii::t('front', 'Личный кабинет'),
            'url' => Url::to(['/account']),
            'class' => '',
        ];
    }
    
    $controllerID = Yii::$app->controller->id;
    $actionID = Yii::$app->controller->action->id;
    
    // главная страница?
    $isMainPage = $controllerID == 'site' && $actionID == 'index';
    
    // карточка товара
    $isProductPage = $controllerID == 'product' && $actionID == 'index';
    
    $cart = Yii::$app->cart;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="HandheldFriendly" content="true"/>
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="194x194" href="/images/favicons/favicon-194x194.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/images/favicons/android-chrome-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
        <link rel="manifest" href="/images/favicons/site.webmanifest">
        <link rel="mask-icon" href="/images/favicons/safari-pinned-tab.svg" color="#f4f7f6">
        <link rel="shortcut icon" href="/images/favicons/favicon.ico">
        <meta name="msapplication-TileColor" content="#f4f7f6">
        <meta name="msapplication-TileImage" content="/images/favicons/mstile-144x144.png">
        <meta name="msapplication-config" content="/images/favicons/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body data-page="<?= base64_encode($controllerID . '/' . $actionID) ?>" class="position-relative">
        
        <div id="loader" class="fixed-top vw-100 vh-100 bg-white opacity-75">
            <div class="d-flex vw-100 vh-100 align-items-center justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
                <!--
                <svg width="150" height="204" viewBox="0 0 150 204" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M110.887 130.389C120.238 119.45 129.252 107.898 136.418 95.3405C140.001 89.1457 142.857 82.7276 144.816 75.8072C147.224 67.2126 148.736 58.3389 148.96 49.4652C149.184 41.2612 148.512 32.6666 146.048 24.7975C144.145 18.6584 140.841 12.5752 135.354 8.94761C121.693 -0.0376936 105.065 12.5194 96.3864 22.7883C87.0363 33.8944 81.7174 47.9025 79.9818 62.1897C79.7578 63.9756 79.5899 65.7057 79.5339 67.4916C80.1497 67.38 80.7656 67.3242 81.4375 67.2126C76.5105 53.2044 71.4715 39.0289 62.9613 26.695C59.1541 21.2257 54.7869 16.0912 49.692 11.7939C44.4851 7.38495 38.3263 3.19925 31.5517 1.58078C25.0011 0.0181158 18.6184 1.63659 13.5794 6.10134C8.87637 10.287 5.96497 16.0354 4.00537 21.8954C-0.641672 35.7919 0.646065 50.8604 4.17334 64.8686C7.53265 78.207 12.8516 91.099 18.8423 103.433C24.6651 115.376 31.4397 126.873 39.0542 137.811C46.7246 148.75 55.1789 159.131 64.417 168.841C73.767 178.664 83.845 187.761 94.5388 196.132C96.8343 197.918 99.1298 199.816 101.537 201.378C103.049 202.383 104.953 203.053 106.8 202.997C109.04 202.941 111.503 201.322 111.895 198.978C112.231 197.137 110.719 195.742 109.208 194.904C107.36 193.956 105.345 193.509 103.329 193.007C99.6897 192.058 95.9945 191.054 92.3552 190.105C91.5154 189.882 90.7316 189.658 89.8917 189.435C88.716 189.1 88.2121 190.942 89.3878 191.277C94.5948 192.672 99.8017 194.123 105.009 195.407C106.464 195.797 110.831 196.858 109.824 199.034C108.76 201.322 106.072 201.267 104.057 200.43C102.321 199.76 100.921 198.476 99.4658 197.36C88.9959 189.547 79.1419 180.896 69.9598 171.632C60.8897 162.535 52.4914 152.713 44.821 142.388C37.2625 132.175 30.4879 121.403 24.4972 110.186C18.5064 98.9123 13.2435 87.1365 9.21231 74.9701C4.73323 61.6875 1.70985 47.5119 3.33351 33.4479C4.11735 26.8624 5.96497 20.1095 9.26829 14.3053C12.1237 9.33828 16.3788 4.81772 22.0337 3.25506C28.9203 1.30173 35.9188 4.48286 41.6296 8.11047C47.0605 11.5707 51.9315 15.9238 56.1307 20.835C65.3128 31.4946 71.0796 44.4982 75.9506 57.5576C77.1823 60.9061 78.3581 64.1989 79.5339 67.5474C79.8698 68.552 81.3255 68.3846 81.4375 67.2684C82.2773 53.4277 86.4204 39.6428 94.4268 28.2577C98.458 22.4535 103.553 17.319 109.432 13.3007C115.646 9.05923 123.709 5.8781 131.211 8.66857C137.202 10.9009 141.121 16.5935 143.305 22.3419C146.104 29.5413 147 37.522 147.112 45.1679C147.28 53.7625 146.16 62.413 144.145 70.7844C143.249 74.4678 142.241 78.207 140.897 81.7788C139.721 84.8483 138.266 87.8621 136.754 90.7641C130.091 103.21 121.413 114.651 112.399 125.533C111.447 126.65 110.551 127.766 109.6 128.882C108.76 129.998 110.104 131.338 110.887 130.389Z" stroke="#B8994F" stroke-width="2" class="loading-heart-1"></path>
                </svg>
                -->
            </div>
        </div>

    <?php $this->beginBody() ?>
            
        <nav id="nav" class="py-1 transition">
            <div id="nav-container" class="container-xxl">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <button id="language" type="button" class="btn btn-link text-dark text-uppercase text-decoration-none gotham px-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= Yii::$app->language ?>
                                </button>
                                <div class="dropdown-menu bg-transparent m-0 py-0 px-1 border-0" aria-labelledby="language">
                            <?php
                                foreach ($langs as $key => $lang) {
                                    if ($lang['label'] !== Yii::$app->language) {
                                        echo Html::a($lang['label'], urldecode($lang['url']), [
                                            'class' => 'd-block bg-transparent px-0 text-uppercase text-decoration-none gotham',
                                        ]);
                                    }
                                }
                            ?>
                                </div>
                            </div>
                            <div class="col-auto d-none d-md-block">
                                <a href="#" class="btn btn-link text-dark text-decoration-none search">
                                    <img src="/images/search.svg">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <a id="logo" href="<?= Url::home(true) ?><?= Yii::$app->language ?>">
                            <?= Html::img('/images/logo.svg') ?>
                        </a>
                    </div>
                    
                    <div class="col-auto">
                        <div class="row align-items-center">
                            <div class="col-auto d-none d-md-block">
                                <a href="<?= Url::to(['/wishlist']) ?>">
                                    <img src="/images/wishlist.svg">
                                </a>
                            </div>
                            <div class="col-auto d-none d-md-block">
                                <a href="<?= Yii::$app->user->isGuest ? Url::to(['/login']) : Url::to(['/account']) ?>" class="d-flex align-items-center justify-content-center p-0_25 rounded-pill <?= Yii::$app->user->isGuest ? '' : 'border border-primary' ?>" style="width:2em; height: 2em;">
                                    <img src="/images/user.svg">
                                </a>
                            </div>
                            <div class="col-auto d-none d-md-block">
                                <a href="<?= Url::to(['/cart']) ?>">
                                    <img src="/images/cart.svg">
                                </a>
                            </div>
                            <div class="col-auto d-md-none">
                                <a href="#menu">
                                    <img src="/images/menu.svg">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-xxl">
                <div class="row justify-content-center mt-1 mb-0_5 d-none d-md-flex">
                    <div class="col-sm-11 col-md-10 col-lg-8 col-xl-6">
                        <div class="row justify-content-center">
                            <div class="col-xxl-10">
                                <div class="row justify-content-between flex-nowrap">
                            <?php
                                foreach ($menuItems as $menuItem) {
                            ?>
                                    <div class="col-auto">
                                        <a href="<?= $menuItem['url'] ?>" class="btn btn-link mx-0 gotham font-weight-bold text-uppercase text-decoration-none main-menu-item <?= $menuItem['class'] ?>">
                                            <?= $menuItem['label'] ?>
                                        </a>
                                    </div>
                            <?php
                                }
                            ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div id="pagecontent">

            <?php 
                // echo Breadcrumbs::widget([
                    // 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                // ]);
            ?>
            
            <?= \lavrentiev\widgets\toastr\NotificationFlash::widget([
                    'options' => [
                        "closeButton" => true,
                        "debug" => false,
                        "newestOnTop" => false,
                        "progressBar" => false,
                        "positionClass" => \lavrentiev\widgets\toastr\NotificationFlash::POSITION_BOTTOM_RIGHT,
                        "preventDuplicates" => true,
                        "onclick" => null,
                        "showDuration" => "300",
                        "hideDuration" => "1000",
                        "timeOut" => "5000",
                        "extendedTimeOut" => "1000",
                        "showEasing" => "swing",
                        "hideEasing" => "linear",
                        "showMethod" => "fadeIn",
                        "hideMethod" => "fadeOut",
                        'escapeHtml' => false,
                    ]
                ])
            ?>

            <div id="content">
                <?= $content ?>
            </div>
            
        </div>

        <script type="text/javascript" async="async" src="//mailer.i.bizml.ru/apps/fc3/build/default-handler.js?1665563471096"></script> 
        <footer class="mt-5 mt-sm-7 pt-4 pt-sm-7 pb-1 pb-sm-2 bg-gray-300">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-sm-11 col-md-10 col-lg-8 col-xl-6">
                        <div id="sp-form-3524" sp-id="3524" sp-hash="2b7f4940b15a86304377d80aa6b26da567d871f9c9b68c63084ddbc75e4689a7" sp-lang="ru" class="row justify-content-center sp-form sp-form-regular sp-form-embed sp-form-horizontal sp-form-full-width" sp-show-options="%7B%22satellite%22%3A%22biz_mailru%22%2C%22maDomain%22%3A%22mailer.i.bizml.ru%22%2C%22formsDomain%22%3A%22form.i.bizml.ru%22%2C%22condition%22%3A%22onEnter%22%2C%22scrollTo%22%3A25%2C%22delay%22%3A10%2C%22repeat%22%3A3%2C%22background%22%3A%22rgba(0%2C%200%2C%200%2C%200.5)%22%2C%22position%22%3A%22bottom-right%22%2C%22animation%22%3A%22%22%2C%22hideOnMobile%22%3Afalse%2C%22submitRedirectUrl%22%3A%22%22%2C%22urlFilter%22%3Afalse%2C%22urlFilterConditions%22%3A%5B%7B%22force%22%3A%22hide%22%2C%22clause%22%3A%22contains%22%2C%22token%22%3A%22%22%7D%5D%2C%22analytics%22%3A%7B%22ga%22%3A%7B%22eventLabel%22%3A%22%D0%A4%D0%BE%D1%80%D0%BC%D0%B0_%D0%BF%D0%BE%D0%B4%D0%BF%D0%B8%D1%81%D0%BA%D0%B8_%D0%9C%D0%BE%D1%8F_%D0%BF%D0%B5%D1%80%D0%B2%D0%B0%D1%8F_%D0%B0%D0%B4%D1%80%D0%B5%D1%81%D0%BD%D0%B0%D1%8F_%D0%BA%D0%BD%D0%B8%D0%B3%D0%B0%22%2C%22send%22%3Afalse%7D%2C%22ym%22%3A%7B%22counterId%22%3Anull%2C%22eventLabel%22%3Anull%2C%22targetId%22%3Anull%2C%22send%22%3Afalse%7D%7D%2C%22utmEnable%22%3Afalse%7D">
                            <div class="col-xxl-10 sp-form-fields-wrapper show-grid">
                                <form novalidate="" class="sp-element-container sp-lg sp-field-nolabel">
                                    <div class="row">
                                        <div class="col-12 col-sm sp-field" sp-id="sp-5c5c1eed-c8de-46b3-a850-75f376aed280">
                                            <input type="email" class="form-control py-1 py-sm-0_5 px-1_5 h-100 sp-form-control" name="sform[email]" placeholder="<?= Yii::t('front', 'Ваш E-mail') ?>" autocomplete="on" required="required" sp-type="email" sp-tips="%7B%22required%22%3A%22%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D0%B5%20%D0%BF%D0%BE%D0%BB%D0%B5%22%2C%22wrong%22%3A%22%D0%9D%D0%B5%D0%B2%D0%B5%D1%80%D0%BD%D1%8B%D0%B9%20email-%D0%B0%D0%B4%D1%80%D0%B5%D1%81%22%7D">
                                        </div>
                                        <div class="col-12 col-sm-auto mt-1 mt-sm-0 sp-field sp-button-container" sp-id="sp-1414a3be-b57b-42ac-9d92-a50ddd2626ef">
                                            <button id="sp-1414a3be-b57b-42ac-9d92-a50ddd2626ef" type="submit" class="btn btn-primary btn-sm-block gotham px-2 py-0_5 py-sm-1 sp-button">
                                                <?= Yii::t('front', 'Подписаться') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="sender" value="sendbox@mamour-enfants.com">
                                </form>
                                <div class="sp-message">
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-sm-11 col-md-10 col-lg-8 col-xl-6">
                        <div class="row justify-content-center">
                            <div class="col-xxl-10">
                                <div class="row justify-content-between align-items-end mt-3 mt-sm-5">
                                    <div class="col-12 col-md">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-5 col-xl-4 mb-2">
                                                <p class="gotham text-uppercase font-weight-bold mb-1_5">
                                                    <?= Yii::t('front', 'О бренде') ?>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/about/history']) ?>">
                                                        <?= Yii::t('front', 'История') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/about/philosophy']) ?>">
                                                        <?= Yii::t('front', 'Философия') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/gallery']) ?>">
                                                        <?= Yii::t('front', 'Фото') ?>-<?= Yii::t('front', 'Видео') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/about/publications']) ?>">
                                                        <?= Yii::t('front', 'Публикации') ?>
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="col-sm-6 col-md-7 col-lg-7 col-xl-6 mb-2">
                                                <p class="gotham text-uppercase font-weight-bold mb-1_5">
                                                    <?= Yii::t('front', 'Клиентский сервис') ?>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/service/payment']) ?>">
                                                        <?= Yii::t('front', 'Способы оплаты') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/service/shipping']) ?>">
                                                        <?= Yii::t('front', 'Философия') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/service/exchange-refund']) ?>">
                                                        <?= Yii::t('front', 'Обмен') ?>-<?= Yii::t('front', 'Возврат') ?>
                                                    </a>
                                                </p>
                                                <p class="small text-lowercase mb-0">
                                                    <a href="<?= Url::to(['/service/sizes']) ?>">
                                                        <?= Yii::t('front', 'Размерная сетка') ?>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto mt-sm-2 pb-2_5">
                                <?php
                                    foreach (Yii::$app->params['socials'] as $socialName => $socialUrl) {
                                ?>
                                        <a href="<?= $socialUrl ?>" target="_blank" class="text-decoration-none">
                                            <img src="/images/socials/<?= $socialName ?>.svg" alt="<?= $socialName ?>">
                                        </a>
                                <?php
                                    }
                                ?>
                                    </div>
                                </div>
                                <div class="row justify-content-between mt-2 mt-sm-4">
                                    <div class="col-12 col-lg-auto mb-0_5 mb-lg-1 text-center text-lg-left">
                                        <p>
                                            <em>
                                                <small>
                                                    © <?= date('Y') ?> <?= Yii::$app->config->name ?>. <?= Yii::t('front', 'Все права защищены') ?>
                                                </small>
                                            </em>
                                        </p>
                                    </div>
                                    <div class="col-12 col-lg-auto mb-0_5 mb-lg-1 text-center text-lg-right">
                                        <p>
                                            <em>
                                                <small>
                                                    <a href="<?= Url::to(['/privacy-policy']) ?>">
                                                        <?= Yii::t('front', 'Политика конфиденциальности') ?>
                                                    </a>
                                                </small>
                                            </em>
                                        </p>
                                    </div>
                                    <div class="col-12 text-center mt-2 mb-1">
                                        <small>
                                            <?= Yii::t('front', 'сделано в') ?> <a href="https://axioweb.ru" target="_blank">AXIO web</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        
        <div id="menu" class="modal side p-0 fade" tabindex="-1" aria-labelledby="menuLabel" aria-hidden="true">
            <div class="modal-dialog position-absolute top-0 right-0 left-0 vw-100 mt-6 mx-0 border-0">
                <div class="modal-content m-0 border-0 vw-100 min-vh-100 bg-gray-900 rounded-0">
                    <div class="modal-body p-0 rounded-0">
                        <div class="container-lg container-xl container-xxl">
                            <div class="d-sm-none mt-2">
                                <?php
                                    if ($langs) {
                                        foreach ($langs as $key => $lang) {
                                            echo Html::a($lang['label'], $lang['url'], [
                                                'class' => 'text-uppercase text-white mr-1 ' . ($lang['active'] ? 'text-underline' : 'text-decoration-none')
                                            ]);
                                        }
                                    }
                                ?>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-xl-11 col-xxl-10 mt-3 mt-md-4 mt-lg-5 mt-xl-6 mt-xxl-7">
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <ul class="nav flex-column my-auto">
                                                <?php                            
                                                    foreach ($menuItems as $menuItem) {
                                                        $activeMenu = false;
                                                        if (isset($menuItem['url'])) {
                                                            $activeMenu = $menuItem['url'] == Url::to();
                                                        }
                                                ?>
                                                        <li class="nav-item <?= $menuItem['class'] ?> <?= $activeMenu ? 'active' : '' ?>">
                                                        <?php
                                                            if (isset($menuItem['url'])) {
                                                        ?>
                                                                <a href="<?= $menuItem['url'] ?>" class="nav-link main-menu-item d-inline-block position-relative h4 font-weight-light text-uppercase text-white p-0 mb-1 mb-md-1_5 mb-lg-3 border-white <?= $activeMenu ? 'text-underline' : 'text-decoration-none' ?>"
                                                                    <?php 
                                                                        if (isset($menuItem['options'])) {
                                                                            foreach ($menuItem['options'] as $optionKey => $optionVal) {
                                                                                echo $optionKey . '="' . $optionVal . '" ';
                                                                            }
                                                                        }
                                                                    ?>
                                                                >
                                                                    <?= $menuItem['label'] ?>
                                                                </a>
                                                        <?php
                                                            } else {
                                                                echo $menuItem['label'];
                                                            }
                                                        ?>
                                                        </li>
                                                <?php
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="col-auto">
                                            <p class="h4 text-white font-weight-light text-uppercase mb-2">
                                                <?= Yii::t('front', 'Just like you') ?>
                                            </p>
                                            <p class="h5 text-white text-uppercase font-weight-light mb-3">
                                                <?= Yii::t('front', 'For ultra{0}high-net-worth{1}dogs', ['<br>', '<br>']) ?>
                                            </p>
                                            <div class="mb-3">
                                            <?php
                                                foreach (Yii::$app->params['socials'] as $socialName => $socialUrl) {
                                                    echo Html::a(Html::img('/images/socials/' . $socialName . '_light.svg', [
                                                        'class' => 'menu-social-icon',
                                                    ]), $socialUrl, [
                                                        'class' => 'mr-1',
                                                    ]);
                                                }
                                            ?>
                                            </div>
                                            <div id="menu-contacts" class="mb-4">
                                                <a href="mailto:<?= Yii::$app->params['supportEmail'] ?>" class="text-white" style="text-decoration: underline">
                                                    <?= Yii::$app->params['supportEmail'] ?>
                                                </a>
                                                <br>
                                                <a href="tel:+<?= preg_replace('/[^0-9]/', '', Yii::$app->params['phone']) ?>" class="text-white">
                                                    <?= Yii::$app->params['phone'] ?>
                                                </a>
                                            </div>
                                            <div class="mb-3">
                                                <?= Html::img('/images/logo/big.svg', [
                                                        'id' => 'logo-big',
                                                    ])
                                                ?>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col my-1">
                                                    <?= Html::img('/images/apple_store.png', [
                                                            'id' => 'apple-store',
                                                        ])
                                                    ?>
                                                </div>
                                                <div class="col my-1">
                                                    <?= Html::img('/images/google_play.png', [
                                                            'id' => 'google-play',
                                                        ])
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="modal side p-0 fade" id="mini-cart" tabindex="-1" aria-labelledby="miniCartLabel" aria-hidden="true">
            <div class="modal-dialog position-absolute top-0 bottom-0 right-0 max-vw-50 border-0 m-0">
                <div class="modal-content m-0 border-0 vh-100 vw-50">
                    <div class="modal-header align-items-center flex-nowrap py-md-2 pt-lg-3 pt-xl-4 pt-xxl-5 px-md-1 px-lg-2 px-xl-3">
                        <span class="ttfirsneue h1 m-0 text-nowrap font-weight-light">
                            <?= Yii::t('front', 'Корзина') ?> (<?= CartInformer::widget([
                                    'htmlTag' => 'span',
                                    'cssClass' => 'dvizh-cart-informer',
                                    'text' => '{c}'
                                ]);
                            ?>)
                        </span>
                        <button type="button" class="close p-0 float-none" data-dismiss="modal" aria-label="<?= Yii::t('front', 'Закрыть') ?>">
                            <svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <line x1="13.7891" y1="12.3744" x2="39.9521" y2="38.5373" stroke="black" stroke-width="2"/>
                                <line x1="12.3749" y1="38.5379" x2="38.5379" y2="12.3749" stroke="black" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="modal-body px-0 h-100 overflow-scroll">
                        <div class="w-100">
                            <div class="col-12 px-md-1 px-lg-2 px-xl-3">
                                <hr class="my-1_5">                            
                                <?= ElementsList::widget([
                                        'type' => 'div',
                                        'currency' => Yii::$app->params['currency'],
                                        'lang' => Yii::$app->language,
                                    ]);
                                ?>
                            </div>
                            <div id="mini-cart-total" class="col-12 px-md-1 px-lg-2 px-xl-3 mt-2 mb-2 text-right <?= $cart->getCount() == 0 ? 'd-none' : '' ?>">
                                <?= CartInformer::widget([
                                        'currency' => Yii::$app->params['currency'],
                                        'text' => Yii::t('front', 'Итого') . ': {p}'
                                    ]);
                                ?>
                                <?= Html::a(Yii::t('front', 'Оформить заказ'), [
                                            '/checkout'
                                        ], [
                                            'class' => 'btn btn-primary btn-hover-warning btn-block py-1 my-2 mini-cart-checkout-link',
                                        ]
                                    )
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    <?php
        if (!Yii::$app->session->get('cookiesNotificationShown')) {
            // echo $this->render('@frontend/views/layouts/_cookies');
        }
    ?>

<?php
    if (Yii::$app->controller->id != 'checkout') {
        $this->registerJs("
            // показ корзины при изменении
            // $(document).on('dvizhCartChanged', function () {
                // $('#mini-cart').modal('show');
            // });
        ", View::POS_READY);
    }
?>
        
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
