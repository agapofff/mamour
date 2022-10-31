<?php

namespace backend\controllers;

use Yii;
use common\models\News;
use backend\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;
use common\models\NewsCategories;
use yii\helpers\ArrayHelper;

class NewsController extends Controller
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
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $languages = Languages::findAll([
            'active' => 1
        ]);
        
        $categories = News::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');
            
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    public function actionCreate()
    {
        $model = new News();
        $model->loadDefaultValues();
        $model->date_published = date('Y-m-d');
        $model->sort = News::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::getActiveCodes();
        
        $categories = News::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
            }

            if (Yii::$app->request->post('saveAndExit')) {
                return $this->redirect(['index']);
            }
        }
        
        $languages = Languages::getActiveCodes();
        
        $categories = News::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'categories' => $categories,
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
        if (($model = News::findOne($id)) !== null) {
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
        
        $sort = News::find()
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
