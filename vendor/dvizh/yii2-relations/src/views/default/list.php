<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div class="list-index">
    <form action="" method="post" class="dvizh-relations-search" style="
		position: fixed;
		top: 0;
		z-index: 3;
		width: 100%;
		margin-left: -14px;
		background: #fff;
		padding: 5px 18px;
		box-shadow: 0 0 7px rgba(0, 0, 0, .3);
	">
        <input type="text" class="form-control" name="s" value="<?=Html::encode(Yii::$app->request->post('s'));?>" placeholder="Поиск..." />
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    </form>
    <?php if (empty($modelList)) { ?>
        <p>Не найдено</p>
    <?php } ?>
    <ul class="dvizh-relations-list list-group" style="padding-top: 50px;">
    <?php 
		foreach($modelList as $model) {
			$name = json_decode($model->getName())->{Yii::$app->language};
			$imgSrc = null;
			$image = $model->getImage();
			if ($image){
				$cachedImage = '/images/cache/Products/Product' . $image->itemId . '/' . $image->urlAlias . '_50x50.jpg';
				$imgSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('50x50');
			}
	?>
            <li class="list-group-item">
                <a href="#" data-model="<?= $model::className() ?>" data-id="<?= $model->getId() ?>" data-name="<?= $name ?>" data-image="<?= $imgSrc ?>" class="dvizh-relations-variant " style="
					background-color: transparent !important;
					border: none !important;
				">
                    <div class="row">
                        <div class="col-lg-2 col-xs-2">
						<?php
							if ($imgSrc){
						?>
								<img src="<?= $imgSrc ?>" class="img-fluid" alt="<?= $image->alt ? $image->alt : $product_name ?>" loading="lazy">
						<?php
							}
						?>
						</div>
                        <div class="col-lg-10 col-xs-10">
                            <p><?= $name ?></p>
                            <?php foreach ($fields as $field) { ?>
                                <?php if ($value = $model->{$field}) { ?>
                                    <p class="hidden"><?=$model->getAttributeLabel($field); ?>: <?=$value;?></p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>