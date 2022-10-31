<?php
namespace dvizh\promocode\behaviors;

use yii;
use yii\base\Behavior;
use dvizh\cart\Cart;

class Discount extends Behavior
{
    public $eventName = 'cart_cost';
    
    public function events()
    {
        $eventName = $this->eventName;

        return [
            $eventName => 'doDiscount'
        ];
    }

    public function doDiscount($event)
    {
        if(Yii::$app->promocode->has()) {
            
            if (!Yii::$app->promocode->get()->promocode->getTransactions()->all() && Yii::$app->promocode->get()->promocode->type == 'cumulative') {
                $discount = 0;
            } else {
                $discount = Yii::$app->promocode->get()->promocode->discount;
            }
            
            if (Yii::$app->promocode->get()->promocode->type == 'percent' || Yii::$app->promocode->get()->promocode->type == 'cumulative' || empty(Yii::$app->promocode->get()->promocode->type)) {
                if($discount > 0 && $discount <= 100 && $event->cost > 0) {
                    $event->cost = $event->cost-($event->cost*$discount)/100;
                }                
            } else {
                if($discount > 0 && $event->cost > 0) {
                    if  ($event->cost < $discount) {
                        $event->cost = $event->cost-($event->cost*100)/100;
                    } else {
                        $event->cost = $event->cost-$discount;
                    }
                }
            }
        }

        return $this;
    }
}
