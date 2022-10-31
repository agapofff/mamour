<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">' . Yii::$app->id . '</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-fixed-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
    <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <?php // echo klisl\languages\widgets\ListWidget::widget() ?>
                </li>
                <li class="user user-menu">
                    <?= Html::a(
                        Html::img('@web/images/user.png', [
                            'class' => 'user-image img-circle'
                        ])
                        .
                        Html::tag('span', Yii::$app->user->identity->username, [
                            'class' => 'hidden-xs',
                        ]),
                        ['/user/settings/account']
                    )?>
                </li>
                <li>
                    <?= Html::a(
                            Html::tag('span', '', [
                                'class' => 'fa fa-sign-out'
                            ]),
                            ['/site/logout'],
                            [
                                'data-method' => 'post',
                                'title' => Yii::t('back', 'Выход'),
                                'class' => 'btn btn-link btn-lg',
                            ]
                        ) ?>
                </li>
            </ul>
        </div>
    <?php } ?>
    </nav>
</header>
