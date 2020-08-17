<?php

use yii\db\Migration;

/**
 * Class m191111_191453_create_tables
 */
class m200808_102353_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->string(255)   ->unique(),
            'first_name'    => $this->string(50)    ->defaultValue(null),
            'last_name'     => $this->string(50)    ->defaultValue(null),
            'language_code' => $this->string(10)    ->defaultValue('ru'),
            'params'        => $this->text(),
            'create'        => $this->timestamp()   ->defaultExpression('CURRENT_TIMESTAMP'),

        ]);

        $this->createTable('shop', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string(255)   ->unique(),
            'url'           => $this->text(),
            'update'        => $this->dateTime(),

        ]);

        $this->createTable('wrong_input', [
            'id'            => $this->primaryKey(),
            'shop_id'       => $this->integer()     ->notNull(),
            'second_name'   => $this->string(255),
        ]);

        $this->createTable('coupon', [
            'id'            => $this->primaryKey(),
            'shop_id'       => $this->integer(),
            'category'      => $this->string(255),
            'name'          => $this->string(255),
            'description'   => $this->text(),
            'coupon_id'     => $this->integer(),
            'logo'          => $this->text(),
            'promocode'     => $this->string(255),
            'promolink'     => $this->text(),
            'gotolink'      => $this->text(),
            'date_start'    => $this->dateTime(),
            'date_end'      => $this->dateTime(),
            'discount'      => $this->string(255),
            'create'        => $this->timestamp()   ->defaultExpression('CURRENT_TIMESTAMP'),

        ]);


        $this->createTable('logs', [
            'id'            => $this->primaryKey(),
            'type'          => $this->string(255)   ->notNull(),
            'event'         => $this->string(255)   ->notNull(),
            'target'        => $this->string(255)   ->notNull(),
            'result'        => $this->text()        ->notNull(),
            'create'        => $this->timestamp()   ->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('logs');
        $this->dropTable('coupon');
        $this->dropTable('wrong_input');
        $this->dropTable('shop');
        $this->dropTable('users');
    }

    
    // Use up()/down() to run migration code without a transaction.
    /*public function up()
    {   
        

    }

    public function down()
    {
        echo "m191111_191453_create_tables cannot be reverted.\n";

        return false;
    }*/
    
}
