<?php
namespace dvizh\filter\controllers;

use yii;
use dvizh\filter\models\Filter;
use dvizh\filter\models\tools\FilterSearch;
use dvizh\filter\models\FilterVariant;
use dvizh\filter\models\tools\FilterVariantSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;

class FilterController extends Controller
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

    public function actionIndex($tab = 'filters')
    {
        $searchModel = new FilterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($tab == 'filters') {
            $dataProvider->query->andWhere(['{{%filter}}.is_filter' => 'yes']);
        } else {
            $dataProvider->query->andWhere(['{{%filter}}.is_option' => 'yes']);
        }
        
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'tab' => Html::encode($tab),
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
        $model = new Filter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'update', 
                'id' => $model->id
            ]);
        } else {
            $languages = Languages::findAll([
                'active' => 1
            ]);
            return $this->render('create', [
                'model' => $model,
                'languages' => $languages
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $searchModel = new FilterVariantSearch;

            $params = Yii::$app->request->queryParams;
            
            if (empty($params['FilterVariantSearch'])) {
                $params = ['FilterVariantSearch' => ['filter_id' => $model->id]];
            }

            $dataProvider = $searchModel->search($params);

            $variantModel = new FilterVariant;
            $variantModel->filter_id = $model->id;
            
            $languages = Languages::findAll([
                'active' => 1
            ]);
            
            foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
                $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
            }

            return $this->render('update', [
                'model' => $model,
                'variantModel' => $variantModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'languages' => $languages,
                'sort' => $sort,
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
        if (($model = Filter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditable()
	{
		$name = Yii::$app->request->post('name');
		$value = Yii::$app->request->post('value');
		$pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
		Filter::saveEdit($pk, $name, $value);
	}

    public function actionEditVariant()
    {
        $name = Yii::$app->request->post('name');
		$value = Yii::$app->request->post('value');
		$pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
		FilterVariant::saveEdit($pk, $name, $value);
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
        
        $sort = Filter::find()
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
