<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('front', 'Блог');

?>

<div class="container-lg container-xl container-xxl">

    <h1 class="mb-2 text-uppercase">
        <?= Yii::t('front', 'Блог') ?>
    </h1>
    
    <h2 class="mb-3 ml-md-5 text-uppercase">
        <?= Yii::t('front', 'Новости') ?>
    </h2>

    <div id="news" class="row mb-4">
    <?php
        foreach ($posts as $post) {
            $image = $post->getImage();
            $cachedImage = '/images/cache/News/News' . $image->itemId . '/' . $image->urlAlias . '_500x500.' . $image->getExtension();
            $name = json_decode($post->name)->{Yii::$app->language};
    ?>
        <div class="col-sm-6 col-lg-4">
            <?= $this->render('/news/_post', [
                    'post' => $post
                ])
            ?>
        </div>
    <?php
        }
    ?>
        <div class="col-12">
            <p class="lead text-center mt-3">
                <a href="<?= Url::to(['/news']) ?>" class="text-dark">
                    <?= Yii::t('front', 'Все новости') ?>
                </a>
            </p>
        </div>
    </div>
    
    <hr>
    
    <h2 class="mt-3 mb-3 ml-md-5 text-uppercase">
        <?= Yii::t('front', 'Акции') ?>
    </h2>
    
    <div id="actions" class="row mb-4 px-xl-5">
<?php
    foreach ($actions as $action) {
?>
        <div class="col-md-6">
            <?= $this->render('/actions/_post', [
                    'action' => $action
                ])
            ?>
        </div>
<?php
    }
?>
        <div class="col-12">
            <p class="lead text-center my-3">
                <a href="<?= Url::to(['/actions']) ?>" class="text-dark">
                    <?= Yii::t('front', 'Все акции') ?>
                </a>
            </p>
        </div>
    </div>

</div>