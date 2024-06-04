<?php

use yii\db\Migration;

/**
 * Class m240604_154650_add_subscribe_status
 */
class m240604_154650_add_subscribe_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('site_user', 'subscription_status', $this->string(16)->null());
        $this->createIndex(
            'idx-site_user-subscription_status',
            'site_user',
            'subscription_status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240604_154650_add_subscribe_status cannot be reverted.\n";

        return false;
    }
}
