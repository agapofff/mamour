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
 * @var dektrium\user\models\Token $token
 */
?>
<p>
    <?= Yii::t('front', 'Здравствуйте') ?>!
</p>
<p>
    <?= Yii::t(
        'front',
        'Мы получили запрос на смену e-mail адреса для Вашей учётной записи на сайте {0}',
        Yii::$app->id
    ) ?>.
    <?= Yii::t('front', 'Для завершения операции, пожалуйста, перейдите по ссылке ниже') ?>.
</p>
<p>
    <?= Html::a(Html::encode($token->getUrl()), $token->getUrl()); ?>
</p>
<p>
    <?= Yii::t('front', 'Если у Вас не получается перейти по ссылке, скопируйте её в адресную строку браузера') ?>.
</p>
<p>
    <?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
</p>
