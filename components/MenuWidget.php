<?php
//меню категории
namespace app\components;

use yii\base\Widget;
use app\models\Category;
use Yii;


class MenuWidget extends Widget
{
  public $tpl;
  //масив категорий котор получ из БД категории
  public $data;
  //хранит результат работы ф-ции. Делает из обычного массива масив-дерево(будет видна вложенность)
  public $tree;
  //готовый код в зависимости от свойства $tpl
  public $menuHtml;
  //переменная из category _form.php MenuWidget
  public $model;

  public function init()
  {
    parent::init();
    if($this->tpl == null)
    {
      $this->tpl = 'Menu';
    }
    $this->tpl.= '.php';
  }

  public function run()
  {
    //кешируем
    if($this->tpl == 'menu.php')
    {
      $menu = Yii::$app->cache->get('menu');
      if($menu) return $menu;
    }
    //вернет масив
    //indexBy указ какую колонку использ для индексиров массива
    $this->data = Category::find()->indexBy('id')->asArray()->all();
    $this->tree = $this->getTree();
    $this->menuHtml = $this->getMenuHtml($this->tree);

    if($this->tpl == 'menu.php')
    {
      Yii::$app->cache->set('menu', $this->menuHtml, 60);
    }
    // debug($this->tree);
    // return $this->tpl;
    return $this->menuHtml;
  }

  //получаем дерево
  protected function getTree()
  {
    $tree = [];
    foreach($this->data as $id=>&$node){
        if(!$node['parent_id'])
            $tree[$id] = &$node;
        else
            $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
    }
    return $tree;
  }

  //add to html
  //отступ в списке категории
  protected function getMenuHtml($tree, $tab = '')
  {
    $str = '';
    foreach ($tree as $category)
    {
      $str .= $this->catToTemplate($category, $tab);
    }
    return $str;
  }

  //collection
  protected function catToTemplate($category, $tab)
  {
    //буфер вывода
    ob_start();
    include __DIR__ . '/menu_tpl/' . $this->tpl;
    return ob_get_clean();
  }
}
