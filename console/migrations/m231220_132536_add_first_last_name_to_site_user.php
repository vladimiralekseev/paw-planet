<?php

use yii\db\Migration;

/**
 * Class m231220_132536_add_first_last_name_to_site_user
 */
class m231220_132536_add_first_last_name_to_site_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('site_user', 'last_name', $this->string(128)->defaultValue(null)->after('username')->null());
        $this->addColumn('site_user', 'first_name', $this->string(128)->after('username')->notNull());
        $this->addColumn('site_user', 'phone_number', $this->string(64)->defaultValue(null)->null());
        $this->addColumn('site_user', 'about', $this->text()->defaultValue(null)->null());
        $this->addColumn('site_user', 'my_location', $this->text()->defaultValue(null)->null());
        $this->addColumn('site_user', 'latitude', $this->decimal(17, 13)->defaultValue(null)->null());
        $this->addColumn('site_user', 'longitude', $this->decimal(17, 13)->defaultValue(null)->null());
        $this->addColumn('site_user', 'whats_app', $this->string(256)->defaultValue(null)->null());
        $this->addColumn('site_user', 'facebook', $this->string(256)->defaultValue(null)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('site_user', 'last_name');
        $this->dropColumn('site_user', 'first_name');
        $this->dropColumn('site_user', 'phone_number');
        $this->dropColumn('site_user', 'about');
        $this->dropColumn('site_user', 'my_location');
        $this->dropColumn('site_user', 'latitude');
        $this->dropColumn('site_user', 'longitude');
        $this->dropColumn('site_user', 'whats_app');
        $this->dropColumn('site_user', 'facebook');
    }
}
