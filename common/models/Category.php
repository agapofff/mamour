<?php
namespace common\models;

use Yii;
use dvizh\shop\models\category\CategoryQuery;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    
    public $saveAndExit = 0;
    
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'dvizh\gallery\behaviors\AttachImages',
                'mode' => 'gallery',
                'quality' => 80,
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'ensureUnique' => true,
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
            'field' => [
                'class' => 'dvizh\field\behaviors\AttachFields',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%shop_category}}';
    }
    
    static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public function rules()
    {
        return [
            [['parent_id', 'sort', 'saveAndExit', 'active'], 'integer'],
            [['name', 'slug'], 'required'],
            [['text', 'code'], 'string'],
            [['code', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => Yii::t('back', 'Родительская категория'),
            'name' => Yii::t('back', 'Имя категории'),
            'slug' => Yii::t('back', 'Алиас'),
            'text' => Yii::t('back', 'Описание'),
            'image' => Yii::t('back', 'Изображение'),
            'sort' => Yii::t('back', 'Сортировка'),
            'description' => Yii::t('back', 'Описание'),
            'active' => Yii::t('back', 'Активно'),
        ];
    }

    private static $categoriesByParent = null;

    public static function buildTree($parent_id = null)
    {
        $return = [];
        
        if (empty($parent_id)) {
            $categories = Category::find()->where('parent_id = 0 OR parent_id is null')->orderBy('sort ASC')->asArray()->all();
        } else {
            $categories = Category::find()->where(['parent_id' => $parent_id])->orderBy('sort ASC')->asArray()->all();
        }
        
        foreach ($categories as $level1) {
            $return[$level1['id']] = $level1;
            $return[$level1['id']]['childs'] = self::buildTree($level1['id']);
        }
        
        return $return;
    }
    
    public static function buildChain($id)
    {
        $return = [];
        
        $category = Category::find()->where(['id' => $id])->one();
        if ($category->parent_id){
            
        } else {
            
        }
        
        return $return;
    }

    public static function buildTextTree($id = null, $level = 1, $ban = [])
    {
        $return = [];
        $categories = null;
        $groupedCategories = null;
        $prefix = str_repeat('--', $level);
        $level++;

        if (empty($id)) {
            $categories = Category::find()
                ->select([
                    'id',
                    'parent_id',
                    'name'
                ])
                ->orderBy('sort DESC')
                ->asArray()
                ->all();

            foreach ($categories as $key => $category) {
                $category['name'] = json_decode($category['name'])->{Yii::$app->language};
                $groupedCategories[$category['parent_id']][] = $category;
            }

            self::$categoriesByParent = $groupedCategories;
            $categories = $groupedCategories[''];

        } else {
            if (isset(self::$categoriesByParent[$id])) {
                $categories = self::$categoriesByParent[$id];
            }
        }

        if (is_null($categories)) {
            return $return;
        }

        foreach ($categories as $category) {
            if (!in_array($category['id'], $ban)) {
                $return[$category['id']] = "$prefix {$category['name']}";
                $return = $return + self::buildTextTree($category['id'], $level, $ban);
            }
        }

        return $return;
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
             ->viaTable('{{%shop_product_to_category}}', ['category_id' => 'id'])->available();
    }
    
    public function getChilds()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }
    
    public function getLink()
    {
        return Url::toRoute([Yii::$app->getModule('shop')->categoryUrlPrefix, 'slug' => $this->slug]);
    }
}
