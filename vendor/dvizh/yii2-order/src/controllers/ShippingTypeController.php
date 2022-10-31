<?php
namespace dvizh\order\controllers;

use yii;
use dvizh\order\models\ShippingType;
use dvizh\order\models\tools\ShippingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Languages;
use common\models\Countries;
use dvizh\order\models\PaymentType;

class ShippingTypeController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => Yii::$app->getModule('order')->adminRoles,
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
        $searchModel = new ShippingTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $postcodes = [];
        if ($shippingTypes = ShippingType::find()->all()) {
            foreach ($shippingTypes as $shippingType) {
                if ($shippingType->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($shippingType->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);
        
        $paymentTypes = PaymentType::find()->all();
        
        $countries = Countries::find()->all();
        
        foreach ($dataProvider->sort->attributeOrders as $sortKey => $sortVal) {
            $sort = $sortKey == 'sort' ? ($sortVal == 3 ? 'desc' : 'asc') : false;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
            'paymentTypes' => $paymentTypes,
            'sort' => $sort,
        ]);
    }

    public function actionCreate()
    {
        $model = new ShippingType();
        $model->loadDefaultValues();
        $model->sort = ShippingType::find()->max('sort') + 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->postcodes = $model->postcodes ? json_encode($model->postcodes) : null;
            $model->payment_types = is_array($model->payment_types) ? join(',', $model->payment_types) : null;
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::getActiveCodes();
        
        $countries = Countries::find()->all();
        
        $postcodes = [];
        if ($shippingTypes = ShippingType::find()->all()) {
            foreach ($shippingTypes as $shippingType) {
                if ($shippingType->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($shippingType->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);
        
        $paymentTypes = PaymentType::find()->all();

        return $this->render('create', [
            'model' => $model,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
            'paymentTypes' => $paymentTypes,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->postcodes = $model->postcodes ? json_encode($model->postcodes) : null;
            $model->payment_types = is_array($model->payment_types) ? join(',', $model->payment_types) : null;
            if ($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
            }

            if (Yii::$app->request->post('saveAndExit')) {
                return $this->redirect(['index']);
            }
        }
        
        $model->postcodes = $model->postcodes ? json_decode($model->postcodes) : null;
        $model->payment_types = explode(',', $model->payment_types);
        
        $languages = Languages::getActiveCodes();
        
        $countries = Countries::find()->all();
        
        $postcodes = [];
        if ($shippingTypes = ShippingType::find()->all()) {
            foreach ($shippingTypes as $shippingType) {
                if ($shippingType->postcodes) {
                    $postcodes = array_merge($postcodes, json_decode($shippingType->postcodes));
                }
            }
        }
        $postcodes = array_unique($postcodes);
        $postcodes = array_combine($postcodes, $postcodes);
        
        $paymentTypes = PaymentType::find()->all();

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'countries' => $countries,
            'postcodes' => $postcodes,
            'paymentTypes' => $paymentTypes,
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
        if (($model = ShippingType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }

    public function actionActive($id)
    {
        $model = $this->findModel($id);
        $model->active = $model->active ? 0 : 1;
        if ($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
        }

        if (Yii::$app->request->isAjax){
            $this->actionIndex();
        } else {
            return $this->redirect(['index']);
        }
    }
    
    public function actionSort($elements)
    {
        $elements = explode(',', $elements);
        
        $sort = ShippingType::find()
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