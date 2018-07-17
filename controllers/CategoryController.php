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
        //получ из масива гет либо параметром в этой ф-ции
        // $id = Yii::$app->request->get('id');

        //исключение. Пользователь запрашивает несуществ id категории
        $category = Category::findOne($id);

        if(empty($category))
        {
          throw new \yii\web\HttpException(404, 'Данной категории товара не существует');
        }
        // debug($id);
        // $products = Product::find()->where(['category_id'=>$id])->all();
        $query = Product::find()->where(['category_id'=>$id]);
        $pages = new Pagination(['totalCount'=>$query->count(), 'pageSize'=>3, 'forcePageParam'=>false, 'pageSizeParam'=>false]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        // debug($products);

        //формирование заголовка title
        $this->setMeta('E-SHOPPER | ' . $category->name, $category->keywords, $category->description);
        return $this->render('view', compact('products', 'pages', 'category'));
    }

    //Поиск товаров
    public function actionSearch() {
      $q = trim(Yii::$app->request->get('q'));
      
      //формирование заголовка title
        $this->setMeta('E-SHOPPER | ' . $q);

      //при пустом запросе в поисковик
      if(!$q)
      {
        return $this->render('search');
      }

      $query = Product::find()->where(['like', 'name', $q]);
      $pages = new Pagination(['totalCount'=>$query->count(), 'pageSize'=>3, 'forcePageParam'=>false, 'pageSizeParam'=>false]);
      $products = $query->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('search', compact('products', 'pages', 'q'));
    }
}
