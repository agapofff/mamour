<?php
    namespace dvizh\order\behaviors;

    use Yii;
    use dvizh\order\models\Element;
    use dvizh\order\models\FieldValue;
    use dektrium\user\models\User;


    class OrderFilling extends \yii\base\Behavior
    {
        public function events()
        {
            return [
                'create' => 'putElements'
            ];
        }

        public function putElements($event)
        {
            $order = $event->model;

            foreach(Yii::$app->cart->elements as $element) {
                $elementModel = new Element;

                $elementModel->setOrderId($order->id);
                $elementModel->setAssigment($order->is_assigment);
                $elementModel->setModelName($element->getModelName());
                $elementModel->setName($element->getName());
                $elementModel->setItemId($element->getItemId());
                $elementModel->setCount($element->getCount());
                $elementModel->setBasePrice($element->getPrice(false));
                $elementModel->setPrice($element->getPrice());
                $elementModel->setOptions(json_encode($element->getOptions()));
                $elementModel->setDescription($element->getComment());
                $elementModel->saveData();
            }

            $order->base_cost = 0;
            $order->cost = 0;

            foreach($order->elements as $element) {
                $order->base_cost += ($element->base_price*$element->count);
                $order->cost += ($element->price*$element->count);
            }

            $delivery_cost = FieldValue::find()
                ->where([
                    'order_id' => $order->id,
                    'field_id' => 11
                ])
                ->one(); 
            
            $order->cost += $delivery_cost->value;
            
            if (Yii::$app->user->isGuest){
                $user = User::findOne([
                    'email' => $order->email
                ]);
                if ($user && $user->id){
                    $order->user_id = $user->id;
                } else {
                    $user = new User();
                    $user->email = $order->email;
                    $user->username = $order->email;
                    $user->create();
                    $order->user_id = $user->id;
                }
            }
            
            $order->save();

            // Yii::$app->cart->truncate();
        }
    }