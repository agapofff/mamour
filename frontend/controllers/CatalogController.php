<?php

namespace frontend\controllers;

use Yii;
use common\models\Stores;
use common\models\Languages;
use dvizh\shop\models\Product;
// use common\models\Category;
use dvizh\shop\models\Category;
use dvizh\filter\models\Filter;
use dvizh\filter\models\FilterVariant;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class CatalogController extends Controller
{
    public function actionIndex()
    {
        $categories = Category::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
        
        return $this->render('index', [
            'categories' => $categories
        ]);
    }
    
    public function actionCategory($slug)
    {
        $category = Category::find()
            ->where('slug = :slug', [
                ':slug' => $slug
            ])
            ->one();

        $store = Stores::findOne([
            'lang' => Yii::$app->language,
            'type' => Yii::$app->params['store_type']
        ]);
        
        $prices = Price::find()
            ->where([
                'name' => $store->store_id
            ])
            ->asArray()
            ->all();

        $products = $category->products;

        return $this->render('category', [
            'category' => $category,
            'products' => $products,
            'store' => $store,
            'prices' => ArrayHelper::index($prices, 'item_id'),
        ]);
    }

/*
    public function actionProducts($path = null)
    {
        $new = $popular = $sale = $promo = null;
        $products = $productsIDs = $productsSizes = $productsPrices = [];
        
        $queryParams = $baseQueryParams = Yii::$app->request->getQueryParams();
        
        // echo Yii::$app->request->pathInfo;

        if ($path) {
            $slugs = explode('/', $path);
            
            switch ($slugs[array_key_last($slugs)]) {
                case 'new':
                    $new = 1;
                    break;
                case 'popular':
                    $popular = 1;
                    break;
                case 'promo':
                    $promo = 1;
                    break;
                case 'sale':
                    $sale = 1;
                    break;
            }
            
            if (in_array(1, [$new, $popular, $promo, $sale])) {
                array_pop($slugs);
                $path = join('/', $slugs);
            }
            
            $filterVariants = FilterVariant::findAll([
                'latin_value' => $slugs
            ]);
            
            if ($filterVariants) {
                foreach ($filterVariants as $filterVariant) {
                    $queryParams['filter'][$filterVariant->filter_id][] = $filterVariant->id;
                }
            }
            
            Yii::$app->request->setQueryParams($queryParams);
// echo VarDumper::dump(Yii::$app->request->getQueryParams(), 99, true); exit;
        }
        
        $productsIDs = Product::find()
            ->active()
            ->filtered()
            ->andFilterWhere([
                'new' => $new,
                'promo' => $promo,
                'popular' => $popular,
                'sale' => $sale,
            ])
            ->orderBy([
                'sort' => SORT_DESC
            ])
            ->column();
            
        if (!empty($productsIDs)) {
            $modifications = Product::getAllProductsPrices($productsIDs);
            $modificationsSizes = Product::getAllProductsSizes($productsIDs);
            $modificationsPrices = ArrayHelper::map($modifications, 'product_id', 'price');
            $modificationsOldPrices = ArrayHelper::map($modifications, 'product_id', 'price_old');
            $productsSizes = array_unique(ArrayHelper::map($modificationsSizes, 'id', 'value'));
            $productsPrices = array_unique($modificationsPrices);

            $goods = Product::find()
                ->where([
                    'id' => $productsIDs,
                ])
                ->active()
                ->andFilterWhere([
                    'new' => $new,
                    'promo' => $promo,
                    'popular' => $popular,
                    'sale' => $sale,
                ])
                ->filtered()
                ->orderBy([
                    'sort' => SORT_ASC
                ])
                ->all();

            if ($goods) {
                foreach ($goods as $key => $product) {
                    $productSizes = array_filter($modificationsSizes, function ($modificationsSizes) use ($product) {
                        return $modificationsSizes['product_id'] == $product->id;
                    });

                    $products[] = [
                        'id' => $product->id,
                        'model' => $product,
                        'name' => json_decode($product->name)->{Yii::$app->language},
                        'price' => (float)$modificationsPrices[$product->id],
                        'oldPrice' => (float)$modificationsOldPrices[$product->id],
                        'sizes' => ArrayHelper::map($productSizes, 'id', 'id'), // $productSizes ?: [],
                    ];
                }
            }
            
// echo VarDumper::dump(ArrayHelper::map($products, 'id', 'name'), 99, true); exit;
            
            $price = Yii::$app->request->get('price');
            if ($price) {
                $price = explode(';', $price);
                $products = array_filter($products, function ($product) use ($price) {
                    return $product['price'] >= (float) $price[0] && $product['price'] <= (float) $price[1];
                });
            }

            $sizes = Yii::$app->request->get('sizes');
            if ($sizes) {
                $products = array_filter($products, function ($product) use ($sizes) {
                    return !empty(array_intersect($product['sizes'], $sizes));
                });
            }
            
            $sort = Yii::$app->request->get('sort');
            if ($sort) {
                $isDesc = mb_substr($sort, 0, 1) == '-';
                $sortField = $isDesc ? mb_substr($sort, 1) : $sort;
                $sortDir = $isDesc ? SORT_DESC : SORT_ASC;
                ArrayHelper::multisort($products, [$sortField], [$sortDir]);
            }
        }
        
        Yii::$app->request->setQueryParams($baseQueryParams);
        
        return $this->render('category', [
            'products' => $products,
            'productsSizes' => $productsSizes,
            'productsPrices' => $productsPrices,
            'queryParams' => $queryParams,
        ]);
    }
    
    public function actionIndex($path = null)
    {
        $new = $popular = $sale = $promo = $category_id = null;
        $products = $productsIDs = $productsSizes = $productsPrices = [];

        $categories = Category::find()
            ->select([
                'id', 'parent_id', 'slug'
            ])
            ->asArray()
            ->all();
            
        if ($path) {
            $slugs = explode('/', $path);
            
            switch ($slugs[array_key_last($slugs)]) {
                case 'new':
                    $new = 1;
                    break;
                case 'popular':
                    $popular = 1;
                    break;
                case 'promo':
                    $promo = 1;
                    break;
                case 'sale':
                    $sale = 1;
                    break;
            }
            
            if (in_array(1, [$new, $popular, $promo, $sale])) {
                array_pop($slugs);
                $path = join('/', $slugs);
            }
            
            $category_id = Category::getByPath($categories, $path);

            $categoryChilds = Category::getAllChilds($categories, $category_id, 'id', true);

            $productCategories = Category::find()
                ->where([
                    'id' => $categoryChilds
                ])
                ->active()
                ->all();
                
            if ($productCategories) {
                foreach ($productCategories as $productCategory) {
                    if ($productCategory->products) {
                        foreach ($productCategory->products as $categoryProduct) {
                            $productsIDs[$categoryProduct->id] = $categoryProduct->id;
                        }
                    }
                }
            }
        } else {
            $productsIDs = Product::find()->active()->column();
        }

        if (!empty($productsIDs)) {
            $modifications = Product::getAllProductsPrices($productsIDs);
            $modificationsSizes = Product::getAllProductsSizes($productsIDs);
            $modificationsPrices = ArrayHelper::map($modifications, 'product_id', 'price');
            $modificationsOldPrices = ArrayHelper::map($modifications, 'product_id', 'price_old');
            $productsSizes = array_unique(ArrayHelper::map($modificationsSizes, 'id', 'value'));
            $productsPrices = array_unique($modificationsPrices);

            $goods = Product::find()
                ->where([
                    'id' => $productsIDs,
                ])
                ->active()
                ->andFilterWhere([
                    'new' => $new,
                    'promo' => $promo,
                    'popular' => $popular,
                    'sale' => $sale,
                ])
                ->filtered()
                ->orderBy([
                    'sort' => SORT_ASC
                ])
                ->all();

            if ($goods) {
                foreach ($goods as $key => $product) {
                    $productSizes = array_filter($modificationsSizes, function ($modificationsSizes) use ($product) {
                        return $modificationsSizes['product_id'] == $product->id;
                    });

                    $products[] = [
                        'id' => $product->id,
                        'model' => $product,
                        'name' => json_decode($product->name)->{Yii::$app->language},
                        'price' => (float)$modificationsPrices[$product->id],
                        'oldPrice' => (float)$modificationsOldPrices[$product->id],
                        'sizes' => ArrayHelper::map($productSizes, 'id', 'id'), // $productSizes ?: [],
                    ];
                }
            }
            
// echo VarDumper::dump(ArrayHelper::map($products, 'id', 'name'), 99, true); exit;
            
            $price = Yii::$app->request->get('price');
            if ($price) {
                $price = explode(';', $price);
                $products = array_filter($products, function ($product) use ($price) {
                    return $product['price'] >= (float) $price[0] && $product['price'] <= (float) $price[1];
                });
            }

            $sizes = Yii::$app->request->get('sizes');
            if ($sizes) {
                $products = array_filter($products, function ($product) use ($sizes) {
                    return !empty(array_intersect($product['sizes'], $sizes));
                });
            }
            
            $sort = Yii::$app->request->get('sort');
            if ($sort) {
                $isDesc = mb_substr($sort, 0, 1) == '-';
                $sortField = $isDesc ? mb_substr($sort, 1) : $sort;
                $sortDir = $isDesc ? SORT_DESC : SORT_ASC;
                ArrayHelper::multisort($products, [$sortField], [$sortDir]);
            }
        }

        return $this->render('category', [
            'products' => $products,
            'productsSizes' => $productsSizes,
            'productsPrices' => $productsPrices,
        ]);
    }
    
    public function actionIndex1($collectionSlug = null, $categorySlug = null)
    {
        $collIDs = [
            16, // 2021
            17, // 2021 дети
            9, // 2020
        ];
        
        if ($collectionSlug && !in_array(Category::findOne(['slug' => $collectionSlug])->id, $collIDs)) {
            $categorySlug = $collectionSlug;
            $collectionSlug = null;
        }
        
        $collectionsIDs = $collectionSlug ? [Category::findOne(['slug' => $collectionSlug])->id] : $collIDs;
        
        $category = $categorySlug ? Category::findOne(['slug' => $categorySlug]) : null;

        $collections = [];
        
// print_r($allProductsSizes);
        
        foreach ($collectionsIDs as $collectionID) {
            $collectionCategories = [];
            $collectionProductsIDs = [];
            $products = null;
            $allProductPrices = [];
            
            $collection = Category::findOne([
                'id' => $collectionID,
                'active' => 1
            ]);
            
            if ($collection) {
                $collectionProducts = $collection->products;
                
                if ($collectionProducts) {
                    $collectionCategoriesIDs = [];
                    
                    foreach ($collectionProducts as $collectionProduct) {
                        $collectionProductCategories = $collectionProduct->categories;
                        if ($collectionProductCategories) {
                            foreach ($collectionProductCategories as $collectionProductCategory) {
                                if ($collectionProductCategory->id != $collectionID) {
                                    $collectionCategoriesIDs[] = $collectionProductCategory->id;
                                }
                                if (!$categorySlug || $collectionProductCategory->slug == $categorySlug) {
                                    $collectionProductsIDs[] = $collectionProduct->id;
                                }
                            }
                        }
                    }
                    
                    $collectionCategoriesIDs = array_unique($collectionCategoriesIDs);

                    $collectionCategories = Category::find()
                        ->where([
                            'id' => array_unique($collectionCategoriesIDs),
                            'active' => 1,
                        ])
                        ->orderBy([
                            'sort' => SORT_ASC
                        ])
                        ->all();
                        
                    $modifications = Product::getAllProductsPrices($collectionProductsIDs);
                    
                    $modificationsSizes = Product::getAllProductsSizes($collectionProductsIDs);

                    $modificationsPrices = ArrayHelper::map($modifications, 'product_id', 'price');
                    $modificationsOldPrices = ArrayHelper::map($modifications, 'product_id', 'price_old');
                        
                    $goods = Product::find()
                        ->where([
                            'active' => 1,
                            'id' => $collectionProductsIDs
                        ]);
                        
                    if (Yii::$app->request->get('filter')) {
                        $goods = $goods->filtered();
                    }
                    
                    $goods = $goods->all();
                    
                    $products = [];
                    
                    if ($goods) {
                        foreach ($goods as $key => $product) {
                            // $productSizes = $product->getCartOptions()[1]['variants'];
// print_r($product->getCartOptions()[1]['variants']);
                            $productSizes = array_filter($modificationsSizes, function ($modificationsSizes) use ($product) {
                                return $modificationsSizes['product_id'] == $product->id;
                            });

                            $products[] = [
                                'model' => $product,
                                'name' => json_decode($product->name)->{Yii::$app->language},
                                'price' => (float) $modificationsPrices[$product->id],
                                'oldPrice' => (float) $modificationsOldPrices[$product->id],
                                'sizes' => ArrayHelper::map($productSizes, 'id', 'id'), // $productSizes ?: [],
                            ];
                        }
                    }
// echo \yii\helpers\VarDumper::dump($products, 99, true);
                    
                    $price = Yii::$app->request->get('price');
                    if ($price) {
                        $price = explode(';', $price);
                        $products = array_filter($products, function ($product) use ($price) {
                            return $product['price'] >= (float) $price[0] && $product['price'] <= (float) $price[1];
                        });
                    }

                    $sizes = Yii::$app->request->get('sizes');
                    if ($sizes) {
                        $products = array_filter($products, function ($product) use ($sizes) {
                            return !empty(array_intersect($product['sizes'], $sizes));
                        });
                    }
                    
                    $sort = Yii::$app->request->get('sort');
                    if ($sort) {
                        $isDesc = mb_substr($sort, 0, 1) == '-';
                        $sortField = $isDesc ? mb_substr($sort, 1) : $sort;
                        $sortDir = $isDesc ? SORT_DESC : SORT_ASC;
                        ArrayHelper::multisort($products, [$sortField], [$sortDir]);
                    }

                    $collections[$collectionID] = [
                        'collection' => $collection,
                        'subCategories' => $collectionCategories,
                        'products' => $products,
                        'productsSizes' => array_unique(ArrayHelper::map($modificationsSizes, 'id', 'value')),
                        'productsPrices' => array_unique($modificationsPrices),
                    ];
                }
            }
        }
        
        Yii::$app->params['currency'] = Languages::findOne([
            'code' => Yii::$app->language
        ])->currency;
        
        if ($collectionSlug && $categorySlug) {
            $title = json_decode($collection->name)->{Yii::$app->language} . ' - ' . json_decode($category->name)->{Yii::$app->language};
        } elseif ($categorySlug) {
            $title = json_decode($category->name)->{Yii::$app->language};
        } elseif ($collectionSlug) {
            $title = json_decode($collection->name)->{Yii::$app->language};
        } else {
            $title = Yii::t('front', 'Каталог');
        }

        return $this->render('index', [
            'collections' => $collections,
            'collectionSlug' => $collectionSlug,
            'categorySlug' => $categorySlug,
            'category' => $category,
            'title' => $title,
        ]);
    }
*/
    
}