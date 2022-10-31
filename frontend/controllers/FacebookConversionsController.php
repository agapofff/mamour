<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use linslin\yii2\curl;

class FacebookConversionsController extends \yii\web\Controller
{
    
    public function actionIndex($data, $test = true)
    {
        $data = json_decode($data);
        
        $contents = [];
        
        foreach ($data->contents as $content) {
            $contents[] = json_encode([
                'id' => $content->id,
                'quantity' => $content->quantity,
                'item_price' => $content->price
            ]);
        }
        
        $params = [
            'access_token' => Yii::$app->params['facebookAccessToken'],
            'data' => [
                json_encode([
                    'event_name' => $data->event_name,
                    'event_time' => time(),
                    'action_source' => 'website',
                    'user_data' => [
                        'external_id' => [
                            hash('sha256', Yii::$app->session->getId())
                        ]
                    ],
                    'custom_data' => [
                        'currency' => $data->currency,
                        'value' => $data->value,
                        'contents' => $contents,
                        'content_type' => $data->content_type,
                        'name' => $data->name,
                        'variant' => $data->variant,
                    ],
                ]),
            ],
        ];
        
        if ($test) {
            $params['test_event_code'] = Yii::$app->params['test_event_code'];
        }
        
        $curl = new curl\Curl();
        $response = $curl->setPostParams($params)
            ->post('https://graph.facebook.com/v12.0/' . Yii::$app->params['facebookPixelID'] . '/events');
        
        return $response;
    }
    
}