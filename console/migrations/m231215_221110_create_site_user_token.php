<?php

use yii\db\Migration;

/**
 * Class m231215_221110_create_site_user_token
 */
class m231215_221110_create_site_user_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(
            'site_user_token',
            [
                'id'           => $this->primaryKey(),
                'site_user_id' => $this->integer()->notNull(),
                'token'        => $this->string(256)->notNull(),
                'created_at'   => $this->datetime()->null(),
                'expired_at'   => $this->datetime()->null(),
            ],
            $tableOptions
        );

        $this->createIndex(
            'idx-site_user_token-site_user_id',
            'site_user_token',
            'site_user_id'
        );

        $this->addForeignKey(
            'fk-site_user_token-site_user_id',
            'site_user_token',
            'site_user_id',
            'site_user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('site_user_token');
    }
}
