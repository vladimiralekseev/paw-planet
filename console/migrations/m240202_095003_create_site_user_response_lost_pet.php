<?php

use yii\db\Migration;

/**
 * Class m240202_095003_create_site_user_response_lost_pet
 */
class m240202_095003_create_site_user_response_lost_pet extends Migration
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
            'response_lost_pet',
            [
                'id'               => $this->primaryKey(),
                'lost_pet_id'      => $this->integer()->notNull(),
                'request_owner_id' => $this->integer()->notNull(),
                'status'           => $this->string(64)->notNull(),
                'created_at'       => $this->datetime()->null(),
                'updated_at'       => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-response_lost_pet-lost_pet_id',
            'response_lost_pet',
            'lost_pet_id'
        );
        $this->addForeignKey(
            'fk-response_lost_pet-lost_pet_id',
            'response_lost_pet',
            'lost_pet_id',
            'lost_pet',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'idx-response_lost_pet-request_owner_id',
            'response_lost_pet',
            'request_owner_id'
        );
        $this->addForeignKey(
            'fk-response_lost_pet-request_owner_id',
            'response_lost_pet',
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
        $this->dropForeignKey('fk-response_lost_pet-request_owner_id', 'response_lost_pet');
        $this->dropForeignKey('fk-response_lost_pet-lost_pet_id', 'response_lost_pet');
        $this->dropTable('response_lost_pet');
    }
}
