<?php
namespace dvizh\order\controllers;

use yii;
use dvizh\order\models\FieldValueVariant;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class FieldValueVariantController extends Controller
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
                    'edittable' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionCreate()
    {
        $model = new FieldValueVariant();

        $model->load(Yii::$app->request->post());
        $model->save();
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionEditable()
    {
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
        FieldValueVariant::editField($pk, $name, $value);
    }
    
    protected function findModel($id)
    {
        if (($model = FieldValueVariant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested variant does not exist.');
        }
    }
}
