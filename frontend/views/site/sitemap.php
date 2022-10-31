<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    
    $this->title = Yii::t('front', 'Карта сайта') . ' - ' . Yii::$app->name;
    
    function buildTree($cats, $html = '') {
        foreach ($cats as $cat) {
            echo '
                <ul>
                    <li>
                        <a href="' . Url::to(['/catalog/' . $cat['slug']]) . '">' . json_decode($cat['name'])->{Yii::$app->language} . '</a>';
            if (!empty($cat['childs'])) {
                buildTree($cat['childs'], $html);
            }
            echo '</li></ul>';
        }
    }
?>
    
<div class="container my-5">
    <div class="row my-5 py-5">
        <div class="col-auto mx-auto lead">
            <ul>
                <li>
                    <a href="<?= Url::to(Yii::$app->language, true) ?>"><?= Yii::t('front', 'Главная') ?></a>
                    <ul>
                        <li>
                            <a href="<?= Url::to(['/catalog']) ?>"><?= Yii::t('front', 'Каталог') ?></a>
                            <?= buildTree($categories) ?>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/about']) ?>"><?= Yii::t('front', 'О нас') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/about-mars']) ?>"><?= Yii::t('front', 'О Марсе') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/test/mars']) ?>"><?= Yii::t('front', 'Пройти тест') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/scan-to-win']) ?>"><?= Yii::t('front', 'Scan-to-Win') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/contacts/earth']) ?>"><?= Yii::t('front', 'Контакты') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/gps']) ?>"><?= Yii::t('front', 'Устройства GPS') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/expedition']) ?>"><?= Yii::t('front', 'Экспедиция на Марс') ?></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>