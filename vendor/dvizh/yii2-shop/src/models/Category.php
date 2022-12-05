<?php
namespace dvizh\shop\models;

use Yii;
use dvizh\shop\models\category\CategoryQuery;
use yii\helpers\Url;

class Category extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'agapofff\gallery\behaviors\AttachImages',
                'mode' => 'single',
                'quality' => 80,
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'ensureUnique' => false,
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
            [['parent_id', 'sort', 'active'], 'integer'],
            [['name'], 'required'],
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
    
    public static function getCategoryBySlug($categories, $slug)
    {
        foreach ($categories as $category) {
            
        }
    }

    public static function buildTree($parent_id = null)
    {
        $return = [];
        
        if(empty($parent_id)) {
            $categories = Category::find()->where('parent_id = 0 OR parent_id is null')->orderBy('sort ASC')->asArray()->all();
        } else {
            $categories = Category::find()->where(['parent_id' => $parent_id])->orderBy('sort ASC')->asArray()->all();
        }
        
        foreach($categories as $level1) {
            $return[$level1['id']] = $level1;
            $return[$level1['id']]['childs'] = self::buildTree($level1['id']);
        }
        
        return $return;
    }
    
    public static function getByPath($categories, $path, $i = 0, $parent_id = null)
    {
        $slugs = explode('/', $path);
        if ($i == count($slugs)) {
            return $parent_id;
        } else {
            foreach ($categories as $category) {
                if ($category['slug'] == $slugs[$i] && $category['parent_id'] == $parent_id) {
                    return self::getByPath($categories, $path, $i+1, $category['id']);
                    break;
                }
            }
        }
    }
    
    public static function getAllChilds($categories, $id, $type = false, $self = false, $childs = [], $i = 0)
    {
        if ($self && !$i) {
            $childs[] = $id;
        }
        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $childs[] = !$type ? $category : $category[$type];
                $childs = self::getAllChilds($categories, $category['id'], $type, $self, $childs, $i++);
            }
        }
        return $childs;
    }
    
    
    public static function getAllParents($categories, $id, $type = false, $self = false, $parents = [], $i = 0)
    {
        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                if ($self || (!$self && $i)) {
                    $parents[] = !$type ? $category : $category[$type];
                }
                $parents = self::getAllParents($categories, $category['parent_id'], $type, $self, $parents, $i++);
                break;
            }
        }
        return $parents;
    }
    
    public static function buildTreeArray($data, $rootID = 0)
    {
        $tree = [];
        foreach ($data as $id => $node) {
            if ($node['parent_id'] == $rootID) {
                unset($data[$id]);
                $node['childs'] = self::buildTreeArray($data, $node['id']);
                $tree[] = $node;
            }
        }
        return $tree;
    }
    
    public static function buildSelectTree($data, $level = 0, $separator = '••••')
    {
        foreach ($data as $key => $item) {
            $string = '';
            for ($i = 0; $i < $level; $i++) {
                $string .= $separator;
            }
            $data[$key]['name'] = $string . $item['name'];
            $data[$key]['parent_id'] = $item['parent_id'];
            if (isset($item['childs'])) {
                $data[$key]['childs'] = self::buildSelectTree($item['childs'], $level+1, $separator);
            }
        }
        return $data;
    }
    
    
    public static function buildPlainTree($data)
    {
        $arr = [];
        foreach ($data as $key => $item) {
            $arr[] = [
                'id' => $item['category_id'],
                'name' => $item['name'],
                'parent' => $item['parent_id'],
            ];
            if (isset($item['childs'])) {
                $arr = array_merge($arr, self::buildPlainTree($item['childs']));
            }
        }
        return $arr;
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
                ->orderBy('sort ASC')
                ->asArray()
                ->all();

            foreach ($categories as $key => $category) {
                $category['name'] = json_decode($category['name'])->{Yii::$app->language};
                $groupedCategories[$category['parent_id']][] = $category;
            }

            self::$categoriesByParent = $groupedCategories;
            $categories = $groupedCategories[''];

        } else {
            if(isset(self::$categoriesByParent[$id])) {
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
