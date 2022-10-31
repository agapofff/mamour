<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Bonus;
use dektrium\user\models\User;
use yii\helpers\Url;
use yii\helpers\Html;

class BonusController extends Controller
{
    public function actionIndex ($layout = 'blank')
    {
        $bonuses = Bonus::find()
            ->where([
                'active' => 1,
                'user_id' => Yii::$app->user->id,
            ])
            ->orderBy([
                'created_at' => SORT_DESC
            ])
            ->all();
            
        return $this->render('index', [
            'bonuses' => $bonuses
        ]);
    }
    
    public function actionGift(int $user, int $sum)
    {
        $userBonus = Bonus::getUserBonus(Yii::$app->user->id);
        
        if ($userBonus['total'] < $sum) {
            return $this->asJson([
                'status' => 'error',
                'message' => Yii::t('front', 'На Вашем счету недостаточно UME для проведения этой операции')
            ]);
        }
        
        $friend = User::findOne($user);
        
        $friendName = Yii::$app->user->identity->name ?: (Yii::$app->user->identity->first_name ?: Yii::$app->identity->username);
        
        $removeBonus = new Bonus();
        $removeBonus->attributes = [
            'active' => 1,
            'user_id' => Yii::$app->user->id,
            'type' => 0,
            'amount' => $sum,
            'reason' => 2,
            'description' => (string) $user,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $addBonus = new Bonus();
        $addBonus->attributes = [
            'active' => 1,
            'user_id' => $user,
            'type' => 1,
            'amount' => $sum,
            'reason' => 2,
            'description' => (string) Yii::$app->user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($removeBonus->save() && $addBonus->save()) {
            $html = Html::tag('h1', Yii::t('front', 'Поздравляем!'), [
                        'style' => '
                            text-align: center;
                        ',
                    ]) . 
                    Html::tag('p', Yii::t('front', 'Вы получили в подарок <b>{0} UME</b> от пользователя <b>{1}</b>', [
                        $sum,
                        $friendName
                    ]), [
                        'style' => '
                            text-align: center;
                        ',
                    ]) . 
                    Html::tag('br') . 
                    Html::tag('div', Html::a(Yii::t('front', 'Потратить бонусы'), Url::home(true), [
                        'style' => '
                            display: inline-block;
                            padding: 24px 60px;
                            color: #ffffff !important;
                            background-color: #474F73;
                            border: 1px solid #474F73;
                            -webkit-border-radius: 50%;
                            -moz-border-radius: 50%;
                            border-radius: 50px;
                            font-size: 24px;
                            font-weight: 400;
                            text-align: center;
                            text-decoration: none !important;
                        ',
                        ]), [
                            'style' => '
                                text-align: center;
                            '
                    ]);
                        
            $mail = Yii::$app->mailer
                ->compose('default', [
                    'content' => $html,
                ])
                ->setFrom([
                    Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']
                ])
                ->setTo($friend->email)
                // ->setTo('agapofff@gmail.com')
                ->setReplyTo(Yii::$app->params['senderEmail'])
                ->setSubject(Yii::t('front', '+{0} UME от {1}', [
                    $sum,
                    $friendName
                ]))
                ->send();
            
            return $this->asJson([
                'status' => 'success',
                'message' => Yii::t('front', 'Ваш подарок успешно отправлен')
            ]);
        } else {
            return $this->asJson([
                'status' => 'error',
                'message' => Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже'),
                'error' => [
                    $removeBonus->getErrors(),
                    $addBonus->getErrors(),
                ]
            ]);
        }
    }
    
    public function actionAdd($user_id, $amount, $reason, $description = null)
    {
        return $this->setBonus(1, $user_id, $amount, $reason, $description);
    }
    
    public function actionRemove($user_id, $amount, $reason, $description = null)
    {
        return $this->setBonus(0, $user_id, $amount, $reason, $description);
    }

    public function setBonus($type, $user_id, $amount, $reason, $description = null)
    {
        $model = new Bonus();
        $model->attributes = [
            'active' => 1,
            'user_id' => $user_id,
            'type' => $type,
            'amount' => $amount,
            'reason' => $reason,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($model->save()) {
            return true;
        } else {
            return $model->getErrors();
        }
    }
    
}
