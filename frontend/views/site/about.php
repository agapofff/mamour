<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('front', 'О нас') . ' - ' . Yii::$app->name;

?>

<div id="about">

    <div id="main" class="container-lg container-xl container-xxl mb-4">

        <h1 class="mb-2 text-uppercase wow fadeIn" data-wow-duration="0.5s">
            <?= Yii::t('front', 'Преимущества UME') ?>
        </h1>

        <p class="text-uppercase d-sm-none wow fadeInUp">
            <?= Yii::t('front', 'Чтобы лучше понимать животных и заботиться об их здоровье, мы соединили современные технологии и тысячелетние традиции применения женьшеня') ?>.
        </p>    
        <div class="row align-items-center position-relative mb-20 mb-sm-12 mb-lg-4">
            <div id="about-cover" class="col-12 px-lg-1 wow fadeIn">
                <img src="/images/about/bg1.jpg" class="img-fluid border border-gray-500" alt="<?= $title ?>">
            </div>
            <div class="col-12 position-absolute top-0 left-0 right-0 bottom-0">
                <div class="row h-100 align-items-center">
                    <div class="col-sm-7 col-lg-4 pl-2 pl-xl-5 pr-0 d-none d-sm-block wow fadeInUp">
                        <p class="mb-0 font-weight-bold text-uppercase lead">
                            <?= Yii::t('front', 'Чтобы лучше понимать животных и заботиться об их здоровье, мы соединили современные технологии и тысячелетние традиции применения женьшеня') ?>.
                        </p>
                    </div>
                    <div class="col-12 col-sm-5 col-lg-4 h-100 px-0 pt-1 wow fadeIn">
                        <img src="/images/about/booster1.png" class="d-block h-100 mx-auto">
                    </div>
                    <div class="col-lg-4 pl-lg-5 pr-md-2 py-1 py-md-0">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-8 col-sm-6 col-md-5 col-lg-12 mt-1 mb-1 wow fadeInUp" data-wow-delay="0s" data-wow-duration="0.7s">
                                <div class="row align-items-center flex-nowrap">
                                    <div class="col-auto pr-0">
                                        <img src="/images/about/grain-free.svg" class="about-cover-icons" loading="lazy" alt="<?= $title ?> grain-free">
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            <?= Yii::t('front', 'Беззерновые') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 col-sm-6 col-md-5 col-lg-12 mt-1 mb-1 wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="0.7s">
                                <div class="row align-items-center flex-nowrap">
                                    <div class="col-auto pr-0">
                                        <img src="/images/about/insect-protein.svg" class="about-cover-icons" loading="lazy" alt="<?= $title ?> insect protein">
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            <?= Yii::t('front', 'Протеин насекомых') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 col-sm-6 col-md-5 col-lg-12 mt-1 mb-1 wow fadeInUp" data-wow-delay="1s" data-wow-duration="0.7s">
                                <div class="row align-items-center flex-nowrap">
                                    <div class="col-auto pr-0">
                                        <img src="/images/about/white-imperial-ginseng.svg" class="about-cover-icons" loading="lazy" alt="<?= $title ?> white imperial ginseng">
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            <?= Yii::t('front', 'Белый императорский женьшень') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 col-sm-6 col-md-5 col-lg-12 mt-1 mb-1 wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="0.7s">
                                <div class="row align-items-center flex-nowrap">
                                    <div class="col-auto pr-0">
                                        <img src="/images/about/7-ginsensenosides.svg" class="about-cover-icons" loading="lazy" alt="<?= $title ?> 7 Ginsensenosides">
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            <?= Yii::t('front', '37 Гинзенозидов') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>

    <div id="innovations">
        <div class="container-lg container-xl container-xxl">
            <div class="row mt-lg-5 mb-2 pt-2 position-relative">
                <?= Html::img('/images/arrow.svg', [
                        'class' => 'position-absolute top-0 right-0 arrow d-none d-md-block mt-2 wow fadeIn',
                        'style' => '
                            width: 4.5em;
                            transform: rotate(135deg);
                        ',
                    ])
                ?>
                <div class="col-md-11 offset-md-1">
                    <h2 class="text-uppercase font-weight-bolder mb-2 wow fadeInUp">
                        <?= Yii::t('front', 'Инновации и ИИ') ?>
                    </h2>
                    <p class="h5 pr-md-15 pr-lg-17 pr-xl-19 wow fadeInUp">
                        <?= Yii::t('front', 'С помощью систем видеомониторинга и искусственного интеллекта мы изучаем пищевые предпочтения наших питомцев и подбираем идеальный рацион') ?>.
                    </p>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-7 col-xl-8">
                    <img src="/images/about/innovations.jpg" alt="<?= $title ?>" class="img-fluid border border-gray-500">
                </div>
                <div class="col-11 col-lg-5 col-xl-4">
                    <ul class="check-circle my-2 my-lg-0">
                        <li class="wow fadeInUp" data-wow-delay="0s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Видеонаблюдение за собаками в режиме 24/7') ?>.
                        </li>
                        <li class="wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Анализ полученных данных на основе возможностей искусственного интеллекта') ?>.
                        </li>
                        <li class="wow fadeInUp" data-wow-delay="1s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Создание индивидуальных карт оптимального и сбалансированного питания') ?>.
                        </li>
                        <li class="mb-0 wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Европейское производство, сертифицированное сырье') ?>.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="alternatives" class="container-lg container-xl container-xxl pt-7 mb-5 mb-lg-7 position-relative">
        <div class="row position-absolute top-0 right-0 left-0">
            <div class="col-md-10 offset-md-1 pl-md-1_5">
                <img src="/images/about/bg2.svg" class="d-block w-100">
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-lg-9 col-xl-8 offset-md-1 position-relative">
                <h2 class="d-inline-block text-uppercase font-weight-bolder position-relative mb-2 wow fadeInUp" style="max-width: 600px">
                    <?= Yii::t('front', 'Альтернативные источники белка') ?>
                </h2>
                <?= Html::img('/images/arrow.svg', [
                        'class' => 'position-absolute bottom-0 right-0 d-none d-md-inline-block mb-2 wow fadeIn',
                        'style' => '
                            width: 4.5em;
                            transform: rotate(45deg);
                        ',
                    ])
                ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-10 col-lg-9 col-xl-8 offset-md-1">
                <p class="h5 wow fadeInUp">
                    <?= Yii::t('front', 'Корма UME разработаны на основе белка насекомых: это позволяет сделать производство экологичнее, а меню собак — разнообразнее и питательнее') ?>.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-11 offset-lg-1 position-relative">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 order-lg-last wow fadeIn">
                        <img src="/images/about/alternatives.jpg" class="img-fluid border border-gray-500" alt="<?= $title ?>">
                    </div>
                    <div class="col-11 col-lg-6">
                        <ul class="check-circle my-2 my-lg-0">
                            <li class="wow fadeInUp" data-wow-delay="0s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Протеин насекомых богат аминокислотами, является отличным источником таурина и витаминов') ?>.
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Насекомые очень питательны: они состоят в среднем на 70% из белка') ?>.
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="1s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'При производстве сырья из насекомых выбрасывается до 98% меньше парниковых газов, чем при переработке продуктов животноводства') ?>.
                            </li>
                            <li class="mb-0 wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'До 2025 года рынок белка из насекомых будет расти в среднем на 37,5%*') ?>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="ginseng" class="position-relative mb-4 mb-lg-7">
        <div class="container-lg container-xl container-xxl">
            <div class="row mb-2">
                <div class="col-md-10 col-lg-9 col-xl-8 offset-md-1">
                    <h2 class="text-uppercase font-weight-bolder position-relative mb-2 wow fadeInUp">
                        <?= Yii::t('front', 'Белый императорский женьшень') ?>
                    </h2>
                    <p class="h5 wow fadeInUp">
                        <?= Yii::t('front', 'Выращивая женьшень 6 лет в экологически чистых условиях горы Чанбайшань, мы сохраняем в его корне максимум полезных веществ, включая 37 видов гинзенозидов') ?>.
                    </p>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7 col-xl-8 wow fadeIn">
                    <video autoplay loop muted playsinline class="d-block w-100">
                        <source src="/video/ginseng_small.mp4" type="video/mp4">
                        <source src="/video/ginseng_small.ogv" type="video/ogv">
                        <source src="/video/ginseng_small.webm" type="video/webm">
                    </video>
                </div>
                <div class="col-11 col-lg-5 col-xl-4">
                    <ul class="check-circle my-2 my-lg-0">
                        <li class="wow fadeInUp" data-wow-delay="0s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Является сильным природным адаптогеном и эффективно влияет на здоровье питомцев') ?>.
                        </li>
                        <li class="wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Улучшает работу сердечно-сосудистой системы и ЖКТ') ?>.
                        </li>
                        <li class="wow fadeInUp" data-wow-delay="1s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Повышает выносливость и стрессоустойчивость') ?>.
                        </li>
                        <li class="mb-0 wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="0.7s">
                            <?= Yii::t('front', 'Стимулирует иммунную систему') ?>.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="hypoallergenic" class="container-lg container-xl container-xxl mb-5">
        <div class="row mb-2">
            <div class="col-md-10 col-lg-11 col-xl-10 offset-md-1 position-relative">
                <?= Html::img('/images/arrow.svg', [
                        'class' => 'position-absolute top-0 right-0 arrow d-none d-md-block mt-2 wow fadeIn',
                        'style' => '
                            width: 4.5em;
                            transform: rotate(-135deg);
                        ',
                    ])
                ?>
                <h2 class="text-uppercase font-weight-bolder position-relative wow fadeInUp">
                    <?= Yii::t('front', 'Гипоаллергенная беззерновая формула') ?>
                </h2>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-10 col-lg-11 col-xl-10 offset-md-1">
                <p class="h5 wow fadeInUp">
                    <?= Yii::t('front', 'Мы сознательно избегаем использования глютена, сои, искусственных красителей, ароматизаторов и консервантов — они вредны как человеку, так и животным') ?>.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-11 offset-lg-1 position-relative">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7 col-xl-8 order-lg-last wow fadeIn">
                        <img src="/images/about/noallergens.jpg" class="img-fluid border border-gray-500 wow fadeIn" alt="<?= $title ?>">
                    </div>
                    <div class="col-11 col-lg-5 col-xl-4">
                        <ul class="check-circle my-2 my-lg-0">
                            <li class="wow fadeInUp" data-wow-delay="0s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Изготовлены без использования кукурузы и пшеницы') ?>.
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Способствуют активному долголетию') ?>.
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="1s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Не содержат ГМО') ?>.
                            </li>
                            <li class="wow fadeInUp" data-wow-delay="1.5s" data-wow-duration="0.7s">
                                <?= Yii::t('front', 'Поддерживают блеск шерсти и чистоту кожи') ?>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-lg container-xl container-xxl">
        <div class="row mb-2">
            <div class="col-md-11 offset-md-1">
                <p class="text-purple opacity-50 small">
                    <small>
                        * <?= Yii::t('front', 'Исследование мирового FoodTech-рынка группы компаний «ЭФКО», Московской биржи и J’son&Partners Consulting') ?>
                    </small>
                </p>
            </div>
        </div>
    </div>

    <!-- 
    <div class="container-lg container-xl container-xxl pt-4 mt-5 position-relative">
        <div class="row position-absolute top-0 right-0 left-0" style="z-index: -1">
            <div class="col-md-10">
                <img src="/images/about/bg2.svg" class="d-block w-100">
            </div>
        </div>
    </div>
    -->

</div>
