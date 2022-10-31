<?php

namespace sitronik\treemenu\models;

use Yii;

/**
 * This is the model class for table "tree_menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $parent
 * @property integer $parent_id
 */
class TreeMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tree_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent', 'parent_id'], 'required'],
            [['parent', 'parent_id', 'active', 'sort', 'target_blank'], 'integer'],
            [['url', 'alias'], 'string', 'max' => 500],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'url' => 'Ссылка',
            'parent' => 'Parent',
            'parent_id' => 'Parent ID',
            'active' => 'Активно',
            'alias' => 'Алиас',
            'target_blank' => 'Открывать в новой вкладке',
        ];
    }
}
