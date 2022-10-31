<?php
    use yii\web\View;
    use yii\helpers\Html;

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\Module $module
 */

$this->title = $title;

?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
            <?= $this->render('/_alert', [
                    'module' => $module
                ]);
            ?>
            <?php
                // if ($redirect) {
                    // echo Html::tag('p', Yii::t('front', 'Сейчас мы переадресуем Вас на страницу авторизации'), [
                        // 'class' => 'text-center'
                    // ]);
                    // echo Html::tag('p', $redirect);
                    // $this->registerJs("
                        // setTimeout(function(){
                            // location.href = '" . $redirect . "';
                        // }, 3000);
                    // ", View::POS_READY);
                // }
            ?>
        </div>
    </div>
</div>
