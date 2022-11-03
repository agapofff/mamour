<?php

namespace dvizh\shop\controllers;

use Yii;
use dvizh\shop\models\Category;
use dvizh\shop\models\category\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;

class CategoryController extends Controller
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
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'edittable' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $languages = Languages::findAll([
            'active' => 1
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
        ]);
    }

    public function actionCreate()
    {
        $model = new Category;
        $model->loadDefaultValues();
        $model->sort = Category::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::findAll([
            'active' => 1
        ]);

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
            }

            if (Yii::$app->request->post('saveAndExit')){
                return $this->redirect(['index']);
            }
        }
        
        $languages = Languages::findAll([
            'active' => 1
        ]);

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()){
            Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно удалён'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка удаления элемента'));
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $model = new Category;
        
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested product does not exist.');
        }
    }
    
    protected function findModelBySlug($slug)
    {
        $model = new Category;
        
        if (($model = $model::findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested product does not exist.');
        }
    }
	
    public function actionActive($id)
    {
        $model = Category::findOne($id);
        $model->active = $model->active ? 0 : 1;
        
        if ($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
        }
        
        if (Yii::$app->request->isAjax){
            $this->actionIndex();
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
    public function actionSort($elements)
    {
        $elements = explode(',', $elements);
        
        $sort = Category::find()
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
    
    public function actionCopy($id)
    {
        $category = Category::findOne($id);
        $model = new Category();
        $model->attributes = $category->attributes;
        $model->sort = Category::find()->max('sort') + 1;
        
        if ($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
        }
        
        if (Yii::$app->request->isAjax){
            $this->actionIndex();
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}
