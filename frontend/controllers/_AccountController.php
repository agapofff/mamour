<?php
namespace frontend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dvizh\shop\models\Price;
use dvizh\shop\models\Product;
use dvizh\shop\models\product\ProductSearch;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use dektrium\user\models\Profile;
use dektrium\user\models\User;
use backend\models\Actions;
use common\models\Languages;
use backend\models\Breeds;

class AccountController extends Controller
{

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        
        $user = User::findOne(Yii::$app->user->id);
        
        $profile = Profile::findOne([
            'user_id' => Yii::$app->user->id
        ]);
        
        $breed = $model->breed ? Breeds::findOne($model->breed)->name : 0;
        
        $actions = Actions::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'published' => SORT_DESC
            ])
            ->limit(4)
            ->all();
        
        return $this->render('index', [
            'user' => $user,
            'profile' => $profile,
            'breed' => $breed,
            'actions' => $actions,
        ]);
    }
    
    public function actionEdit()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        
        $user = User::findOne(Yii::$app->user->id);
        
        $profile = Profile::findOne([
            'user_id' => Yii::$app->user->id
        ]);
        
        if ($profile->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            if ($profile->save() && $user->save()) {
                Yii::$app->session->setFlash('success', Yii::t('front', 'Ваш профиль был обновлён'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже'));
            }
        }
        
        $breed = $profile->breed ? Breeds::findOne($profile->breed)->name : 0;
        
        $actions = Actions::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'published' => SORT_DESC
            ])
            ->limit(4)
            ->all();
        
        return $this->render('edit', [
            'user' => $user,
            'profile' => $profile,
            'breed' => $breed,
            'actions' => $actions,
        ]);
    }
    
}