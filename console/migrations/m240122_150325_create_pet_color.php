<?php

use yii\db\Migration;

/**
 * Class m240122_150325_create_pet_color
 */
class m240122_150325_create_pet_color extends Migration
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
            'color',
            [
                'id'         => $this->primaryKey(),
                'color'      => $this->string(128)->notNull(),
                'created_at' => $this->datetime()->null(),
                'updated_at' => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createTable(
            'pet_color',
            [
                'id'          => $this->primaryKey(),
                'color_id'    => $this->integer()->notNull(),
                'lost_pet_id' => $this->integer()->notNull(),
                'created_at'  => $this->datetime()->null(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-pet_color-color_id',
            'pet_color',
            'color_id'
        );
        $this->addForeignKey(
            'fk-pet_color-color_id',
            'pet_color',
            'color_id',
            'color',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'idx-pet_color-lost_pet_id',
            'pet_color',
            'lost_pet_id'
        );
        $this->addForeignKey(
            'fk-pet_color-lost_pet_id',
            'pet_color',
            'lost_pet_id',
            'lost_pet',
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
        $this->dropForeignKey('fk-pet_color-lost_pet_id', 'pet_color');
        $this->dropForeignKey('fk-pet_color-color_id', 'pet_color');
        $this->dropTable('pet_color');
        $this->dropTable('color');
    }
}
