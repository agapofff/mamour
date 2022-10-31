<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\VarDumper;
use linslin\yii2\curl\Curl;

class SmsController extends Controller
{
    public function actionSend($phone, $text)
    {
        $curl = new Curl();
        
        $response = $curl
            ->setGetParams([
                'api_id' => Yii::$app->params['sms']['apiKey'],
                'to' => preg_replace('/[^0-9]/', '', $phone),
                'msg' => $text,
            ])
            ->get(Yii::$app->params['sms']['host']);

        return $response;
    }
    
    public function actionGetCode($phone)
    {
        $smsCode = rand(1000, 9999);
        Yii::$app->session->set('smsCode', $smsCode);

        $this->actionSend($phone, Yii::t('front', 'Ваш код подтверждения') . ': ' . $smsCode);
        return $smsCode;
    }
    
    public function actionCheckCode($code)
    {
        return $code == Yii::$app->session->get('smsCode');
    }
}
