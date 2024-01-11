<?php

use yii\db\Migration;

/**
 * Class m240111_210518_create_pet_images
 */
class m240111_210518_create_pet_images extends Migration
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
            'pet_images',
            [
                'id'            => $this->primaryKey(),
                'pet_id'        => $this->integer()->notNull(),
                'small_img_id'  => $this->integer()->notNull(),
                'middle_img_id' => $this->integer()->notNull(),
                'img_id'        => $this->integer()->notNull(),
            ],
            $tableOptions
        );
        $this->createIndex(
            'idx-pet_images-pet_id',
            'pet_images',
            'pet_id'
        );
        $this->addForeignKey(
            'fk-pet_images-pet_id',
            'pet_images',
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
        $this->dropTable('pet_images');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240111_210518_create_pet_images cannot be reverted.\n";

        return false;
    }
    */
}
