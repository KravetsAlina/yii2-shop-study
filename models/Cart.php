<?php

namespace app\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
  public function behaviors()
   {
       return [
           'image' => [
               'class' => 'rico\yii2images\behaviors\ImageBehave',
           ]
       ];
   }
  //формируем корзину
  public function addToCart($product, $qty = 1)
  {
    //проверка если в массиве карт есть продукт со свойством id
    if(isset($_SESSION['cart'][$product->id])) {
      //если есть то добавл еще 1 товар такой же
      $_SESSION['cart'][$product->id]['qty'] += $qty;
    }else{
      //если такого товара нет то создаем масив
      $_SESSION['cart'][$product->id] = [
        'qty'   => $qty,
        'name'  => $product->name,
        'price' => $product->price,
        'img'   => $product->img,
      ];
    }
    //вывод кол-во и сумма
    //этого товара может и не быть по этому идет проверка. Если есть товар, то прибавить. Если нет, то положить
    $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
    //аналогично с суммой в корзине
    $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product->price : $qty * $product->price;
  }

  public function recalc($id)
  {
    //если не существует метода в масиве карт, то....
    if(!isset($_SESSION['cart'][$id])) return false;
    //пересчитываем итоговую сумму
    $qtyMinus = $_SESSION['cart'][$id]['qty'];
    $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];

    $_SESSION['cart.qty'] -= $qtyMinus;
    $_SESSION['cart.sum'] -= $sumMinus;
    //удаляем товар
    unset($_SESSION['cart'][$id]);
  }
}
