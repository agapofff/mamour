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
    use dvizh\shop\models\Category;
    
    AppAsset::register($this);
    
    $this->registerLinkTag([
        'rel' => 'canonical',
        'href' => Url::canonical()
    ]);
    
    
    // SEO   
    if ($this->params['model']) {
        $model = $this->params['model'];
        if ($model->seo->title && $modelTitle = json_decode($model->seo->title)->{Yii::$app->language}) {
            $this->title = $modelTitle;
        } else {
            $this->title = json_decode($model->name)->{Yii::$app->language};
        }
        if ($model->seo->description && $modelDescription = json_decode($model->seo->description)->{Yii::$app->language}) {
            $this->registerMetaTag([
                'name' => 'description',
                'content' => $modelDescription
            ]);
        }
        if ($model->seo->keywords && $modelKeywords = json_decode($model->seo->keywords)->{Yii::$app->language}) {
            $this->registerMetaTag([
                'name' => 'keywords',
                'content' => $modelKeywords
            ]);
        }
    }
    
    // fonts preload
    $fonts = [
        'names' => [
            'CourierNew' => [
                '',
                'Bold',
                'Italic',
                'BoldItalic',
            ],         
            // 'GothamPro' => [
                // '',
                // 'Bold',
                // 'Light',
            // ],
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
    if ($this->params['description']) {
        $this->registerMetaTag([
            'property' => 'og:description',
            'content' => $this->params['description']
        ]);        
    }
    $this->registerMetaTag([
        'property' => 'og:locale',
        'content' => Yii::$app->language
    ]);
    $this->registerMetaTag([
        'property' => 'og:site_name',
        'content' => Yii::$app->name
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


    // ???????????? ???????????? ???????????????? ?????????? ?? ??????????????????
    // Yii::$app->params['currency'] = \common\models\Languages::findOne([
        // 'code' => Yii::$app->language
    // ])->currency;
    
    // echo yii\helpers\VarDumper::dump(Yii::$app->params, 99, true);
    
    // ???????????????? ??????????
    $langs = new cetver\LanguageSelector\items\MenuLanguageItems([
        'languages' => Yii::$app->params['languages'],
    ]);
    $langs = $langs->toArray();

    foreach (Yii::$app->params['menu'] as $menuKey => $menuItem) {
        if ($menuItem['url'] == '/' . Yii::$app->request->pathInfo) {
            Yii::$app->params['menu'][$menuKey]['current'] = true;
        }
    }    
    // echo \yii\helpers\VarDumper::dump(Yii::$app->params['menu'], 99, true);
    // echo \yii\helpers\VarDumper::dump(Category::buildTreeArray(Yii::$app->params['menu']), 99, true);

    
    $controllerID = Yii::$app->controller->id;
    $actionID = Yii::$app->controller->action->id;
    
    // ?????????????? ?????????????????
    $isMainPage = $controllerID == 'site' && $actionID == 'index';
    
    // ???????????????? ????????????
    $isProductPage = $controllerID == 'product' && $actionID == 'index';
    
    $cart = Yii::$app->cart;

    $this->registerJs("
        CART_ADD_SUCCESS = '" . Yii::t('front', '?????????? ???????????????? ?? ??????????????') . "';
    ");
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
                    <span class="sr-only">????????????????...</span>
                </div>
            </div>
        </div>

    <?php $this->beginBody() ?>
            
        <nav id="nav" class="py-1 transition <?= $isMainPage ? 'position-absolute w-100' : '' ?>">
            <div id="nav-container" class="container-fluid">
                <div class="row align-items-center justify-content-between flex-nowrap">
                    <div class="col-8 col-sm-5 col-md-4 col-lg-3 col-xl-3">
                        <a id="logo" href="<?= Url::home(true) ?><?= Yii::$app->language ?>">
                            <img src="/images/logo_nav_<?= $isMainPage ? 'light' : 'dark' ?>.svg" class="img-fluid" alt="<?= Yii::$app->name ?>">
                        </a>
                    </div>
                    <div class="col-md-7 col-lg-6 col-xl-6 d-none d-lg-block">
                        <div id="mainmenu" class="row justify-content-between flex-nowrap">
                    <?php

                        foreach (Yii::$app->params['menu'] as $menuItem) {
                            $menuChilds = [];
                            $menuChilds = Category::getAllChilds(Yii::$app->params['menu'], $menuItem['id']);
                            if (!$menuItem['parent_id']) {
                    ?>
                                <div class="col-auto dropdown hover px-0 px-lg-0_5">
                                    <a href="<?= Url::to([$menuItem['url']]) ?>" class="btn btn-link mx-0 montserrat fs15px font-weight-bold text-uppercase text-decoration-none main-menu-item <?= !empty($menuChilds) ? 'dropdown-toggle' : '' ?>">
                                        <?= json_decode($menuItem['name'])->{Yii::$app->language} ?>
                                    </a>
                            <?php
                                if (!empty($menuChilds)) {
                            ?>
                                    <div class="dropdown-menu mt-0">
                                        <ul class="list-unstyled">
                                    <?php
                                        foreach (Yii::$app->params['menu'] as $menuItemChild) {
                                            if ($menuItemChild['parent_id'] == $menuItem['id']) {
                                    ?>
                                                <li>
                                                    <a href="<?= Url::to([$menuItemChild['url']]) ?>" class="dropdown-item montserrat fs15px">
                                                        <?= json_decode($menuItemChild['name'])->{Yii::$app->language} ?>
                                                    </a>
                                                    <?php Category::renderMenu(Category::buildTreeArray($menuChilds, $menuItemChild['id']), 'list-unstyled pl-2', 'montserrat fs15px', 'dropdown-item') ?>
                                                </li>
                                                <div class="dropdown-divider"></div>
                                    <?php
                                            }
                                        }
                                    ?>
                                        </ul>
                                    </div>
                            <?php
                                }
                            ?>
                                </div>
                    <?php
                            }
                        }
                    ?>
                        </div>
                    </div>

                    <div class="col-auto col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="row align-items-center justify-content-end flex-nowrap">
                            <div class="col-auto d-none d-sm-block pl-0">
                                <a href="<?= Url::to(['/search']) ?>" class="search">
                                    <img src="/images/search.svg">
                                </a>
                            </div>
                            <div class="col-auto d-none d-sm-block pl-sm-0_5 pl-md-1 pl-lg-0 pl-xl-1">
                                <a href="<?= Url::to(['/wishlist']) ?>">
                                    <img src="/images/wishlist.svg">
                                </a>
                            </div>
                            <div class="col-auto d-none d-sm-block pl-sm-0_5 pl-md-1 pl-lg-0 pl-xl-1">
                                <a href="<?= Yii::$app->user->isGuest ? Url::to(['/login']) : Url::to(['/account']) ?>">
                                    <img src="/images/<?= Yii::$app->user->isGuest ? 'guest' : 'user' ?>.svg">
                                </a>
                            </div>
                            <div class="col-auto d-none d-sm-block pl-sm-0_5 pl-md-1 pl-lg-0 pl-xl-1 pr-md-0_5 pr-lg-0_5">
                                <a href="<?= Url::to(['/cart']) ?>" class="d-flex align-items-center text-decoration-none">
                                    <img src="/images/cart.svg">
                                    <?= CartInformer::widget([
                                            'htmlTag' => 'span',
                                            'cssClass' => 'mt-1 small',
                                            'text' => '{c}'
                                        ]);
                                    ?>
                                </a>
                            </div>
                            <div class="col-auto pl-sm-0_5 pl-md-1 pl-lg-0 pl-xl-1">
                                <button id="language" type="button" class="btn btn-link text-dark text-uppercase text-decoration-none montserrat fs15px px-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= Yii::$app->language ?>
                                </button>
                                <div class="dropdown-menu bg-transparent m-0 py-0 px-1 border-0" aria-labelledby="language">
                            <?php
                                foreach ($langs as $key => $lang) {
                                    if ($lang['label'] !== Yii::$app->language) {
                                        echo Html::a($lang['label'], urldecode($lang['url']), [
                                            'class' => 'd-block bg-transparent px-0 text-uppercase text-decoration-none montserrat fs15px',
                                        ]);
                                    }
                                }
                            ?>
                                </div>
                            </div>
                            <div class="col-auto d-lg-none">
                                <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#menu" aria-label="<?= Yii::t('front', '????????') ?>">
                                    <img src="/images/menu.svg">
                                </button>
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
            
            <div class="container mt-6 mt-lg-8">
                <div class="row justify-content-center">
                    <div class="col-md-11 col-lg-10">
                        <h2 class="h1 gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-3">
                            <?= Yii::t('front', '??????????????????') ?>
                        </h2>
                        <p class="text-center lead courier letter-spacing-10 line-height-150">
                            <?= Yii::t('front', '?????????? ???????????????????? ???? ????????????????????. ?????????? ?????????????? ?????????? ?????????????????? ??????????????????, ???? ????????????, ?????? ?????? ?????????? ???????????????????? ???????????????? ?????? ???????????? ????????????, ???????????????????? ?? ???????????? ???????? ?????????????????? ??????????.') ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="container mt-4 mt-lg-6">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-3 text-center mb-1">
                        <a href="<?= Url::to(['/service/delivery']) ?>" class="text-decoration-none">
                            <img src="/images/main/mainpage_icon_delivery.png" class="mb-2">
                            <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                                <?= Yii::t('front', '???????????????????? ????????????????') ?>
                            </p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center mb-1">
                        <a href="<?= Url::to(['/service/payment']) ?>" class="text-decoration-none">
                            <img src="/images/main/mainpage_icon_payment.png" class="mb-2">
                            <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                                <?= Yii::t('front', '????????????') ?>
                            </p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center mb-1">
                        <a href="<?= Url::to(['/service/package']) ?>" class="text-decoration-none">
                            <img src="/images/main/mainpage_icon_package.png" class="mb-2">
                            <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                                <?= Yii::t('front', '????????????????') ?>
                            </p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center mb-1">
                        <a href="<?= Url::to(['/service']) ?>" class="text-decoration-none">
                            <img src="/images/main/mainpage_icon_service.png" class="mb-2">
                            <p class="gotham font-weight-bold text-uppercase text-center letter-spacing-10 mb-1_5">
                                <?= Yii::t('front', '???????????????????? ????????????') ?>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>

        <footer class="mt-5 mt-sm-7 pt-4 pt-lg-7 pb-1 pb-lg-4 bg-gray-300">
            <div class="container">
                <div class="row justify-content-center justify-content-xl-between">
                    <div class="col-lg-8 col-xl-4 mb-3">
                        <div class="row justify-content-between">
                            <div class="col col-xl-auto">
                                <p class="gotham text-uppercase font-weight-bold mb-1_5">
                                    <?= Yii::t('front', '?? ????????????') ?>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/about/history']) ?>">
                                        <?= Yii::t('front', '??????????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/about/philosophy']) ?>">
                                        <?= Yii::t('front', '??????????????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/gallery']) ?>">
                                        <?= Yii::t('front', '????????') ?>-<?= Yii::t('front', '??????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/about/publications']) ?>">
                                        <?= Yii::t('front', '????????????????????') ?>
                                    </a>
                                </p>
                            </div>
                            <div class="col">
                                <p class="gotham text-uppercase font-weight-bold mb-1_5">
                                    <?= Yii::t('front', '???????????????????? ????????????') ?>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/service/payment']) ?>">
                                        <?= Yii::t('front', '?????????????? ????????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/service/shipping']) ?>">
                                        <?= Yii::t('front', '????????????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/service/exchange-refund']) ?>">
                                        <?= Yii::t('front', '??????????') ?>-<?= Yii::t('front', '??????????????') ?>
                                    </a>
                                </p>
                                <p class="small text-lowercase mb-0">
                                    <a href="<?= Url::to(['/service/sizes']) ?>">
                                        <?= Yii::t('front', '?????????????????? ??????????') ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mb-3">
                        <div class="row h-100">
                            <div class="col-12 mb-3 mb-xl-0">
                                <form id="subscribe" action="https://mailer.i.bizml.ru/forms/simple/u/eyJ1c2VyX2lkIjoyMDAwMDQ2MTM4LCJhZGRyZXNzX2Jvb2tfaWQiOjkyNzMzMSwibGFuZyI6InJ1In0=" method="post" target="_blank" data-message="<?= Yii::t('front', '???????????????????? ?????? ???? ???????????????? ???? ???????? email-????????????????! ???? ?????????????????? ???????????? ?? ???????????????????????????? ???? ?????????????????? ???????? e-mail') ?>">
                                    <div class="row">
                                        <div class="col-12 col-sm">
                                            <input type="email" class="form-control py-1 py-sm-0_5 px-1_5 h-100" name="email" placeholder="<?= Yii::t('front', '?????? e-mail') ?>" required>
                                        </div>
                                        <div class="col-12 col-sm-auto mt-1 mt-sm-0">
                                            <button type="submit" class="btn btn-primary btn-sm-block gotham px-2 py-0_5 py-sm-1">
                                                <?= Yii::t('front', '??????????????????????') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="sender" value="sendbox@mamour-enfants.com">
                                </form>
                            </div>
                            <div class="col-12 align-self-end">
                                <div class="row justify-content-center justify-content-xl-between align-items-baseline">
                                    <div class="col-auto mb-3 mb-xl-0">
                                        <div class="row justify-content-between">
                                    <?php
                                        foreach (Yii::$app->params['socials'] as $socialName => $socialUrl) {
                                    ?>
                                            <div class="col pr-0">
                                                <a href="<?= $socialUrl ?>" target="_blank" class="text-decoration-none">
                                                    <img src="/images/socials/<?= $socialName ?>.svg" alt="<?= $socialName ?>">
                                                </a>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-auto mb-2 mb-xl-0 text-center">
                                        <p class="m-0 small" style="font-size: 13px">
                                            <em>
                                                <small>
                                                    ?? <?= date('Y') ?> <?= Yii::$app->config->name ?>. <?= Yii::t('front', 'All rights reserved') ?>
                                                </small>
                                            </em>
                                        </p>
                                    </div>
                                    <div class="col-12 col-xl-auto text-center">
                                        <p class="m-0 small" style="font-size: 13px">
                                            <em>
                                                <small>
                                                    <a href="<?= Url::to(['/privacy-policy']) ?>" class="text-decoration-underline">
                                                        <?= Yii::t('front', '???????????????? ????????????????????????????????????') ?>
                                                    </a>
                                                </small>
                                            </em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        
        <div class="modal side p-0 fade" id="menu" tabindex="-1" aria-labelledby="menuLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable position-absolute top-0 bottom-0 left-0 border-0 m-0">
                <div class="modal-content m-0 border-0 vh-100">
                    <div class="modal-header d-block px-1">
                        <div class="row justify-content-between align-items-center">
                            <div class="col text-center">
                                <a href="<?= Url::to(['/search']) ?>">
                                    <img src="/images/search.svg">
                                </a>
                            </div>
                            <div class="col text-center">
                                <a href="<?= Yii::$app->user->isGuest ? Url::to(['/login']) : Url::to(['/account']) ?>">
                                    <img src="/images/<?= Yii::$app->user->isGuest ? 'guest' : 'user' ?>.svg">
                                </a>
                            </div>
                            <div class="col text-center">
                                <a href="<?= Url::to(['/wishlist']) ?>">
                                    <img src="/images/wishlist.svg">
                                </a>
                            </div>
                            <div class="col text-center">
                                <a href="<?= Url::to(['/cart']) ?>" class="d-flex align-items-center text-decoration-none">
                                    <img src="/images/cart.svg">
                                    <?= CartInformer::widget([
                                            'htmlTag' => 'span',
                                            'cssClass' => 'mt-1',
                                            'text' => '{c}'
                                        ]);
                                    ?>
                                </a>
                            </div>
                            <div class="col text-center">
                                <button type="button" class="btn btn-link p-0" data-dismiss="modal" aria-label="<?= Yii::t('front', '??????????????') ?>">
                                    <img src="/images/close.svg">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div id="mobile-menu" class="nav flex-column my-auto w-100">
                            <?php Category::renderMenu(Category::buildTreeArray(Yii::$app->params['menu']), 'list-unstyled pl-2', '', 'dropdown-item'); ?>
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
                            <?= Yii::t('front', '??????????????') ?> (<?= CartInformer::widget([
                                    'htmlTag' => 'span',
                                    'cssClass' => 'dvizh-cart-informer',
                                    'text' => '{c}'
                                ]);
                            ?>)
                        </span>
                        <button type="button" class="close p-0 float-none" data-dismiss="modal" aria-label="<?= Yii::t('front', '??????????????') ?>">
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
                                        'text' => Yii::t('front', '??????????') . ': {p}'
                                    ]);
                                ?>
                                <?= Html::a(Yii::t('front', '???????????????? ??????????'), [
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
            // ?????????? ?????????????? ?????? ??????????????????
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
