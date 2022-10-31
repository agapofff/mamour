<?php

namespace backend\controllers;

use Yii;
use common\models\Languages;
use common\models\Stores;
use backend\models\StoresSearch;
use common\models\Countries;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class StoresController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new StoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $languages = Languages::getActiveCodes();
        
        $countries = Countries::find()->all();
        $countries = ArrayHelper::map($countries, 'id', function ($country) {
            return json_decode($country->name)->{Yii::$app->language};
        });
        
        $postcodes = [];
        if ($stores = Stores::find()->all()) {
            foreach ($stores as $store) {
                if ($store->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($store->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);
        
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
            'sort' => $sort,
        ]);
    }

    public function actionCreate()
    {
        $model = new Stores();
        $model->loadDefaultValues();
        $model->sort = Stores::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->postcodes = json_encode($model->postcodes);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::getActiveCodes();
        
        $countries = Countries::find()->all();
        $countries = ArrayHelper::map($countries, 'id', function ($country) {
            return json_decode($country->name)->{Yii::$app->language};
        });
        
        $postcodes = [];
        if ($stores = Stores::find()->all()) {
            foreach ($stores as $store) {
                if ($store->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($store->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            $model->postcodes = $model->postcodes ? json_encode($model->postcodes) : null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
            }

            if (Yii::$app->request->post('saveAndExit')) {
                return $this->redirect(['index']);
            }
        }
        
        $model->postcodes = $model->postcodes ? json_decode($model->postcodes) : null;
        
        $languages = Languages::getActiveCodes();
        
        $countries = Countries::find()->all();
        $countries = ArrayHelper::map($countries, 'id', function ($country) {
            return json_decode($country->name)->{Yii::$app->language};
        });
        
        $postcodes = [];
        if ($stores = Stores::find()->all()) {
            foreach ($stores as $store) {
                if ($store->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($store->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно удалён'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка удаления элемента'));
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Stores::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
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
        
        $sort = Stores::find()
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
