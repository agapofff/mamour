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

<h1 style="text-align: center">
    <?= Yii::t('front', 'Благодарим Вас за регистрацию на сайте {0}', Yii::$app->id) ?>.
</h1>
<p style="text-align: center">
    <?= Yii::t('front', 'Чтобы завершить регистрацию, пожалуйста, пройдите по ссылке ниже') ?>.
</p>
<p style="text-align: center">
    <?= Html::a(Html::encode($token->url), $token->url) ?>
</p>
<p>
    <?= Yii::t('front', 'Если у Вас не получается перейти по ссылке, скопируйте её в адресную строку браузера') ?>.
</p>
<p>
    <?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
</p>
