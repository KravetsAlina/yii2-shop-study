<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180716_065436_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'created_at' =>$this->date(),
            'updated_at' =>$this->date(),
            'qty' =>$this->integer(),
            'sum' =>$this->float(),
            'status'=>$this->integer()->defaultValue(0),
            'name'=>$this->string(),
            'email'=>$this->string(),
            'phone'=>$this->string(),
            'address'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
