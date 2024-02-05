<?php

use yii\db\Migration;

/**
 * Class m240205_102223_add_status_to_pet
 */
class m240205_102223_add_status_to_pet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pet', 'status', $this->smallInteger()->notNull()->defaultValue('1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pet', 'status');
    }
}
