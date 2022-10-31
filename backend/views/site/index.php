<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = Yii::$app->name;

$menuItems = [];
$adminItems = [];
 
            
if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/shop/category/*')
    || Yii::$app->user->can('/shop/category/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Категории'),
        'icon' => 'indent',
        'url' => ['/shop/category'],
    ];
}

if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/shop/product/*')
    || Yii::$app->user->can('/shop/product/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Товары'),
        'icon' => 'shopping-basket',
        'url' => ['/shop/product'],
    ];
}

if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/order/order/*')
    || Yii::$app->user->can('/order/order/index')
    // || Yii::$app->user->can('/order/operator/*')
    // || Yii::$app->user->can('/order/operator/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Заказы'),
        'icon' => 'shopping-cart',
        // 'url' => ['/order/operator'],
        'url' => ['/order/order'],
    ];
}

if (
    Yii::$app->user->can('/promocodes/*')
    || Yii::$app->user->can('/promocodes/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Промокоды'),
        'icon' => 'hashtag',
        'url' => ['/promocodes'],
    ];
}

if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/order/shipping-type/*')
    || Yii::$app->user->can('/order/shipping-type/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Способы доставки'),
        'icon' => 'truck',
        'url' => ['/order/shipping-type'],
    ];
}

if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/order/payment-type/*')
    || Yii::$app->user->can('/order/payment-type/index')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Способы оплаты'),
        'icon' => 'credit-card',
        'url' => ['/order/payment-type'],
    ];
}

if (
    Yii::$app->user->can('/shop/*')
    || Yii::$app->user->can('/filter/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Фильтры'),
        'icon' => 'filter',
        'url' => ['/filter/filter'],
    ];
}

if (
    Yii::$app->user->can('/news/*')
    && Yii::$app->user->can('/news-categories/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Новости'),
        'icon' => 'bold',
        'url' => ['/news'],
    ];
}

if (
    Yii::$app->user->can('/pages/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Страницы'),
        'icon' => 'files-o',
        'url' => ['/pages'],
    ];
}

if (
    Yii::$app->user->can('/meta-tags/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Мета-теги'),
        'icon' => 'tags',
        'url' => ['/meta-tags'],
    ];
}

if (
    Yii::$app->user->can('/redirects/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Редиректы'),
        'icon' => 'share-square-o',
        'url' => ['/redirects'],
    ];
}

if (
    Yii::$app->user->can('/actions/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Акции'),
        'icon' => 'gift',
        'url' => ['/actions'],
    ];
}

if (
    Yii::$app->user->can('/bonus/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Бонусы'),
        'icon' => 'star',
        'url' => ['/bonus'],
    ];
}

if (
    Yii::$app->user->can('/banners/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Баннеры'),
        'icon' => 'image',
        'url' => ['/banners'],
    ];
}

if (
    Yii::$app->user->can('/reviews/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Отзывы'),
        'icon' => 'commenting',
        'url' => ['/reviews'],
    ];
}

if (
    Yii::$app->user->can('/breeds/*')
) {
    $menuItems[] = [
        'label' => Yii::t('back', 'Породы'),
        'icon' => 'paw',
        'url' => ['/breeds'],
    ];
}
            

if (
    Yii::$app->user->can('/langs/*')
    || Yii::$app->user->can('/langs/index')
) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Языки'),
        'icon' => 'flag',
        'url' => ['/langs']
    ];
}
            
if (Yii::$app->user->can('/user/admin/*')) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Пользователи'),
        'icon' => 'users',
        'url' => ['/user/admin'],
    ];
}

if (Yii::$app->user->can('/rbac/*')) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Контроль доступа'),
        'icon' => 'key',
        'url' => ['/rbac/route'],
    ];
}

if (Yii::$app->user->can('/message/*')) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Локализация'),
        'icon' => 'globe',
        'url' => ['/source-message'],
    ];
}

if (Yii::$app->user->can('/countries/*')) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Страны'),
        'icon' => 'flag',
        'url' => ['/countries'],
    ];
}

if (
    Yii::$app->user->can('/stores/*')
    || Yii::$app->user->can('/stores/index')
) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Магазины'),
        'icon' => 'shopping-bag',
        'url' => ['/stores'],
    ];
}

if (Yii::$app->user->can('/gii/*') && YII_ENV_DEV) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Gii'),
        'icon' => 'file-code-o',
        'url' => ['/gii'],
    ];
}

if (Yii::$app->user->can('/debug/*') && YII_ENV_DEV) {
    $adminItems[] = [
        'label' => Yii::t('back', 'Debug'),
        'icon' => 'dashboard',
        'url' => ['/debug'],
    ];
}

function renderItem ($item) {
    return Html::tag('div',
            Html::tag('div',
                Html::a(
                    Html::tag('div',
                        Html::tag('div', Html::tag('span', '', [
                            'class' => 'fa fa-' . $item['icon']
                        ]),[
                            'class' => 'h1'
                        ]) . Html::tag('h4', $item['label']),
                    [
                        'class' => 'panel-body'
                    ]),
                $item['url']),
            [
                'class' => 'panel panel-default'
            ]
        ),[
            'class' => 'col-xs-12 col-sm-6 col-md-4 col-lg-3'
        ]);
}

$this->registerCss('h1 { display: none; }');

?>
    <div class="site-index text-center">
<?php

    if (!empty($menuItems)) {
?>
        <div class="row text-center">
<?php
        echo Html::tag('br');
        foreach ($menuItems as $k => $item) {
            echo renderItem($item);
        }
?>
        </div>
<?php
    }
    
    if (!empty($adminItems)) {
        echo Html::tag('br');
?>
        <div class="row">
            <hr>
<?php
        echo Html::tag('br');
        foreach ($adminItems as $k => $item) {
            echo renderItem($item);
        }
?>
        </div>
<?php
    }
?>
    </div>
