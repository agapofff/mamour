<?php
    use yii\helpers\Html;
    use lo\widgets\SlimScroll;
?>

<aside class="main-sidebar">

    <?= SlimScroll::widget([
        'options' => [
            'height' => '100%',
            'size' => '6px',
        ]
    ]); 
    ?>

    <section class="sidebar">
        
        <?php
        if (Yii::$app->user->isGuest) {
            $menuItems[] = [
                'label' => Yii::t('back', 'Войти'),
                'icon' => 'sign-in',
                'url' => ['/user/login'],
            ];
        } else {
            
            if (
                Yii::$app->user->can('/settings/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Параметры'),
                    'icon' => 'cogs',
                    'url' => ['/settings'],
                ];
            }
            
            if (
                (
                    Yii::$app->user->can('/source-message/*')
                    || Yii::$app->user->can('/source-message/index')
                ) && (
                    Yii::$app->user->can('/message/*')
                    || Yii::$app->user->can('/message/index')
                )
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Локализация'),
                    'icon' => 'language',
                    'items' => [
                        [
                            'label' => Yii::t('back', 'Константы'),
                            'icon' => 'language',
                            'url' => ['/source-message'],
                        ],
                        [
                            'label' => Yii::t('back', 'Переводы'),
                            'icon' => 'exchange',
                            'url' => ['/message']
                        ],
                    ],
                ];
            }
            
            if (
                Yii::$app->user->can('/treemenu/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Меню'),
                    'icon' => 'list',
                    'url' => ['/treemenu'],
                ];
            }
            
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
                || Yii::$app->user->can('/filter/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Фильтры'),
                    'icon' => 'filter',
                    'url' => ['/filter/filter'],
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
                Yii::$app->user->can('/stores/*')
                || Yii::$app->user->can('/stores/index')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Магазины'),
                    'icon' => 'home',
                    'url' => ['/stores'],
                ];
            }
            
            if (
                Yii::$app->user->can('/promocode/*')
                || Yii::$app->user->can('/promocode/promo-code')
                || Yii::$app->user->can('/promocode/promo-code/index')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Промокоды'),
                    'icon' => 'hashtag',
                    'url' => ['/promocode/promo-code'],
                ];
            }
            
            
            // if (
                // Yii::$app->user->can('/shop/*')
                // || Yii::$app->user->can('/order/payment/*')
                // || Yii::$app->user->can('/order/payment/index')
            // ) {
                // $menuItems[] = [
                    // 'label' => Yii::t('back', 'Транзакции'),
                    // 'icon' => 'money',
                    // 'url' => ['/order/payment'],
                // ];
            // }
            
            if (
                Yii::$app->user->can('/news/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Новости'),
                    'icon' => 'file-text-o',
                    'url' => ['/news'],
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
                Yii::$app->user->can('/slides/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Баннеры / Слайды'),
                    'icon' => 'file-image-o',
                    'url' => ['/slides'],
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
                Yii::$app->user->can('/galleries/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Галереи'),
                    'icon' => 'image',
                    'url' => ['/galleries'],
                ];
            }
            
            if (
                Yii::$app->user->can('/search-history/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'История поиска'),
                    'icon' => 'search',
                    'url' => ['/search-history'],
                ];
            }
            
            // if (
                // Yii::$app->user->can('/shop/*')
                // || Yii::$app->user->can('/field/*')
            // ) {
                // $menuItems[] = [
                    // 'label' => Yii::t('back', 'Доп.поля'),
                    // 'icon' => 'pencil-square-o',
                    // 'url' => ['/field'],
                // ];
            // }
            
            if (
                Yii::$app->user->can('/order/field/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Доп.поля заказа'),
                    'icon' => 'pencil-square',
                    'url' => ['/order/field'],
                ];
            }
            
            // if (
                // Yii::$app->user->can('/shop/price-type/*')
            // ) {
                // $menuItems[] = [
                    // 'label' => Yii::t('back', 'Типы цен'),
                    // 'icon' => 'usd',
                    // 'url' => ['/shop/price-type'],
                // ];
            // }
            
            if (
                Yii::$app->user->can('/countries/*')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Страны'),
                    'icon' => 'globe',
                    'url' => ['/countries'],
                ];
            }
            
            if (Yii::$app->user->can('/user/admin/*')) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Пользователи'),
                    'icon' => 'users',
                    'url' => ['/user/admin']
                ];
            }
            
            if (
                Yii::$app->user->can('/languages/*')
                || Yii::$app->user->can('/languages/index')
            ) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Языки'),
                    'icon' => 'flag',
                    'url' => ['/languages']
                ];
            }
            
            if (Yii::$app->user->can('/rbac/*')) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Контроль доступа'),
                    'icon' => 'key',
                    'items' => [
                        [
                            'label' => Yii::t('back', 'Маршруты'),
                            'icon' => 'map-signs',
                            'url' => ['/rbac/route']
                        ],
                        [
                            'label' => Yii::t('back', 'Роли'),
                            'icon' => 'user-circle',
                            'url' => ['/rbac/role']
                        ],
                        [
                            'label' => Yii::t('back', 'Разрешения'),
                            'icon' => 'check-square-o',
                            'url' => ['/rbac/permission']
                        ],
                        [
                            'label' => Yii::t('back', 'Привязки'),
                            'icon' => 'code-fork',
                            'url' => ['/rbac/assignment']
                        ],
                    ],
                ];
            }
            
            if (Yii::$app->user->can('/gii/*') && YII_ENV_DEV) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Gii'),
                    'icon' => 'file-code-o',
                    'url' => ['/gii']
                ];
            }
            
            if (Yii::$app->user->can('/debug/*')&& YII_ENV_DEV) {
                $menuItems[] = [
                    'label' => Yii::t('back', 'Debug'),
                    'icon' => 'dashboard',
                    'url' => ['/debug']
                ];
            }
            
        }
    ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $menuItems,
            ]
        ) ?>

    </section>
    
    <?= SlimScroll::end(); ?>

</aside>
