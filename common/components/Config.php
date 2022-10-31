<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use common\models\Settings;

class Config extends Component
{
    private $_attributes;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_attributes = Settings::findAll([
            'active' => 1
        ]);
    
        foreach ($this->_attributes as $setting) {
            $settingName = is_numeric($setting->name) ? (float) $setting->name : $setting->name;
            $settingValue = $this->format($setting->value, $setting->type);
            
            if ($setting->category) {
                Yii::$app->params[$setting->category][$settingName] = $settingValue;
            } else {
                Yii::$app->params[$settingName] = $settingValue;
            }
            if (property_exists(Yii::$app, $settingName)) {
                Yii::$app->{$settingName} = $settingValue;
            }
        }
// echo \yii\helpers\VarDumper::dump(Yii::$app->params, 99, true);
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name, $category = null)
    {
        // if (array_key_exists($name, $this->_attributes)) {
            // return $this->_attributes[$name];
        // }
// echo \yii\helpers\VarDumper::dump($this->_attributes, 99, true);
        foreach ($this->_attributes as $setting) {
            if (
                $setting->name == $name 
                && $setting->category == $category
            ) {
                return $this->format($setting->value, $setting->type);
            }
        }
        return parent::__get($name);
    }
    
    public function format($value, $type)
    {
        switch ($type) {
            case 0: 
                $value = (string) $value; 
                break;
            case 1: 
                $value = (float) $value; 
                break;
            case 2: 
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN); 
                break;
            case 3: 
                $value = explode(PHP_EOL, $value); 
                break;
        }
        return $value;
    }
}
