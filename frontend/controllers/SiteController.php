<?php
namespace frontend\controllers;

// use frontend\models\ResendVerificationEmailForm;
// use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dvizh\shop\models\Price;
use dvizh\shop\models\Category;
use dvizh\shop\models\Product;
use dvizh\shop\models\product\ProductSearch;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use dektrium\user\models\Profile;
use dektrium\user\models\User;
use common\models\Pages;
use common\models\News;
use common\models\Languages;
use common\models\Slides;
use common\models\Bonus;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['account', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $slidesDesktop = Slides::find()
            ->where([
                'active' => 1,
                'category' => 'Главный слайдер - Desktop',
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
            
        $slidesMobile = Slides::find()
            ->where([
                'active' => 1,
                'category' => 'Главный слайдер - Mobile',
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
            
        $categories = Category::findAll([
            'active' => 1
        ]);
            
        return $this->render('index', [
            'slidesDesktop' => $slidesDesktop,
            'slidesMobile' => $slidesMobile,
            'categories' => $categories,
        ]);
        
    }
    
    public function actionBlog()
    {
        $posts = News::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'date_published' => SORT_DESC
            ])
            ->limit(6)
            ->all();
        
        return $this->render('blog', [
            'categories' => $categories,
        ]);
    }
    
    public function actionAccount()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        
        $user = Yii::$app->user->identity;
        $userBonus = Bonus::getUserBonus(Yii::$app->user->id);
        $profile = Yii::$app->user->identity->profile;
        
        return $this->render('account', [
            'user' => $user,
            'profile' => $profile,
            'userBonus' => $userBonus,
        ]);
    }
    
    
    public function actionAbout()
    {
        return $this->render('about');
    }

    
    public function actionCookiesNotificationShown()
    {
        Yii::$app->session->set('cookiesNotificationShown', true);
        return true;
    }
    
    
    public function actionSitemap()
    {
        $categories = \dvizh\shop\models\Category::buildTree(true);
        return $this->render('sitemap', [
            'categories' => $categories,
        ]);
    }
    
    public function actionJoin()
    {
        return Yii::$app->response->redirect([
            'register',
            'referal' => Yii::$app->request->get('referal')
        ]);
    }
    
    

}
