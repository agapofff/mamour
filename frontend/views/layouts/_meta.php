<?php

    $meta = \backend\models\MetaTags::find()
        ->where('link = :link', [
            ':link' => Yii::$app->request->absoluteUrl
        ])
        ->andWhere([
            'active' => 1
        ])
        ->one();
        
    if ($meta) {
        if ($meta->title) {
            $this->title = $meta->title;
        }
        if ($meta->description) {
            $this->registerMetaTag([
                'name' => 'description',
                'content' => $meta->description
            ]);
        }
        if ($meta->h1) {
            $this->params['h1'] = $meta->h1;
        }
    }