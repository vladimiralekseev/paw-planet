<?php

use yii\db\Migration;

/**
 * Class m240123_085303_change_lost_pet_field
 */
class m240123_085303_change_lost_pet_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('lost_pet', 'address', $this->string(128)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('lost_pet', 'address', $this->string(64)->null());
    }
}
