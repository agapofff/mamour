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
use dektrium\user\Mailer;
use yii\base\Model;

use yii;


/**
 * Model for collecting data on password recovery.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryForm extends Model
{
    const SCENARIO_REQUEST = 'request';
    const SCENARIO_RESET = 'reset';

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param Mailer $mailer
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Mailer $mailer, Finder $finder, $config = [])
    {
        $this->mailer = $mailer;
        $this->finder = $finder;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('front', 'E-mail'),
            'password' => Yii::t('front', 'Пароль'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_REQUEST => ['email'],
            self::SCENARIO_RESET => ['password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'max' => 72, 'min' => 6],
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return bool
     */
    public function sendRecoveryMessage()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->finder->findUserByEmail($this->email);

        if ($user instanceof User) {
            /** @var Token $token */
            $token = \Yii::createObject([
                'class' => Token::className(),
                'user_id' => $user->id,
                'type' => Token::TYPE_RECOVERY,
            ]);

            if (!$token->save(false)) {
                return false;
            }

            if (!$this->mailer->sendRecoveryMessage($user, $token)) {
                return false;
            }
        }

        \Yii::$app->session->setFlash(
            'info',
            Yii::t('front', 'На Ваш e-mail были отправлены инструкции по сбросу пароля для Вашей учетной записи')
        );

        return true;
    }

    /**
     * Resets user's password.
     *
     * @param Token $token
     *
     * @return bool
     */
    public function resetPassword(Token $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->password)) {
            Yii::$app->session->setFlash('success', Yii::t('front', 'Ваш пароль был успешно изменён'));
            $token->delete();
        } else {
            Yii::$app->session->setFlash(
                'danger',
                Yii::t('front', 'Во время смены пароля произошла ошибка. Пожалуйста, попробуйте снова чуть позже.')
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
