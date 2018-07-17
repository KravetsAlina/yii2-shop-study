<?php
namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Product;

class ProductController extends AppController
{
  public function actionView($id)
  {
    //получ из масива гет либо параметром в этой ф-ции
    // $id = Yii::$app->request->get('id');

    //исключение. Пользователь запрашивает несуществ id категории
    //получ из БД всю инфо по данному товару
    //ленивая загрузка
    $product = Product::findOne($id);

    if(empty($product))
    {
      throw new \yii\web\HttpException(404, 'Такого товара не существует');
    }

    //получ из БД всю инфо по данному товару
    //ленивая загрузка
    $product = Product::findOne($id);

    //жадная загрузка
    // $product = Product::find()->with('category')->where(['id'=> $id])->limit(1)->one();

    //выводим популярные продукты
      $hits = Product::find()->where(['hit'=> '1'])->limit(6)->all();
      $this->setMeta('E-SHOPPER | ' . $product->name, $product->keywords, $product->description);
    return $this->render('view', compact('product', 'hits'));
  }

  
}
