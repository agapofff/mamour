<?php
namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use dvizh\cart\interfaces\CartElement;

class Modification extends ActiveRecord implements CartElement
{
    const PRICE_TYPE = 'm';
    
    public $saveAndExit;

    function behaviors()
    {
        return [
            'images' => [
                'class' => 'dvizh\gallery\behaviors\AttachImages',
                'mode' => 'gallery',
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'relations' => [
                'class' => 'dvizh\relations\behaviors\AttachRelations',
                'relatedModel' => 'dvizh\shop\models\Product',
                'inAttribute' => 'related_ids',
            ],
            // 'seo' => [
                // 'class' => 'dvizh\seo\behaviors\SeoFields',
            // ],
            'time' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%shop_product_modification}}';
    }
    
    public function rules()
    {
        return [
            [['name', 'product_id', 'sku'], 'required'],
            [['sort', 'amount', 'product_id', 'store_type', 'saveAndExit', 'available'], 'integer'],
            [['name', 'code', 'create_time', 'update_time', 'filter_values', 'sku', 'barcode', 'lang'], 'string'],
            [['name'], 'string', 'max' => 55],
            [['slug'], 'string', 'max' => 88]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'product_id' => Yii::t('back', 'Товар'),
            'name' => Yii::t('back', 'Название'),
            'code' => Yii::t('back', 'Vendor Code'),
            'sku'  => Yii::t('back', 'ID товара'),
            'barcode' => Yii::t('back', 'Штрихкод'),
            'images' => Yii::t('back', 'Изображения'),
            'available' => Yii::t('back', 'Наличие'),
            'sort' => Yii::t('back', 'Порядок'),
            'slug' => Yii::t('back', 'Алиас'),
            'amount' => Yii::t('back', 'Наличие'),
            'create_time' => Yii::t('back', 'Дата создания'),
            'update_time' => Yii::t('back', 'Дата обновления'),
            'filter_values' => Yii::t('back', 'Сочетание значений фильтров'),
            'store_type' => Yii::t('back', 'Тип магазина'),
            'lang' => Yii::t('back', 'Язык'),
        ];
    }

    public function getFiltervariants()
    {
        $variants = ModificationToOption::find()
            ->where([
                'modification_id' => $this->id
            ])
            ->all();
        return ArrayHelper::map($variants, 'variant_id', 'variant_id');
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function minusAmount($count)
    {
        $this->amount = $this->amount - $count;
        
        return $this->save(false);
    }
    
    public function plusAmount($count)
    {
        $this->amount = $this->amount + $count;
        return $this->save(false);
    }

    public function setOldPrice($price, $type = null)
    {
        if ($priceModel = $this->getPriceModel($type)) {
            $priceModel->price_old = $price;
            return $priceModel->save(false);
        }

        return false;
    }

    public function setPrice($price, $type = null)
    {
        if ($priceModel = $this->getPriceModel($type)) {
            $priceModel->price = $price;
            return $priceModel->save(false);
        } else {
            if ($typeModel = PriceType::findOne($type)) {
                $priceModel = new Price;
                $priceModel->item_id = $this->id;
                $priceModel->price = $price;
                $priceModel->type_id = $type;
                $priceModel->type = self::PRICE_TYPE;
                $priceModel->name = $typeModel->name;

                return $priceModel->save();
            }
        }

        return false;
    }

    public function getPriceModel($typeId = null)
    {
        if (!$typeId && !$typeId = Yii::$app->getModule('shop')->defaultPriceTypeId) {
            return null;
        }

        return $this->getPrices()->andWhere(['type_id' => $typeId])->one();
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['item_id' => 'id'])->where(['type' => self::PRICE_TYPE]);
    }

    public function getPrice($type = null)
    {
        if ($callable = Yii::$app->getModule('shop')->priceCallable) {
            return $callable($this);
        }

        if ($price = $this->getPriceModel($type)) {
            return $price->price;
        }

        return null;
    }

    public function getOldprice($type = null)
    {
        if ($price = $this->getPriceModel($type)) {
            return $price->price_old;
        }

        return null;
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getCartId()
    {
        return $this->id;
    }
    
    public function getCartName()
    {
        return $this->name;
    }
    
    public function getCartPrice()
    {
        return $this->price;
    }

    public function getCartOptions()
    {
        return '';
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSellModel()
    {
        return $this;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        ModificationToOption::deleteAll(['modification_id' => $this->id]);
        Price::deleteAll(["item_id" => $this->id, 'type' => self::PRICE_TYPE]);
    }

    public static function editField($id, $name, $value) 
    {
        $setting = Modification::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }
}
