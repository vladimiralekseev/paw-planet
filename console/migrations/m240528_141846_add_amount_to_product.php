<?php

use yii\db\Migration;

/**
 * Class m240528_141846_add_amount_to_product
 */
class m240528_141846_add_amount_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'amount', $this->integer()->notNull()->defaultValue('0'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240528_141846_add_amount_to_product cannot be reverted.\n";

        return false;
    }
}
