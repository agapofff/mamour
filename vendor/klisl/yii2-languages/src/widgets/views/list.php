<div class="languages dropdown hover">
    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="lead m-0">
        <?= Yii::$app->language ?>
    </a>
    <div class="dropdown-menu dropdown-menu-right border-0 text-center mt-0 pt-0" style="min-width: 60px;
    margin-right: -18px;">
    <?php foreach ($array_lang as $lang){?>
        <p class="lead mb-0">
            <?= $lang ?>
        </p>
    <?php } ?>
    </div>
</div>