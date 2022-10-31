<?php
namespace dvizh\field\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dvizh\field\models\FieldVariant;
use dvizh\field\models\Field;
use dvizh\field\models\tools\FieldSearch;
use dvizh\field\models\tools\FieldVariantSearch;

class FieldController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new FieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sort' => $sort,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Field();
        $model->loadDefaultValues();
        $model->sort = Field::find()->max('sort') + 1;        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'update', 
                'id' => $model->id
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $searchModel = new FieldVariantSearch;

            $params = Yii::$app->request->queryParams;
            if (empty($params['FieldVariantSearch'])) {
                $params = ['FieldVariantSearch' => ['field_id' => $model->id]];
            }

            $dataProvider = $searchModel->search($params);

            $variantModel = new FieldVariant;
            $variantModel->field_id = $model->id;

            return $this->render('update', [
                'model' => $model,
                'variantModel' => $variantModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Field::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested field does not exist.');
        }
    }

    public function actionEditable()
	{
		$name = Yii::$app->request->post('name');
		$value = Yii::$app->request->post('value');
		$pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
		field::saveEdit($pk, $name, $value);
	}

    public function actionEditVariant()
    {
        $name = Yii::$app->request->post('name');
		$value = Yii::$app->request->post('value');
		$pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
		FieldVariant::saveEdit($pk, $name, $value);
    }
}
