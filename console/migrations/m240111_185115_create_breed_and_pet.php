<?php

use yii\db\Migration;

/**
 * Class m240111_185115_create_breed_and_pet
 */
class m240111_185115_create_breed_and_pet extends Migration
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
            'breed',
            [
                'id'         => $this->primaryKey(),
                'name'       => $this->string(128)->notNull(),
                'created_at' => $this->datetime()->null(),
                'updated_at' => $this->datetime()->null(),
            ],
            $tableOptions
        );

        $this->createTable(
            'pet',
            [
                'id'            => $this->primaryKey(),
                'nickname'      => $this->string(128)->notNull(),
                'breed_id'      => $this->integer()->null(),
                'user_id'       => $this->integer()->null(),
                'img_id'        => $this->integer()->null(),
                'middle_img_id' => $this->integer()->null(),
                'small_img_id'  => $this->integer()->null(),
                'age'           => $this->integer()->null(),
                'for_borrow'    => $this->smallInteger(1)->defaultValue(1)->notNull(),
                'for_walk'      => $this->smallInteger(1)->defaultValue(1)->notNull(),
                'description'   => $this->string(1024)->null(),
                'needs'         => $this->string(1024)->null(),
                'good_with'     => $this->string(1024)->null(),
                'created_at'    => $this->datetime()->null(),
                'updated_at'    => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-pet-breed_id',
            'pet',
            'breed_id'
        );
        $this->addForeignKey(
            'fk-pet-breed_id',
            'pet',
            'breed_id',
            'breed',
            'id',
            'SET NULL',
            'SET NULL'
        );
        $this->createIndex(
            'idx-pet-user_id',
            'pet',
            'user_id'
        );
        $this->addForeignKey(
            'fk-pet-user_id',
            'pet',
            'user_id',
            'site_user',
            'id',
            'SET NULL',
            'SET NULL'
        );
        $this->createIndex(
            'idx-pet-img_id',
            'pet',
            'img_id'
        );
        $this->addForeignKey(
            'fk-pet-img_id',
            'pet',
            'img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );
        $this->createIndex(
            'idx-pet-small_img_id',
            'pet',
            'small_img_id'
        );
        $this->addForeignKey(
            'fk-pet-small_img_id',
            'pet',
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
        $this->dropTable('pet');
        $this->dropTable('breed');
    }
}
