<?php
namespace dvizh\promocode\behaviors;

use yii;
use yii\base\Behavior;

class PromoCodeUse extends Behavior
{
    public function events()
    {
        return [
            'create' => 'usePromoCode'
        ];
    }
    public function usePromoCode($event)
    {
        if ($event->model->promocode){
            $promocode = Yii::$app->promocode->checkExists($event->model->promocode);
            Yii::$app->promocode->setPromoCodeUse($promocode,$event->model->id,$event->model->base_cost);
            if ($promocode->type == 'cumulative') {
                Yii::$app->promocode->checkPromoCodeCumulativeStatus($promocode->id);
            }
        }

    }
}