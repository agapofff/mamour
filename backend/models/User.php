<?php

namespace backend\models;

use Yii;

class User extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at', 'status', 'sex', 'agree', 'lottery'], 'integer'],
            [['birthday'], 'safe'],
            [['comment'], 'string'],
            [['username', 'email', 'unconfirmed_email', 'password_reset_token', 'first_name', 'last_name', 'phone'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'username' => Yii::t('back', 'Username'),
            'email' => Yii::t('back', 'Email'),
            'password_hash' => Yii::t('back', 'Password Hash'),
            'auth_key' => Yii::t('back', 'Auth Key'),
            'confirmed_at' => Yii::t('back', 'Confirmed At'),
            'unconfirmed_email' => Yii::t('back', 'Unconfirmed Email'),
            'blocked_at' => Yii::t('back', 'Blocked At'),
            'registration_ip' => Yii::t('back', 'Registration Ip'),
            'created_at' => Yii::t('back', 'Created At'),
            'updated_at' => Yii::t('back', 'Updated At'),
            'flags' => Yii::t('back', 'Flags'),
            'last_login_at' => Yii::t('back', 'Last Login At'),
            'password_reset_token' => Yii::t('back', 'Password Reset Token'),
            'status' => Yii::t('back', 'Status'),
            'first_name' => Yii::t('back', 'First Name'),
            'last_name' => Yii::t('back', 'Last Name'),
            'phone' => Yii::t('back', 'Phone'),
            'birthday' => Yii::t('back', 'Birthday'),
            'sex' => Yii::t('back', 'Sex'),
            'comment' => Yii::t('back', 'Comment'),
            'agree' => Yii::t('back', 'Agree'),
            'lottery' => Yii::t('back', 'Lottery'),
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }
}
