<?php

namespace dvizh\filter\controllers;

use yii;
use dvizh\filter\models\Filter;
use dvizh\filter\models\tools\FilterSearch;
use dvizh\filter\models\FilterVariant;
use dvizh\filter\models\tools\FilterVariantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

class FilterVariantController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        if(Yii::$app->request->post('list')) {
            $list = array_map('trim', explode("\n", Yii::$app->request->post('list')));

            foreach($list as $variant) {
                $model = new FilterVariant();
                $model->active = 1;
                $model->sort = FilterVariant::find()->max('sort') + 1;
                $model->value = $variant;
                $model->filter_id = (int)Yii::$app->request->post('FilterVariant')['filter_id'];
                $model->save();
            }

            if(isset($model)) {
                return $this->redirect(['/filter/filter/update', 'id' => $model->filter_id]);
            }
        }
        else {
            $json = [];
            $model = new FilterVariant();

            $post = Yii::$app->request->post('FilterVariant');
            //Если такой вариант уже есть у этого товара, просто выставляем его выделение
            if($have = $model::find()->where(['value' => $post['value'], 'filter_id' => $post['filter_id']])->one()) {
                $json['result'] = 'success';
                $json['value'] = $have->value;
                $json['id'] = $have->id;
                $json['new'] = false;
            //Если варианта нет, создаем
            } else {
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $json['result'] = 'success';
                    $json['value'] = $model->value;
                    $json['id'] = $model->id;
                    $json['new'] = true;
                } else {
                    $json['result'] = 'fail';
                }
            }
            
            return json_encode($json);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['/filter/filter/update', 'id' => $model->filter_id]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/filter/filter/update', 'id' => $model->filter_id]);
        } else {
            throw new NotFoundHttpException('Не удалось проверить данные.');
        }
    }
    
    protected function findModel($id)
    {
        if (($model = FilterVariant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        $model->active = $model->active ? 0 : 1;
        $model->save();
        
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }
    
    public function actionSort($elements)
    {
        $elements = explode(',', $elements);
        
        $sort = FilterVariant::find()
            ->where([
                'id' => $elements
            ])
            ->min('sort');
            
        foreach ($elements as $key => $element) {
            $model = $this->findModel((int)$element);
            $model->sort = $sort + $key;
            if (!$model->save()) {
                throw new BadRequestHttpException($model->getErrors());
            }
        }
    }
}
