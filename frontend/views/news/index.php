<?php

$this->title = Yii::t('front', 'Новости');

?>

<div class="container-lg container-xl container-xxl">
    <h1 class="mb-3 text-uppercase">
        <?= Yii::t('front', 'Новости') ?>
    </h1>
    <div id="news" class="row mb-3">
<?php
    foreach ($posts as $post) {
?>
        <div class="col-sm-6 col-lg-4">
            <?= $this->render('_post', [
                    'post' => $post
                ])
            ?>
        </div>
<?php
    }
?>
    </div>
</div>