<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%search}}".
 *
 * @property int $id
 * @property int|null $item_id
 * @property string $content
 * @property string $type
 */
 
class Search extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%search}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id'], 'integer'],
            [['item_id', 'content', 'type'], 'required'],
            [['content', 'type'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', 'ID'),
            'item_id' => Yii::t('front', 'Item ID'),
            'content' => Yii::t('front', 'Content'),
            'type' => Yii::t('front', 'Type'),
        ];
    }
    
    public static function normalize($string)
    {
        $string = strip_tags($string); // удаляем тэги
        $string = htmlspecialchars($string, ENT_HTML5);
        $string = preg_replace('/[^\p{L}\p{N}]/u', ' ', $string); // оставляем только буквы и цифры
        $string = preg_replace('/\s\s+/', ' ', $string); // убираем лишние пробелы
        $string = mb_strtolower($string, 'UTF-8'); // всё в нижний регистр
        return $string;
    }
    
    
    public static function numbersLettersSeparate($string)
    {
        $words = explode(' ', $string);
        $newString = [];
        
        foreach ($words as $word) {
            $numbers = $letters = '';
            for ($i = 0; $i < strlen($word); $i++) {
                if (is_numeric($word[$i])) {
                    $numbers .= $word[$i];
                } else {
                    $letters .= $word[$i];
                }
            }
            if ($numbers != $letters) {
                $newString[] = $numbers;
                $newString[] = $letters;
            }
        }

        return $string . ' ' . implode(' ', $newString);
    }
    
    public static function lemmatize($string, $lang = 'ru', $all = false)
    {
        $searchString = mb_strtoupper($string, 'UTF-8');

        $searchArray = explode(' ', $searchString);
        $searchArray = array_unique($searchArray);
        
        if (in_array($lang, ['en', 'vi', 'uz'])) {
            Yii::$app->yiimorphy->language = 'uk';
        } elseif (in_array($lang, ['kz', 'ua'])) {
            Yii::$app->yiimorphy->language = 'ru';
        } else {
            Yii::$app->yiimorphy->language = $lang;
        }
        
        $morphy = Yii::$app->yiimorphy->morphy;
        
        $normalizedSearchArray = [];
        
        // слова массивом = быстрее на 35-50%
        $lemmas = $all ? $morphy->getAllForms($searchArray) : $morphy->getBaseForm($searchArray);

        foreach ($lemmas as $word => $lemma) {
            if ($lemma) {
                if (is_array($lemma) && !empty($lemma)) {
                    foreach ($lemma as $key => $val) {
                        $normalizedSearchArray[] = $val;
                    }
                } else {
                    $normalizedSearchArray[] = $lemma;
                }
            } else {
                $normalizedSearchArray[] = $word;
            }
        }
        
        // кажде слово - по очереди
        // foreach ($searchArray as $key => $word) {
            // $word = trim($word);
            // $lemma = $morphy->getBaseForm($word);

            // if ($lemma === false) {
                // $normalizedSearchArray[] = $word;
            // } else {
                // if (is_array($lemma)) {
                    // foreach ($lemma as $lem){
                        // $normalizedSearchArray[] = $lem;
                    // }
                // } else {
                    // $normalizedSearchArray[] = $lemma;
                // }
            // }
        // }
        
        $searchArray = array_unique($normalizedSearchArray);
        sort($searchArray);

        $searchString = mb_strtolower(implode(' ', $searchArray));

        return $searchString;
    }
    
}
