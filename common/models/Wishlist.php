<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wishlist}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property string $size
 *
 * @property ShopProduct $product
 * @property User $user
 */
class Wishlist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wishlist}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['product_id'], 'integer'],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', 'ID'),
            'user_id' => Yii::t('front', 'User ID'),
            'product_id' => Yii::t('front', 'Product ID'),
        ];
    }

}
