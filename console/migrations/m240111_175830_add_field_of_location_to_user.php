<?php

use yii\db\Migration;

/**
 * Class m240111_175830_add_field_of_location_to_user
 */
class m240111_175830_add_field_of_location_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('site_user', 'country', $this->string(64)->null());
        $this->addColumn('site_user', 'state', $this->string(64)->null());
        $this->addColumn('site_user', 'city', $this->string(64)->null());
        $this->addColumn('site_user', 'address', $this->string(128)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('site_user', 'country');
        $this->dropColumn('site_user', 'state');
        $this->dropColumn('site_user', 'city');
        $this->dropColumn('site_user', 'address');
    }
}
