<?php

namespace backend\controllers;

use Yii;
use common\models\Countries;
use backend\models\CountriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;

/**
 * CountriesController implements the CRUD actions for Countries model.
 */
class CountriesController extends Controller
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
     * Lists all Countries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CountriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $languages = Languages::getActiveCodes();
        
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'sort' => $sort,
        ]);
    }

    /**
     * Creates a new Countries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Countries();
        $model->loadDefaultValues();
        $model->sort = Countries::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', '?????????????? ?????????????? ????????????'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', '???????????? ???????????????? ????????????????'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::getActiveCodes();

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    /**
     * Updates an existing Countries model.
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
                Yii::$app->session->setFlash('success', Yii::t('back', '?????????????????? ??????????????????'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', '???????????? ????????????????????'));
            }

            if (Yii::$app->request->post('saveAndExit')) {
                return $this->redirect(['index']);
            }
        }
        
        $languages = Languages::getActiveCodes();

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
        ]);
    }

    /**
     * Deletes an existing Countries model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('back', '?????????????? ?????????????? ????????????'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', '???????????? ???????????????? ????????????????'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Countries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Countries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Countries::findOne($id)) !== null) {
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
        
        $sort = Countries::find()
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
