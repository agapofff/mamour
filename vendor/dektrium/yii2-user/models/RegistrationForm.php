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

use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationForm extends Model
{
    use ModuleTrait;
    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;
    
    
    public $first_name;
    public $last_name;
    public $name;
    public $phone;
    
    public $sms_code;
    
    public $referal;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // username rules
            'usernameTrim'     => ['username', 'trim'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernamePattern'  => ['username', 'match', 'pattern' => $user::$usernameRegexp],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('front', 'Этот логин уже занят')
            ],
            // email rules
            'emailTrim'     => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('front', 'Этот e-mail уже занят')
            ],
            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],
            
            // fisrt name rules
            'firstNameTrim'     => ['first_name', 'trim'],
            'firstNameLength'   => ['first_name', 'string', 'max' => 255],
            'firstNameRequired' => ['first_name', 'required'],
            
            // last name rules
            'lastNameTrim'     => ['last_name', 'trim'],
            'lastNameLength'   => ['last_name', 'string', 'max' => 255],
            // 'lastNameRequired' => ['last_name', 'required'],
            
            // phone 
            'phone' => ['phone', 'string'],
            'phoneUnique'   => [
                'phone',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('front', 'Этот номер телефона уже занят')
            ],
            
            // sms code
            'smsCode' => ['sms_code', 'string', 'min' => 4, 'max' => 4],
            'checkSmsCode' => ['sms_code', function ($attribute, $params) {
                if ($this->$attribute != Yii::$app->session->get('smsCode')) {
                    $this->addError($attribute, Yii::t('front', 'Неправильный код!'));
                }
            }],
            
            // referal
            'referal' => ['referal', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('front', 'E-mail'),
            'username' => Yii::t('front', 'Логин'),
            'password' => Yii::t('front', 'Пароль'),
            'first_name' => Yii::t('front', 'Имя'),
            'last_name' => Yii::t('front', 'Фамилия'),
            'phone' => Yii::t('front', 'Телефон'),
            'sms_code' => Yii::t('front', 'СМС-код'),
            'referal' => Yii::t('front', 'Реферал'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::createObject(User::className());
        $user->setScenario('register');
        $this->loadAttributes($user);

        if (!$user->register()) {
            return false;
        }

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'front',
                'Ваша учетная запись была успешно создана, и на Вашу электронную почту было отправлено сообщение с дальнейшими инструкциями'
            )
        );

        return true;
    }

    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }
}
