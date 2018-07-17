<?php
namespace app\controllers;

use Yii;
use app\models\Cart;
use app\models\Product;
use app\models\Order;
use app\models\OrderItems;

class CartController extends AppController
{
  public function actionAdd()
  {
    //определяем id товара
    $id = Yii::$app->request->get('id');
    //получ кол-во со страницы одиночного продукта от пользователя
    $qty = (int)Yii::$app->request->get('qty');
    $qty = !$qty ? 1 : $qty;
    // debug($id);

    //чтобы пользователь не занес в БД корзины что то не верное.
    //находим соответств в БД. А если пусто то ничего не делать
    $product = Product::findOne($id);
    if(empty($product)) return false;
    // debug($product);

    //создаем сессию
    $session = Yii::$app->session;
    $session->open();

    //объект модели Cart
    $cart = new Cart();
    $cart->addToCart($product, $qty);

    //если у пользователя отключ jQuery.Ф-ция для корзины
    if(!Yii::$app->request->isAjax) {
      //хранится адрес url с котор пришел пользователь. туда и редиректим его
      return $this->redirect(Yii::$app->request->referrer);
    }

    // debug($session['cart']);

    //вернуть вид
    $this->layout = false;  //убрать шаблон html
    return $this->render('cart-modal', compact('session'));
  }

  //очистить корзину
  public function actionClear()
  {
    //создаем сессию
    $session = Yii::$app->session;
    $session->open();
    // debug($session);
    //очищаем сессию
    $session->remove('cart');
    $session->remove('cart.qty');
    $session->remove('cart.sum');
    //вернуть вид
    $this->layout = false;  //убрать шаблон html
    return $this->render('cart-modal', compact('session'));
  }

//удаление из корзины товара
  public function actionDelItem()
  {
    //получ id товара
    $id = Yii::$app->request->get('id');
    //открываем сессию
    $session = Yii::$app->session;
    $session->open();

    //обращаемся к модели карт
    $cart = new Cart();
    //пересчет в модели цен
    $cart->recalc($id);

    //вернуть вид
    $this->layout = false;  //убрать шаблон html
    return $this->render('cart-modal', compact('session'));
  }

//поазать корзину по нажатию кнопки меню (js)
  public function actionShow()
  {
    //открываем сессию
    $session = Yii::$app->session;
    $session->open();
    //вернуть вид
    $this->layout = false;  //убрать шаблон html
    return $this->render('cart-modal', compact('session'));
  }

//страница корзина для пользователя
  public function actionView()
  {
    //открываем сессию
    $session = Yii::$app->session;
    $session->open();
    //заголовок
    $this->setMeta('Страница заказа');

    $order = new Order();
    //проверяем пришли ли данные от пользователя во время заказа
    if($order->load(Yii::$app->request->post()) )
    {
      //к данным пользователя добавл кол-во и сумму
      $order->qty = $session['cart.qty'];
      $order->sum = $session['cart.sum'];
      //сохранение в БД
      if($order->save()){
        //в случае успешного сохранен данных в БД
        $this->saveOrderItems($session['cart'], $order->id);

        Yii::$app->session->setFlash('success', 'Ваш заказ принят в обработку. Ждите звонка менеджера');

//заказ на почту клиента
        $message = Yii::$app->mailer->compose('order', ['session' => $session])
        //от кого почта
                  ->setFrom(['test@mail.ru'=>'E-Shop.test'])
                  ->setTo($order->email) //email из формы от пользователя
                  ->setSubject('Заказ с сайта')
                  ->send();
//заказ на почту admin
        $message = Yii::$app->mailer->compose('order', ['session' => $session])
                          //от кого почта
                  ->setFrom(['alinakravets2017@gmail.com'=>'E-Shop.test'])
                  ->setTo(Yii::$app->params['adminEmail']) //email из формы от пользователя
                  ->setSubject('Заказ с сайта')
                  ->send();

        //удалить содержим корзины перед обновл
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        //тут можно использ механизм транзакц, если что то пошло не так. Откатить действия. В разделе Актив Рекордс

        //автоматич обновл страницы
        return $this->refresh();
      }else{
        Yii::$app->session->setFlash('success', 'Ошибка оформления заказа');
      }
    }
    return $this->render('view', compact('session', 'order'));
  }

    protected function saveOrderItems($items, $order_id)
    {
      foreach($items as $id => $item)
      {
        //имеет дело с ActivRecords. Кажд его класс соответств отдельн колонке в табл
        //по этому созд объект для каждой записи
        $order_items = new OrderItems();
        $order_items->order_id = $order_id;
        $order_items->product_id = $id;
        $order_items->name = $item['name'];
        $order_items->qty_item = $item['qty'];
        $order_items->price = $item['price'];
        $order_items->sum_item = $item['qty'] * $item['price'];
        $order_items->save();
      }
    }
}
