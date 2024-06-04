<?php

use yii\db\Migration;

/**
 * Class m240604_123633_add_subscription_trial_period
 */
class m240604_123633_add_subscription_trial_period extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'trial_days', $this->string(64)->null());
        $this->addColumn('site_user', 'stripe_trial_is_used', $this->integer(1)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240604_123633_add_subscription_trial_period cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240604_123633_add_subscription_trial_period cannot be reverted.\n";

        return false;
    }
    */
}
