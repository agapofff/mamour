<?php

use yii\helpers\Url;
use yii\helpers\Html;

$postName = json_decode($post->name)->{Yii::$app->language};

$this->title = $postName;

?>

<div class="container-lg container-xl container-xxl">
    <div class="row">
        <div class="col-md-10 col-lg-8">
            <div class="row">
                <div class="col-auto d-none d-md-block pl-0_5 pr-0">
                    <a href="<?= Url::to(['/news']) ?>">
                        <?= Html::img('/images/arrow.svg', [
                                'class' => 'wow fadeIn',
                                'style' => '
                                    width: 4.5em;
                                    transform: rotate(-135deg);
                                    margin-top: -0.5em;
                                ',
                            ])
                        ?>
                    </a>
                </div>
                <div class="col pl-1">
                    <p class="blog-post-date mb-1 opacity-50">
                        <?= Yii::$app->formatter->asDatetime($post->date_published, 'php:d.m.Y') ?>
                    </p>
                    <h1 class="h2 mb-3 text-uppercase font-weight-600">
                        <?= $postName ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
    <?php
        if ($image = $post->getImage()){
    ?>
            <?= Html::img($image->getUrl(), [
                    'class' => 'img-fluid mb-3',
                    'alt' => ($image->alt ?: $postName)
                ])
            ?>
    <?php
        }
    ?>
            <div class="px-xl-5">
                <?= str_replace('<img ', '<img class="img-fluid mx-auto" loading="lazy" ', json_decode($post->text)->{Yii::$app->language}) ?>
            </div>
        </div>
        <div class="col-xl-4 pl-xl-2 mt-3 mt-md-0">
            <div class="row">
    <?php
        if ($posts) {
            foreach ($posts as $post) {
    ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-12">
                    <?= $this->render('_post', [
                            'post' => $post
                        ])
                    ?>
                </div>
    <?php
            }
        }
    ?>
            </div>
        </div>
    </div>
</div>