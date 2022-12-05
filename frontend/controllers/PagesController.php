<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Pages;
use yii\web\NotFoundHttpException;

class PagesController extends Controller
{
    public function actionIndex($slug, $layout = 'main')
    {
        $this->layout = $layout;
        
        $model = Pages::find()
            ->where('slug = :slug', [
                ':slug' => $slug,
            ])
            ->andWhere([
                'active' => 1
            ])
            ->one();
        
        if ($model) {
            $this->view->params['model'] = $model;
            
            return $this->render('index', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('front', 'Запрашиваемая информация не найдена'));
        }
    }
    
}