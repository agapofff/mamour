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
 * @var dektrium\user\Module $module
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Token $token
 * @var bool $showPassword
 */
?>
<p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('front', 'Здравствуйте') ?>,
</p>

<p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('front', 'Ваша учётная запись на сайте {0} успешно создана', Yii::$app->name) ?>.
    <?php if ($showPassword || $module->enableGeneratingPassword): ?>
        <?= Yii::t('front', 'Автоматически сгенерированный пароль для Вашей учётной записи') ?>: <strong><?= $user->password ?></strong>
    <?php endif ?>

</p>

<?php if ($token !== null): ?>
    <p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Yii::t('front', 'Чтобы завершить регистрацию, пожалуйста, пройдите по ссылке ниже') ?>.
    </p>
    <p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Html::a(Html::encode($token->url), $token->url); ?>
    </p>
    <p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Yii::t('front', 'Если у Вас не получается перейти по ссылке, скопируйте её в адресную строку браузера') ?>.
    </p>
<?php endif ?>

<p style="font-family: 'Montserrat', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('front', 'Если Вы не отправляли этот запрос, просто проигнорируйте это письмо') ?>.
</p>
