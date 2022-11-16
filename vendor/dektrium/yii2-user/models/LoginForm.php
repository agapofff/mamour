<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dektrium\user\models;

use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\traits\ModuleTrait;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\base\Model;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends Model
{
    use ModuleTrait;

    /** @var string User's email or username */
    public $login;

    /** @var string User's plain password */
    public $password;
    
    public $type = 'email';
    public $phone;
    public $sms_code;

    /** @var string Whether to remember the user */
    public $rememberMe = false;

    /** @var \dektrium\user\models\User */
    protected $user;

    /** @var Finder */
    protected $finder;

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($config);
    }

    /**
     * Gets all users to generate the dropdown list when in debug mode.
     *
     * @return array
     */
    public static function loginList()
    {
        /** @var \dektrium\user\Module $module */
        $module = \Yii::$app->getModule('user');

        $userModel = $module->modelMap['User'];

        return ArrayHelper::map($userModel::find()->where(['blocked_at' => null])->all(), 'username', function ($user) {
            return sprintf('%s (%s)', Html::encode($user->username), Html::encode($user->email));
        });
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('front', 'Логин'),
            'password'   => Yii::t('front', 'Пароль'),
            'rememberMe' => Yii::t('front', 'Запомнить меня'),
            'phone' => Yii::t('front', 'Телефон'),
            'sms_code' => Yii::t('front', 'СМС-код'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            [['login', 'password', 'phone', 'sms_code', 'type'], 'safe'],
            [['login', 'password', 'phone', 'sms_code', 'type'], 'trim'],
            [['rememberMe'], 'boolean'],
            
            [['login', 'password'], 'required', 'when' => function ($model) {
                return $model->type == 'email';
            }],
            
            [['phone'], 'required', 'when' => function ($model) {
                return $model->type == 'phone';
            }],
            
            'confirmationValidate' => ['login', function ($attribute) {
                if ($this->user !== null) {
                    $confirmationRequired = $this->module->enableConfirmation
                        && !$this->module->enableUnconfirmedLogin;
                    if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                        $this->addError($attribute, Yii::t('front', 'Вам необходимо подтвердить свой e-mail адрес'));
                    }
                    if ($this->user->getIsBlocked()) {
                        $this->addError($attribute, Yii::t('front', 'Ваш аккаунт был заблокирован'));
                    }
                }
            }, 'when' => function ($model) {
                return $model->type == 'email';
            }],
            
            'passwordValidate' => ['password', function ($attribute) {
                if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                    $this->addError($attribute, Yii::t('front', 'Неверный логин или пароль'));
                }
            }, 'when' => function ($model) {
                return $model->type == 'email';
            }],
            
            'smsCode' => ['sms_code', 'string', 'min' => 4, 'max' => 4, 'when' => function ($model) {
                return $model->type == 'phone';
            }],
            'checkSmsCode' => ['sms_code', function ($attribute, $params) {
                if ($this->$attribute != Yii::$app->session->get('smsCode')) {
                    $this->addError($attribute, Yii::t('front', 'Неправильный код!'));
                }
            }, 'when' => function ($model) {
                return $model->type == 'phone';
            }],
        ];

        return $rules;
    }

    /**
     * Validates if the hash of the given password is identical to the saved hash in the database.
     * It will always succeed if the module is in DEBUG mode.
     *
     * @return void
     */
    public function validatePassword($attribute, $params)
    {
      if ($this->user === null || !Password::validate($this->password, $this->user->password_hash))
        $this->addError($attribute, Yii::t('front', 'Неверный логин или пароль'));
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->user) {
            $isLogged = Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);

            if ($isLogged) {
                $this->user->updateAttributes(['last_login_at' => time()]);
            }

            return $isLogged;
        }

        return false;
    }


    /** @inheritdoc */
    public function formName()
    {
        return 'login-form';
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = $this->phone ? $this->finder->findUserByPhone(trim($this->phone)) : $this->finder->findUserByUsernameOrEmail(trim($this->login));

            return true;
        } else {
            return false;
        }
    }
}
