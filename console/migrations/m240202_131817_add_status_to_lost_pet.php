<?php

use yii\db\Migration;

/**
 * Class m240202_131817_add_status_to_lost_pet
 */
class m240202_131817_add_status_to_lost_pet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('lost_pet', 'status', $this->smallInteger()->notNull()->defaultValue('1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('lost_pet', 'status');
    }
}
