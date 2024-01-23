<?php

use yii\db\Migration;

/**
 * Class m240122_122223_create_lost_pet
 */
class m240122_122223_create_lost_pet extends Migration
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
            'lost_pet',
            [
                'id'            => $this->primaryKey(),
                'nickname'      => $this->string(128)->notNull(),
                'breed_id'      => $this->integer()->null(),
                'user_id'       => $this->integer()->notNull(),
                'img_id'        => $this->integer()->null(),
                'middle_img_id' => $this->integer()->null(),
                'small_img_id'  => $this->integer()->null(),
                'age'           => $this->integer()->null(),
                'type'          => $this->string(8)->notNull(),
                'latitude'      => $this->decimal(17, 13)->defaultValue(null)->null(),
                'longitude'     => $this->decimal(17, 13)->defaultValue(null)->null(),
                'country'       => $this->string(64)->null(),
                'state'         => $this->string(64)->null(),
                'city'          => $this->string(64)->null(),
                'address'       => $this->string(64)->null(),
                'when'          => $this->datetime()->null(),
                'created_at'    => $this->datetime()->null(),
                'updated_at'    => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-lost_pet-breed_id',
            'lost_pet',
            'breed_id'
        );
        $this->addForeignKey(
            'fk-lost_pet-breed_id',
            'lost_pet',
            'breed_id',
            'breed',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->createIndex(
            'idx-lost_pet-user_id',
            'lost_pet',
            'user_id'
        );
        $this->addForeignKey(
            'fk-lost_pet-user_id',
            'lost_pet',
            'user_id',
            'site_user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-lost_pet-img_id',
            'lost_pet',
            'img_id'
        );
        $this->addForeignKey(
            'fk-lost_pet-img_id',
            'lost_pet',
            'img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->createIndex(
            'idx-lost_pet-middle_img_id',
            'lost_pet',
            'middle_img_id'
        );
        $this->addForeignKey(
            'fk-lost_pet-middle_img_id',
            'lost_pet',
            'middle_img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->createIndex(
            'idx-lost_pet-small_img_id',
            'lost_pet',
            'small_img_id'
        );
        $this->addForeignKey(
            'fk-lost_pet-small_img_id',
            'lost_pet',
            'small_img_id',
            'files',
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
        $this->dropForeignKey('fk-lost_pet-small_img_id', 'lost_pet');
        $this->dropForeignKey('fk-lost_pet-middle_img_id', 'lost_pet');
        $this->dropForeignKey('fk-lost_pet-img_id', 'lost_pet');
        $this->dropForeignKey('fk-lost_pet-user_id', 'lost_pet');
        $this->dropForeignKey('fk-lost_pet-breed_id', 'lost_pet');
        $this->dropTable('lost_pet');
    }
}
