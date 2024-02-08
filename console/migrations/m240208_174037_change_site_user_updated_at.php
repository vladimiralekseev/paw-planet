<?php

use yii\db\Migration;

/**
 * Class m240208_174037_change_site_user_updated_at
 */
class m240208_174037_change_site_user_updated_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('site_user', 'created_at', $this->datetime()->null());
        $this->alterColumn('site_user', 'updated_at', $this->datetime()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240208_174037_change_site_user_updated_at cannot be reverted.\n";

        return false;
    }
}
