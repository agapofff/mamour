<?php
namespace dvizh\shop\controllers;

use dvizh\shop\models\Modification;
use dvizh\shop\models\Product;
use dvizh\shop\models\ModificationToOption;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Html;
use dvizh\filter\models\FilterVariant;
use common\models\Stores;

class ModificationController extends Controller
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
                    // 'delete' => ['post'],
                    'edittable' => ['post'],
                ],
            ],
        ];
    }

    public function actionAddPopup($productId)
    {
        $this->layout = 'mini';
        
        $model = new Modification;
        $model->loadDefaultValues();
        
        $model->product_id = (int)$productId;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            if ($prices = Yii::$app->request->post('Price')) {
                foreach ($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if ($filterValue = Yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach ($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }
            
            Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            return Html::script("window.parent.$('.modal').modal('hide');");
        }

        $productModel = Product::findOne($productId);
        
        if (!$productModel) {
            throw new NotFoundHttpException('The requested product does not exist.');
        }
        
        $stores = Stores::find()->all();

        return $this->render('create', [
            'model' => $model,
            'module' => $this->module,
            'productModel' => $productModel,
            'stores' => $stores,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Modification;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if ($filterValue = yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }

            $this->redirect(Yii::$app->request->referrer);
        }
        
        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($prices = yii::$app->request->post('Price')) {
                foreach($prices as $typeId => $price) {
                    $model->setPrice($price['price'], $typeId);
                }
            }

            if($filterValue = yii::$app->request->post('filterValue')) {
                ModificationToOption::deleteAll(['modification_id' => $model->id]);
                foreach($filterValue as $filterId => $variantId) {
                    $rel = new ModificationToOption;
                    $rel->modification_id = $model->id;
                    $rel->option_id = $filterId;
                    $rel->variant_id = $variantId;
                    $rel->save();
                }
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $productModel = $model->product;
            
            $stores = Stores::find()->all();

            return $this->render('update', [
                'productModel' => $productModel,
                'module' => $this->module,
                'model' => $model,
                'stores' => $stores,
            ]);
        }
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        // $this->redirect(Yii::$app->request->referrer);
        // return true;
        return Html::script("
            $.pjax.reload({
                container: '#product-modifications',
                async: false
            });
        ");
    }

    public function actionEditField()
    {
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
        $model = new Modification;
        $model::editField($pk, $name, $value);
    }

    protected function findModel($id)
    {
        $model = new Modification;
        
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPublish($id)
    {
        $model = Modification::findOne($id);
        $model->available = $model->available ? 0 : 1;
        $model->save();
        if (!Yii::$app->request->isAjax){
            return $this->redirect([
                'product/update',
                'id' => $model->product_id
            ]);
        }
    }
    
    public function actionSynchro($id)
    {
        $model = Modification::findOne($id);
        $model->synchro = $model->synchro ? 0 : 1;
        $model->save();
        if (!Yii::$app->request->isAjax){
            return $this->redirect([
                'product/update',
                'id' => $model->product_id
            ]);
        }
    }
    
}
