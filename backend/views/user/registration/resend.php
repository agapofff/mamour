<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\alert\AlertBlock;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\ResendForm $model
 */

$this->title = Yii::t('user', 'Request new confirmation message');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= AlertBlock::widget([
        'type' => 'growl',
        'useSessionFlash' => true,
        'delay' => 1,
    ]);
?>

<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <br>
        <?php $form = ActiveForm::begin([
            'id' => 'resend-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="text-center">
            <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
        <br>

        <?php ActiveForm::end(); ?>
    </div>
</div>
