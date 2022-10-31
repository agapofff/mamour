<?php

namespace klisl\languages;

use Yii;

/**
 * Class UrlManager
 * Добавление указателя языка в ссылки
 * @package klisl\languages
 */
class UrlManager extends \yii\web\UrlManager {

    /**
     * @param array|string $params
     * @return string
     */
    public function createUrl($params) {

        $module = Yii::$app->getModule('languages');
        //Сссылка(без идентификатора языка)
        $url = parent::createUrl($params);

        $curentLang = Yii::$app->language;

        if (empty($params['lang'])) {
            if($curentLang != $module->default_language || $module->show_default === true){
                if ($url == '/') {
                    return '/' . Yii::$app->request->baseUrl . $curentLang;
                } else {
                    return '/' . Yii::$app->request->baseUrl . $curentLang . $url;
                }
            }
        };

        return $url;
    }
}