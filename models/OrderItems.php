<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $name
 * @property double $price
 * @property int $qty_item
 * @property double $sum_item
 */
class OrderItems extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_items';
    }

    //связь таблиц в БД
     public function getOrder()
     {
       return $this->hasOne(Order::className(), ['id'=>'order_id']);
     }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'qty_item'], 'integer'],
            [['order_id', 'product_id', 'qty_item'], 'required'],
            [['price', 'sum_item'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */

}
