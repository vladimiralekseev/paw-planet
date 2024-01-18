<?php

use yii\db\Migration;

/**
 * Class m240118_130836_create_reviews
 */
class m240118_130836_create_reviews extends Migration
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
            'review',
            [
                'id'                => $this->primaryKey(),
                'name'              => $this->string(128)->notNull(),
                'description'       => $this->string(512)->notNull(),
                'short_description' => $this->string(128)->null(),
                'user_img_id'       => $this->integer()->null(),
                'pet_img_id'        => $this->integer()->null(),
                'date'              => $this->datetime()->null(),
                'created_at'        => $this->datetime()->null(),
                'updated_at'        => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-review-user_img_id',
            'review',
            'user_img_id'
        );
        $this->addForeignKey(
            'fk-review-user_img_id',
            'review',
            'user_img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );
        $this->createIndex(
            'idx-review-pet_img_id',
            'review',
            'pet_img_id'
        );
        $this->addForeignKey(
            'fk-review-pet_img_id',
            'review',
            'pet_img_id',
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
        $this->dropForeignKey('fk-review-pet_img_id', 'review');
        $this->dropForeignKey('fk-review-user_img_id', 'review');
        $this->dropTable('review');
    }
}
