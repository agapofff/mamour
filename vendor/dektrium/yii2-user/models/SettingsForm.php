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

use dektrium\user\helpers\Password;
use dektrium\user\Mailer;
use dektrium\user\Module;
use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends Model
{
    use ModuleTrait;

    /** @var string */
    public $email;

    /** @var string */
    public $username;

    /** @var string */
    public $new_password;

    /** @var string */
    public $current_password;

    /** @var Mailer */
    protected $mailer;

    /** @var User */
    private $_user;
    
    public $name;
    public $first_name;
    public $last_name;
    public $birthday;
    public $phone;
    public $sex;
    public $comment;
    public $agree;
    public $sms_code;

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /** @inheritdoc */
    public function __construct(Mailer $mailer, $config = [])
    {
        $this->mailer = $mailer;
        $this->setAttributes([
            'username' => $this->user->username,
            'email'    => $this->user->unconfirmed_email ?: $this->user->email,
            'phone' => $this->user->phone,
            'first_name' => $this->user->first_name,
        ], false);
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'usernameTrim' => ['username', 'trim'],
            'usernameRequired' => ['username', 'required'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernamePattern' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            'emailTrim' => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailUsernameUnique' => [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => $this->module->modelMap['User']],
            'newPasswordLength' => ['new_password', 'string', 'max' => 72, 'min' => 6],
            'currentPasswordRequired' => ['current_password', 'required', 'when' => function ($model) {
                return $model->new_password != '';
            }, 'whenClient' => "function (attribute, value) {
                return $('#settings-form-new_password').val() !== '';
            }"],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, Yii::t('front', 'Текущий пароль неверен'));
                }
            }],
            'firstName' => ['first_name', 'string', 'max' => 255],
            'lastName' => ['last_name', 'string', 'max' => 255],
            'name' => ['name', 'string', 'max' => 255],
            // 'address' => ['address', 'string', 'max' => 255],
            'birthday' => ['birthday', 'string'],
            'sex' => ['sex', 'integer'],
            'comment' => ['comment', 'string', 'max' => 255],
            'agree' => ['agree', 'string'],
            'phone' => ['phone', 'string'],
            
            // sms code
            'smsCode' => ['sms_code', 'string', 'min' => 4, 'max' => 4],
            // 'smsCodeRequired' => ['sms_code', 'required', 'when' => function ($model) {
                // return $model->phone != $this->user->phone;
            // }, 'whenClient' => "function (attribute, value) {
                // return $('#settings-form-phone').val() !== '" . $this->user->phone . "';
            // }"],
            'checkSmsCode' => ['sms_code', function ($attribute, $params) {
                if ($this->$attribute != Yii::$app->session->get('smsCode')) {
                    $this->addError($attribute, Yii::t('front', 'Неправильный код!'));
                }
            }],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('front', 'E-mail'),
            'username' => Yii::t('front', 'Логин'),
            'new_password' => Yii::t('front', 'Новый пароль'),
            'current_password' => Yii::t('front', 'Текущий пароль'),
            'first_name' => Yii::t('front', 'Имя'),
            'last_name' => Yii::t('front', 'Фамилия'),
            'name' => Yii::t('front', 'Кличка'),
            'address' => Yii::t('front', 'Адрес'),
            'birthday' => Yii::t('front', 'Дата рождения'),
            'phone' => Yii::t('front', 'Телефон'),
            'sex' => Yii::t('front', 'Пол'),
            'comment' => Yii::t('front', 'Комментарий'),
            'agree' => Yii::t('front', 'Я хочу получать информационную рассылку'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            // $this->user->scenario = 'settings';
            $this->user->username = $this->username;
            $this->user->password = $this->new_password;
            $this->user->phone = $this->phone;
            $this->user->first_name = $this->first_name;
            
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }

            return $this->user->save();
        }

        return false;
    }

    /**
     * Changes user's email address to given without any confirmation.
     */
    protected function insecureEmailChange()
    {
        $this->user->email = $this->email;
        Yii::$app->session->setFlash('success', Yii::t('front', 'Ваш e-mail был изменён'));
    }

    /**
     * Sends a confirmation message to user's email address with link to confirm changing of email.
     */
    protected function defaultEmailChange()
    {
        $this->user->unconfirmed_email = $this->email;
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_NEW_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);
        Yii::$app->session->setFlash(
            'info',
            Yii::t('front', 'Сообщение с подтверждением было отправлено на Ваш e-mail')
        );
    }

    /**
     * Sends a confirmation message to both old and new email addresses with link to confirm changing of email.
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function secureEmailChange()
    {
        $this->defaultEmailChange();
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_OLD_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);

        // unset flags if they exist
        $this->user->flags &= ~User::NEW_EMAIL_CONFIRMED;
        $this->user->flags &= ~User::OLD_EMAIL_CONFIRMED;
        $this->user->save(false);

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'front',
                'Мы отправили сообщения с подтверждением на оба e-mail адреса: старый и новый.Вам необходимо перейти по ссылкам из обоих сообщений.'
            )
        );
    }
}
