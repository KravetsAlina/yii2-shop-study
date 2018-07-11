<?php
namespace app\controllers;

use app\models\Category;
use app\models\Product;
use Yii;
use yii\data\Pagination;

class CategoryController extends AppController {
    public function actionIndex()
    {
      //выводим популярные продукты
        $hits = Product::find()->where(['hit'=> '1'])->limit(6)->all();
        //title
        $this->setMeta('E-SHOPPER');
        // debug($hits);
        //Создает массив, содержащий названия переменных(указ нами) и их значения
        return $this->render('index', compact('hits'));
    }

//вывод страницы категории с товарами
    public function actionView($id)
    {
        $id = Yii::$app->request->get('id');
        // debug($id);
        // $products = Product::find()->where(['category_id'=>$id])->all();
        $query = Product::find()->where(['category_id'=>$id]);
        $pages = new Pagination(['totalCount'=>$query->count(), 'pageSize'=>3, 'forcePageParam'=>false, 'pageSizeParam'=>false]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        // debug($products);
        $category = Category::findOne($id);
        $this->setMeta('E-SHOPPER | ' . $category->name, $category->keywords, $category->description);
        return $this->render('view', compact('products', 'pages', 'category'));
    }
}
