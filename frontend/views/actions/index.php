<?php

$this->title = Yii::t('front', 'Акции');

?>

<div class="container-lg container-xl container-xxl">
    <h1 class="mb-3 text-uppercase">
        <?= Yii::t('front', 'Акции') ?>
    </h1>
    <div id="actions" class="row mb-3">
<?php
    foreach ($actions as $action) {
?>
        <div class="col-md-6">
            <?= $this->render('_post', [
                    'action' => $action
                ])
            ?>
        </div>
<?php
    }
?>
    </div>
</div>