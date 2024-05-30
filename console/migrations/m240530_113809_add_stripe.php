<?php

use yii\db\Migration;

/**
 * Class m240530_113809_add_stripe
 */
class m240530_113809_add_stripe extends Migration
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
            'stripe_log',
            [
                'id'           => $this->primaryKey(),
                'site_user_id' => $this->integer()->notNull(),
                'event'        => $this->string(64)->notNull(),
                'data'         => $this->text()->notNull(),
                'created_at'   => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-stripe_log-site_user_id',
            'stripe_log',
            'site_user_id'
        );
        $this->addForeignKey(
            'fk-stripe_log-site_user_id',
            'stripe_log',
            'site_user_id',
            'site_user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addColumn('site_user', 'stripe_customer_id', $this->string(64)->null());
        $this->addColumn('site_user', 'product_id', $this->integer()->null());
        $this->addColumn('site_user', 'product_expired_date', $this->datetime()->null());

        $this->createIndex(
            'idx-site_user-product_id',
            'site_user',
            'product_id'
        );
        $this->addForeignKey(
            'fk-site_user-product_id',
            'site_user',
            'product_id',
            'product',
            'id',
            'SET NULL',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240530_113809_add_stripe cannot be reverted.\n";

        return false;
    }
}
