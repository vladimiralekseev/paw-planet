<?php

use yii\db\Migration;

/**
 * Class m240116_232706_create_user_request_pet
 */
class m240116_232706_create_user_request_pet extends Migration
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
            'user_request_pet',
            [
                'id'               => $this->primaryKey(),
                'pet_id'           => $this->integer()->notNull(),
                'request_owner_id' => $this->integer()->notNull(),
                'type'             => $this->string(64)->notNull(),
                'status'           => $this->string(64)->notNull(),
                'created_at'       => $this->datetime()->null(),
                'updated_at'       => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-user_request_pet-pet_id',
            'user_request_pet',
            'pet_id'
        );
        $this->addForeignKey(
            'fk-user_request_pet-pet_id',
            'user_request_pet',
            'pet_id',
            'pet',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'idx-user_request_pet-request_owner_id',
            'user_request_pet',
            'request_owner_id'
        );
        $this->addForeignKey(
            'fk-user_request_pet-request_owner_id',
            'user_request_pet',
            'request_owner_id',
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
        $this->dropForeignKey('fk-user_request_pet-request_owner_id', 'user_request_pet');
        $this->dropForeignKey('fk-user_request_pet-pet_id', 'user_request_pet');
        $this->dropTable('user_request_pet');
    }
}
