<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var dektrium\user\models\User
 */
?>
<?= Yii::t('front', 'Здравствуйте') ?>,

<?= Yii::t('front', 'Для Вашей учётной записи на сайте {0} был изменён пароль', Yii::$app->name) ?>.

<?= Yii::t('front', 'Автоматически сгенерированный пароль для Вашей учётной записи') ?>:
<?= $user->password ?>

<?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
