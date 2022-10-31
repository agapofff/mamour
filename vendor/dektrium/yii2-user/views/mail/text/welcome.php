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

<?= Yii::t('front', 'Ваша учётная запись на сайте {0} успешно создана', Yii::$app->name) ?>.
<?php if ($module->enableGeneratingPassword): ?>
<?= Yii::t('front', 'Автоматически сгенерированный пароль для Вашей учётной записи') ?>:
<?= $user->password ?>
<?php endif ?>

<?php if ($token !== null): ?>
<?= Yii::t('front', 'Чтобы завершить регистрацию, пожалуйста, пройдите по ссылке ниже') ?>.

<?= $token->url ?>

<?= Yii::t('front', 'Если у Вас не получается перейти по ссылке, скопируйте её в адресную строку браузера') ?>.
<?php endif ?>

<?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
