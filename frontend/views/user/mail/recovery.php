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

/**
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Token $token
 */
?>
<p>
    <?= Yii::t('front', 'Здравствуйте') ?>!
</p>
<p>
    <?= Yii::t('front', 'Мы получили запрос на смену пароля для Вашей учётной записи на сайте {0}', Yii::$app->id) ?>.
</p>
<p>
    <?= Yii::t('front', 'Для завершения операции, пожалуйста, перейдите по ссылке ниже') ?>.
</p>
<br>
<p style="text-align: center; ">
    <?= Html::a(Yii::t('front', 'Восстановить пароль'), $token->url, [
            'style' => '
                display: inline-block;
                padding: 24px 60px;
                color: #ffffff !important;
                background-color: #474F73;
                border: 1px solid #474F73;
                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                border-radius: 50px;
                font-size: 24px;
                font-weight: 400;
                text-align: center;
                text-decoration: none !important;
            ',
        ]);
    ?>
</p>
<br>
<p>
    <?= Yii::t('front', 'Если у Вас не получается перейти по ссылке, скопируйте её в адресную строку браузера') ?>.
</p>
<p>
    <?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
</p>
