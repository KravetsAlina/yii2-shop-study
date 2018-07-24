<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{

  public function behaviors()
   {
       return [
           'image' => [
               'class' => 'rico\yii2images\behaviors\ImageBehave',
           ]
       ];
   }

  //связь табл и модели
  public static function tableName()
  {
    return 'product';
  }

  public function getCategory()
  {
    //один продукт одна категория
    return $this->hasOne(Product::className(), [
      'id' => 'category_id',
    ]);
  }
}
