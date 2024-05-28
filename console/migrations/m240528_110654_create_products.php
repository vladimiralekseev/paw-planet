<?php

use yii\db\Migration;

/**
 * Class m240528_110654_create_products
 */
class m240528_110654_create_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            'product',
            [
                'id'                => $this->primaryKey(),
                'name'              => $this->string(128)->notNull(),
                'stripe_product_id' => $this->string(64)->notNull(),
                'status'            => $this->smallInteger(1)->notNull(),
                'type'              => $this->string(64)->notNull(),
                'period'             => $this->integer()->notNull(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-product-stripe_product_id',
            'product',
            'stripe_product_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(
            'product'
        );
    }
}
