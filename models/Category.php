<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
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
    return 'category';
  }

  public function getProducts()
  {
    //связь с табл Продукт в БД. 2 парметра. И созд отдель Продукт
    return $this->hasMany(Product::className(), [
      'category_id' => 'id',
    ]);
  }
}
