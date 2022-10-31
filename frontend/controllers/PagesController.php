<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Pages;
// use common\models\MetaTags;
use yii\web\NotFoundHttpException;

class PagesController extends \yii\web\Controller
{
    
    public function actionIndex($slug, $layout = 'main')
    {
        $this->layout = $layout;
        
        $page = Pages::find()
            ->where('slug = :slug', [
                ':slug' => $slug,
            ])
            ->andWhere([
                'active' => 1
            ])
            ->one();
        
        if ($page) {
            
            // $meta = MetaTags::find()
                // ->where('link = :link', [
                    // ':link' => Yii::$app->request->absoluteUrl
                // ])
                // ->andWhere([
                    // 'active' => 1
                // ])
                // ->one();
                
            return $this->render('index', [
                'model' => $page,
                'meta' => $meta,
            ]);
            
        } else {
            throw new NotFoundHttpException(Yii::t('front', 'Запрашиваемая информация не найдена'));
        }
    }
    
}