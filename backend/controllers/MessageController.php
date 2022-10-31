<?php

namespace backend\controllers;

use Yii;
use backend\models\Message;
use backend\models\MessageSearch;
use backend\models\SourceMessage;
use common\models\Languages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class MessageController extends Controller
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
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $sourceMessage = SourceMessage::find()->asArray()->all();
        
        $message = array();
        
        if (isset(Yii::$app->request->queryParams['MessageSearch']['id'])) {
            $message = SourceMessage::findOne(['id' => Yii::$app->request->queryParams['MessageSearch']['id']])->message;
        }
        
        $languages = Languages::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sourceMessage' => $sourceMessage,
            'message' => $message,
            'languages' => $languages,
        ]);
    }

    public function actionCreate()
    {
        $model = new Message();
        $sourceMessage = SourceMessage::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);
        }
        
        $languages = Languages::find()->all();

        return $this->render('create', [
            'model' => $model,
            'sourceMessage' => $sourceMessage,
            'languages' => $languages,
        ]);
    }

    public function actionUpdate($id, $language)
    {
        $model = Message::findOne([
            'id' => $id,
            'language' => $language,
        ]);
        $sourceMessage = SourceMessage::find()->all();

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
        
        $languages = Languages::find()->all();

        return $this->render('update', [
            'model' => $model,
            'sourceMessage' => $sourceMessage,
            'languages' => $languages,
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
        if (($model = Message::findOne([
            'id' => $id
        ])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('back', 'The requested page does not exist.'));
    }
}
