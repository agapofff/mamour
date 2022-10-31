<?php

namespace backend\controllers;

use Yii;
use common\models\Slides;
use backend\models\SlidesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * SlidesController implements the CRUD actions for Slides model.
 */
class SlidesController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Slides models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlidesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $languages = Languages::getActiveCodes();

        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }
        
        $categories = Slides::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    /**
     * Creates a new Slides model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Slides();
        $model->loadDefaultValues();
        $model->sort = Slides::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $categories = Slides::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');
        
        $languages = Languages::getActiveCodes();

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Slides model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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
        
        $categories = Slides::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');
        
        $languages = Languages::getActiveCodes();

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing Slides model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно удалён'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка удаления элемента'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Slides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slides::findOne($id)) !== null) {
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
        
        $sort = Slides::find()
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
