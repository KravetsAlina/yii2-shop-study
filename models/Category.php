<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{

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
