<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\base\DynamicModel;
use linslin\yii2\curl\Curl;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login', 
                            'error', 
                            'curl', 
                            'images-get',
                            'image-upload',
                            'image-delete',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    // 'save-redactor-img' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => Yii::$app->request->hostInfo . '/images/upload',
                'path' => '@images/upload',
                'options' => [
                    'only' => [
                        '*.jpg', 
                        '*.jpeg', 
                        '*.png', 
                        '*.gif', 
                        // '*.ico'
                    ]
                ],
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/images/upload', // Url::home(true) . 'images/',
                'path' => '@images/upload',
                'uploadOnlyImage' => false,
                'unique' => false,
                'replace' => true,
                'translit' => true,
            ],
            'image-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => Yii::$app->request->hostInfo . '/images/upload/',
                'path' => '@images/upload',
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionSaveRedactorImg($sub = 'main')
    {
        $this->enableCsrfValidation = false;
        // if (Yii::$app->request->isPost) {
            $dir = Yii::getAlias('@storageUrl') . '/' . $sub . '/';
            if (!file_exists($dir)) {
                FileHelper::createDirectory($dir);
            }
     
            $result_link = str_replace('admin', '', Url::home(true)) . 'images' . '/' . $sub . '/';
            $file = UploadedFile::getInstanceByName('file');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image')->validate();
     
            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                $model->file->name = strtotime('now') . '_' . Yii::$app->getSecurity()->generateRandomString(6) . '.' . $model->file->extension;
                if ($model->file->saveAs($dir . $model->file->name)) {
                    $imag = Yii::$app->image->load($dir . $model->file->name);
                    $imag->resize(100, NULL, Yii\image\drivers\Image::PRECISE)->save($dir . $model->file->name, 85);
     
                    $result = [
                        'filelink' => $result_link . $model->file->name, 
                        'filename' => $model->file->name
                    ];
                } else {
                    $result = [
                        'error' => Yii::t('back', 'Ошибка загрузки файла')
                    ];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $result;
            }
        // } else {
            // throw new BadRequestHttpException('Only Post is allowed');
        // }
    }
    
    
    
    public function actionCurl ($url, $post = null, $params = null, $json = null)
    {
        $curl = new Curl();
        if ($params) {
            if ($post) {
                $curl->setPostParams(\yii\helpers\Json::decode($params));
            } else {
                $curl->setGetParams(\yii\helpers\Json::decode($params));
            }
        }
        $response = $post ? $curl->post($url) : $curl->get($url);
        if ($curl->errorCode === null) {
            return $response;
        }
        
        return false;
    }
    
}
