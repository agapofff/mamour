<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// use yii\bootstrap\Alert;

/**
 * @var dektrium\user\Module $module
 */
?>

<?php 
    if ($module->enableFlashMessages) {
        foreach (Yii::$app->session->getAllFlashes() as $type => $message)
        {
            if (in_array($type, ['success', 'danger', 'warning', 'info'])) {
?>
                <div class="text-center">
                    <p class="lead"><?= $message ?></p>
                </div>
<?php
            }
        }
    }
?>
