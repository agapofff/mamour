<?php

namespace sitronik\treemenu\controllers;

use Yii;
use yii\web\Controller;
use sitronik\treemenu\models\TreeMenu;
use yii\web\NotFoundHttpException;
use common\models\Languages;
use dvizh\shop\models\Category;
use dvizh\shop\models\Product;
use dvizh\filter\models\Filter;

/**
 * Default controller for the `treemenu` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInsert($id) {
        $model = new TreeMenu();
        $model->loadDefaultValues();
        $model->sort = TreeMenu::find()->max('sort') + 1;
        
        $languages = Languages::getActiveCodes();
        $categories = Category::buildTextTree();
        $products = Product::findAll(['active' => 1]);
        $filters = Filter::find()
            ->where([
                'active' => 1,
                'is_filter' => 'yes',
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->parent == 0) {
                $model->parent = 1;
            } else {
                $model->parent = 0;
            }

            if ($id == 'root') {
                $model->parent_id = 0;
            } else {
                $model->parent_id = $id;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно создан'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка создания элемента'));
            }
            return $this->redirect(['index']);

        } else {

            if ($id != 'root') {
                $model = TreeMenu::findOne($id);
                $model->isNewRecord = true;
                $model->name = '';
                $model->url = '';
            } else {
                $model->parent = 0;
            }
            
            return $this->render('insert', [
                'model' => $model,
                'languages' => $languages,
                'categories' => $categories,
                'products' => $products,
                'filters' => $filters,
            ]);
        }

     }

    public function actionUpdate($id) 
    {
        $model = $this->findModel($id);

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
        
        $languages = Languages::getActiveCodes();
        $categories = Category::buildTextTree();
        $products = Product::findAll(['active' => 1]);
        $filters = Filter::find()
            ->where([
                'active' => 1,
                'is_filter' => 'yes',
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'categories' => $categories,
            'products' => $products,
            'filters' => $filters,
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            TreeMenu::deleteAll([
                'parent_id' => $id
            ]);
            
            Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно удалён'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка удаления элемента'));
        }

        return $this->redirect(['index']);
    }
    
	public function actionCopy($id)
	{
		$model = new TreeMenu();
		$model->attributes = $this->findModel($id)->attributes;
        $model->sort = TreeMenu::find()->max('sort') + 1;
        
		if ($model->save()){
			Yii::$app->session->setFlash('success', Yii::t('back', 'Элемент успешно скопирован'));
			return $this->redirect([
				'update',
				'id' => $model->id,
			]);
		} else {
			Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка копирования элемента'));
			return $this->redirect(['index']);
		}
	}

    protected function findModel($id)
    {
        if (($model = TreeMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionActive($id)
    {
        $model = TreeMenu::findOne($id);
        $model->active = $model->active ? 0 : 1;
        
        if ($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('back', 'Изменения сохранены'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('back', 'Ошибка сохранения'));
        }
        
        if (Yii::$app->request->isAjax){
            $this->actionIndex();
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
    public function actionSort($elements)
    {
        $elements = explode(',', $elements);
        
        $sort = TreeMenu::find()
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
    
    public function actionGetCategoryData($id)
    {
        $category = Category::findOne($id);
        $categories = Category::find()->all();
        
        return json_encode([
            'id' => $category->id,
            'name' => $category->name,
            'path' => '/catalog/' . join('/', array_reverse(Category::getAllParents($categories, $category->id, 'slug', true)))
        ]);
    }
}
