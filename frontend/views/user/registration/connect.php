<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    /**
     * @var yii\web\View $this
     * @var yii\widgets\ActiveForm $form
     * @var dektrium\user\models\User $model
     * @var dektrium\user\models\Account $account
     */

    $this->title = Yii::t('user', 'Регистрация');
    $this->params['breadcrumbs'][] = $this->title;

?>

    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="alert alert-info">
                <p>
                    <?= Yii::t(
                        'user',
                        'Заполните следующие поля, чтобы завершить процесс регистрации'
                    ) ?>:
                </p>
            </div>

<?php
            $form = ActiveForm::begin([
                'id' => 'connect-account-form'
            ]); 
?>

                <?= $form->field($model, 'email')->input('email')->textInput([ 'class' => 'form-control mb-0', 'placeholder' => 'E-mail адрес' ])->label('E-mail:'); ?>

                <?= $form->field($model, 'username')->textInput([ 'class' => 'form-control mb-0', 'placeholder' => 'Имя пользователя' ])->label('Логин:'); ?>

                <div class="form-group mt-2 pt-2 text-center">
                    <?= Html::submitButton(Yii::t('user', 'Продолжить'), ['class' => 'btn btn-primary btn-lg btn-round mt-4 px-5 py-4']) ?>
                </div>

<?php
            ActiveForm::end();
?>

            <p class="text-center mt-5">
                <?= Html::a(
                    Yii::t(
                        'user',
                        'Уже зарегистрированы? Авторизуйтесь!'
                    ),
                    ['/user/settings/networks']
                ) ?>
            </p>
        </div>
    </div>