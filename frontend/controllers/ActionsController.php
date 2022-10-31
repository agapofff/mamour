<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Actions;

class ActionsController extends Controller
{
    public function actionIndex()
    {
        $actions = Actions::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'published' => SORT_DESC
            ])
            ->all();
        
        return $this->render('index', [
            'actions' => $actions,
        ]);
    }
    
    public function actionView($slug)
    {
        $action = Actions::find()
            ->where('slug = :slug', [
                ':slug' => $slug
            ])
            ->andWhere([
                'active' => 1
            ])
            ->one();
            
        return $this->render('view', [
            'action' => $action,
        ]);
    }
}
