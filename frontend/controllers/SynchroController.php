<?php
namespace frontend\controllers;

use Yii;
use common\models\Stores;
use common\models\languages;
use common\models\Search;
use dvizh\shop\models\Product;
use dvizh\shop\models\Price;
use dvizh\shop\models\Modification;
use dvizh\filter\models\Filter;
use dvizh\filter\models\FilterValue;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class SynchroController extends \yii\web\Controller
{
    
    public function actionIndex()
    {
        // Yii::$app->cache->flush();
    }
    
    public function actionSearch()
    {
        Search::deleteAll();

        $products = Product::findAll([
            'active' => 1
        ]);
        
        $languages = Languages::findAll([
            'active' => 1
        ]);
        
        foreach ($products as $product) {
            $content = [];
            
            if ($product->sku) $content[] = $product->sku;
            if ($product->code) $content[] = $product->code;
            if ($product->barcode) $content[] = $product->barcode;
            if ($product->vendor_code) $content[] = $product->vendor_code;
            
            if ($productName = json_decode($product->name)) {
                foreach ($productName as $lang => $name) {
                    if (in_array($lang, ['ru', 'en', 'de'])){
                        $content[] = Search::lemmatize(Search::numbersLettersSeparate(Search::normalize($name)), $lang);
                    } else {
                        $content[] = Search::numbersLettersSeparate(Search::normalize($name));
                    }
                }
            }
            
            if ($productText = json_decode($product->text)) {
                foreach ($productText as $lang => $text) {
                    if (in_array($lang, ['ru', 'en', 'de'])){
                        $content[] = Search::lemmatize(Search::numbersLettersSeparate(Search::normalize($text)), $lang);
                    } else {
                        $content[] = Search::numbersLettersSeparate(Search::normalize($text));
                    }
                }
            }
            
            // if ($productShortText = json_decode($product->short_text)) {
                // foreach ($productShortText as $lang => $shortText) {
                    // if (in_array($lang, ['ru', 'en', 'de'])){
                        // $content[] = Search::lemmatize(Search::numbersLettersSeparate(Search::normalize($shortText)), $lang);
                    // } else {
                        // $content[] = Search::numbersLettersSeparate(Search::normalize($shortText));
                    // }
                // }
            // }
            
            if ($productCategories = $product->categories) {
                foreach ($productCategories as $category) {
                    if ($categoryName = json_decode($category->name)) {
                        foreach ($categoryName as $lang => $name) {
                            if (in_array($lang, ['ru', 'en', 'de'])){
                                $content[] = Search::lemmatize($name, $lang);
                            } else {
                                $content[] = $name;
                            }
                        }
                    }
                }
            }

            $filterValues = FilterValue::findAll([
                'item_id' => $product->id
            ]);
            if ($filterValues) {
                foreach ($filterValues as $filterValue) {
                    foreach ($languages as $language) {
                        if (in_array($language->code, ['ru', 'en', 'de'])){
                            $content[] = Search::lemmatize(json_decode($filterValue->variant->value)->{$language->code}, $language->code);
                        } else {
                            $content[] = json_decode($filterValue->variant->value)->{$language->code};
                        }
                    }
                }
            }
            
            // if ($productOptions = $product->getCartOptions()) {
                // foreach ($productOptions as $option) {
                    // if ($option['name'] == 'Размер' && isset($option['variants']) && !empty($option['variants'])) {
                        // foreach ($option['variants'] as $variant) {
                            // $content[] = $variant;
                        // }
                    // }
                // }
            // }
            
            $searchContent = implode(' ', array_unique(explode(' ', implode(' ', $content))));
            
            $model = new Search();
            $model->item_id = $product->id;
            $model->content = $searchContent;
            $model->type = 'product';
            $model->save();
        }
    }
    
}