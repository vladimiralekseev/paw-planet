<?php

use yii\db\Migration;

/**
 * Class m240111_210131_create_pet_available
 */
class m240111_210131_create_pet_available extends Migration
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
            'pet_available',
            [
                'id'        => $this->primaryKey(),
                'pet_id'    => $this->integer()->notNull(),
                'day'       => $this->smallInteger(1)->notNull(),
                'available' => $this->integer()->notNull(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-pet_available-pet_id',
            'pet_available',
            'pet_id'
        );
        $this->addForeignKey(
            'fk-pet_available-pet_id',
            'pet_available',
            'pet_id',
            'pet',
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
        $this->dropTable('pet_available');
    }
}
