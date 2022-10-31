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
 * @var dektrium\user\Module          $module
 * @var dektrium\user\models\User     $user
 * @var dektrium\user\models\Password $password
 */

?>
<p>
    <?= Yii::t('front', 'Здравствуйте') ?>!
</p>

<p>
    <?= Yii::t('front', 'Для Вашей учётной записи на сайте {0} был изменён пароль', Yii::$app->id) ?>.
    <?= Yii::t('front', 'Автоматически сгенерированный пароль для Вашей учётной записи') ?>: <strong><?= $user->password ?></strong>
</p>

<p>
    <?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
</p>
