<?php
namespace dvizh\shop\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dvizh\shop\models\product\ProductQuery;
use yii\db\Query;

class Product extends \yii\db\ActiveRecord implements \dvizh\relations\interfaces\Torelate, \dvizh\cart\interfaces\CartElement
{
    const PRICE_TYPE = 'p';

    function behaviors()
    {
        return [
            'images' => [
                'class' => 'agapofff\gallery\behaviors\AttachImages',
                'mode' => 'gallery',
                'quality' => 80,
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'relations' => [
                'class' => 'dvizh\relations\behaviors\AttachRelations',
                'relatedModel' => 'dvizh\shop\models\Product',
                'inAttribute' => 'related_ids',
            ],
            'toCategory' => [
                'class' => 'voskobovich\behaviors\ManyToManyBehavior',
                'relations' => [
                    'category_ids' => 'categories',
                ],
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
            'filter' => [
                'class' => 'dvizh\filter\behaviors\AttachFilterValues',
            ],
            'field' => [
                'class' => 'dvizh\field\behaviors\AttachFields',
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%shop_product}}';
    }

    public static function Find()
    {
        $return = new ProductQuery(get_called_class());
        $return = $return->with('category');

        return $return;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['category_id', 'producer_id', 'sort', 'amount', 'available', 'active', 'new', 'promo', 'popular', 'sale',], 'integer'],
            [['name', 'text', 'code', 'sku', 'barcode', 'short_text', 'compound', 'howtouse'], 'string'],
            [['category_ids'], 'each', 'rule' => ['integer']],
            [['slug'], 'string', 'max' => 255],
            [['video'], 'safe'],
            // [['videoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'avi, mp4, mpeg, mov, webm'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => Yii::t('back', '?????????????? ??????????????????????????'),
            'sku'  => Yii::t('back', '??????????????'),
            'barcode' => Yii::t('back', '????????????????'),
            'category_id' => Yii::t('back', '??????????????????'),
            'producer_id' => Yii::t('back', '??????????'),
            'name' => Yii::t('back', '????????????????'),
            'amount' => Yii::t('back', '??????????????'),
            'text' => Yii::t('back', '???????????? ????????????????'),
            'short_text' => Yii::t('back', '?????????????? ????????????????'),
            'images' => Yii::t('back', '??????????????????????'),
            'available' => Yii::t('back', '????????????????'),
            'active' => Yii::t('back', '??????????????'),
            'new' => Yii::t('back', '??????????????'),
            'popular' => Yii::t('back', '????????????????????'),
            'promo' => Yii::t('back', '??????????'),
            'sale' => Yii::t('back', '????????????'),
            'sort' => Yii::t('back', '??????????????'),
            'slug' => Yii::t('back', '??????????'),
            'amount_in_stock' => Yii::t('back', '???????????????????? ???? ??????????????'),
            'video' => Yii::t('back', '??????????'),
            'category_ids' => Yii::t('back', '??????????????????'),
            'compound' => Yii::t('back', '????????????'),
            'howtouse' => Yii::t('back', '???????????????????????? ???? ??????????'),
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAmount($count)
	{
		$this->amount = $count;
		// $this->available = $count > 0 ? 1 : 0;

		$return = $this->save();

		if($return) {
			$prices = Price::find()->where(['item_id' => $this->id])->all();

			foreach($prices as $price) {
				if($return) {
					$price->amount = $count;
					// $price->available = $count > 0 ? 1 : 0;
                    $price->available = $this->available;

					$return = $price->save();
				} else {
					return $return;
				}
			}

			return $return;
		}

		return $return;
	}

    public function minusAmount($count, $moderator="false")
    {
        $this->amount = $this->amount-$count;
        $this->save(false);

        return $this;
    }

    public function plusAmount($count, $moderator="false")
    {
        $this->amount = $this->amount+$count;
        $this->save(false);

        return $this;
    }

    public function setPrice($price, $type = null)
    {
        if ($priceModel = $this->getPriceModel($type)) {
            $priceModel->price = $price;

            return $priceModel->save(false);
        } elseif($type) {
            // ?????????????? ?????????? ????????
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

        return null;
    }

    public function getPriceModel($typeId = null)
    {
        if (!$typeId && !$typeId = Yii::$app->getModule('shop')->defaultPriceTypeId){
            return null;
        }

        return $this->getPrices()->andWhere([
            'type_id' => $typeId
        ])->one();
    }

    public function getPrices()
    {
        return $this->hasMany(Price::className(), [
            'item_id' => 'id'
        ])->where([
            'type' => self::PRICE_TYPE
        ]);
    }
    
    public function getShopPrices()
    {
        return $this->hasMany(Price::className(), [
            'item_id' => 'id'
        ])->where([
            'price' => 'price'
        ]);
    }

    public function getUnderchargedPrices()
    {
        $underchargedPrices = [];
        foreach ($this->getPriceTypes() as $priceType) {
            $price = $this->getPrice($priceType->id);
            if(empty($price)) {
                array_push($underchargedPrices, $priceType);
            }
        }

        return $underchargedPrices;
    }

    public function getPriceTypes()
    {
        return PriceType::find()->all();
    }
    
    public function getPrice($type = null)
    {
        if($callable = Yii::$app->getModule('shop')->priceCallable) {
            return $callable($this);
        }

        if($price = $this->getPriceModel($type)) {
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
        return $this;
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
        $options = [];

        if($this->modifications) {
            $filters = $this->getAvailableOptions();
        } else {
            $filters = $this->getOptions();            
        }

        if ($filters) {
            foreach ($filters as $filter) {
                if ($variants = $filter->variants) {

                    $options[$filter->id]['name'] = $filter->name;
		            $options[$filter->id]['slug'] = $filter->slug;
                    foreach($variants as $variant) {
                        if(!$this->modifications | in_array($variant->id, $this->getOptionVariants($filter->id))) {
                            $options[$filter->id]['variants'][$variant->id] = $variant->value;
                        }
                    }
                }
            }
        }

        return $options;
        //return ['????????' => ['??????????????', '??????????', '??????????'], '????????????' => ['XXL']];
    }

    public function getOptionVariants($optionId)
    {
        $modifications = [];
        foreach ($this->modifications as $key => $modification)
        {
            if ($modification->store_id == Yii::$app->params['store_id'])
            {
                $modifications[$key] = $modification;
            }
        }

        $optionVariants = ArrayHelper::map(ModificationToOption::find()->where([
            'option_id' => $optionId,
            'modification_id' => ArrayHelper::map($modifications, 'id', 'id')])->all(),
            'variant_id',
            'variant_id'
        );
        
        return $optionVariants;
    }

    public function getAvailableOptions()
    {

        $modifications = [];
        foreach ($this->modifications as $key => $modification)
        {
            if ($modification->store_id == Yii::$app->params['store_id'])
            {
                $modifications[$key] = $modification;
            }
        }
        
        $optionIds = ArrayHelper::map(ModificationToOption::find()->where([
            'modification_id' => ArrayHelper::map($modifications, 'id', 'id')])->all(),
            'option_id',
            'option_id'
        );

        if (!$optionIds) {
            return [];
        }

        return $this->getOptionsByIds($optionIds);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSellModel()
    {
        return $this;
    }

    public function getModifications()
    {
        $return = $this->hasMany(Modification::className(), [
            'product_id' => 'id'
        ])->orderBy('id');

        return $return;
    }

    public function getLink()
    {
        return Url::toRoute([Yii::$app->getModule('shop')->productUrlPrefix, 'slug' => $this->slug]);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), [
            'id' => 'category_id'
        ]);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), [
            'id' => 'category_id'
        ])->viaTable('{{%shop_product_to_category}}', [
            'product_id' => 'id'
        ]);
    }

    public function getProducer()
    {
        return $this->hasOne(Producer::className(), [
            'id' => 'producer_id'
        ]);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        Modification::deleteAll([
            'product_id' => $this->id
        ]);

        Price::deleteAll([
            'item_id' => $this->id,
            'type' => self::PRICE_TYPE
        ]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(!empty($this->category_id) && !empty($this->id)) {
            Yii::$app->db->createCommand()->delete('{{%shop_product_to_category}}', ['product_id' => $this->id])->execute();
            // if(!(new \yii\db\Query())
            // ->select('*')
            // ->from('{{%shop_product_to_category}}')
            // ->where('product_id ='.$this->id.' AND category_id = '.$this->category_id)
            // ->all()) {
                Yii::$app->db->createCommand()->insert('{{%shop_product_to_category}}', [
                    'product_id' => $this->id,
                    'category_id' => $this->category_id,
                ])->execute();
            // }
        }
    }
    
    public static function getAllProductsPrices($productsIDs = null, $limit = 9999)
    {
        return (new Query())
            ->select([
                'product_id' => 'm.product_id',
                'price' => 'p.price',
                'price_old' => 'p.price_old',
            ])
            ->from([
                'm' => '{{%shop_product_modification}}',
                'p' => '{{%shop_price}}',
            ])
            ->where([
                'm.available' => 1,
                'm.store_id' => Yii::$app->params['store_id'],
            ])
            ->andWhere('m.id = p.item_id')
            ->andFilterWhere([
                'm.product_id' => $productsIDs
            ])
            ->groupBy([
                'product_id',
                'price',
                'price_old'
            ])
            ->limit($limit)
            ->all();
    }
    
    public static function getAllProductsSizes($productsIDs = null)
    {
        return (new Query())
            ->select([
                'product_id' => 'm.product_id',
                'id' => 'f.id',
                'value' => 'f.value',
            ])
            ->from([
                'm' => '{{%shop_product_modification}}',
                'o' => '{{%shop_product_modification_to_option}}',
                'f' => '{{%filter_variant}}'
            ])
            ->where([
                'm.available' => 1,
                'm.store_id' => Yii::$app->params['store_id'],
                'f.filter_id' => 2,
            ])
            ->andWhere('m.id = o.modification_id')
            ->andWhere('o.variant_id = f.id')
            ->andFilterWhere([
                'm.product_id' => $productsIDs
            ])
            ->all();
    }
    
}
