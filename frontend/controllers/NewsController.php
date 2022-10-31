<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\NewsCategories;
use common\models\News;

class NewsController extends Controller
{
    
    public function actionIndex()
    {
        $categories = NewsCategories::findAll([
            'active' => 1
        ]);
        
        $posts = News::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'date_published' => SORT_DESC
            ])
            ->all();
        
        return $this->render('index', [
            'categories' => $categories,
            'posts' => $posts,
        ]);
    }
    
    public function actionPost($slug)
    {
        $post = News::find()
            ->where('slug = :slug', [
                ':slug' => $slug
            ])
            ->andWhere([
                'active' => 1
            ])
            ->one();
            
        $posts = News::find()
            ->where([
                'active' => 1
            ])
            ->andWhere([
                '<>', 'id', $post->id
            ])
            ->orderBy([
                'date_published' => SORT_DESC
            ])
            ->limit(3)
            ->all();
            
        return $this->render('view', [
            'post' => $post,
            'posts' => $posts
        ]);
    }
    
}
