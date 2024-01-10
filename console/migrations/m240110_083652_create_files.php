<?php

use yii\db\Migration;

/**
 * Class m240110_083652_create_files
 */
class m240110_083652_create_files extends Migration
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
            'files',
            [
                'id'               => $this->primaryKey(),
                'dir'              => $this->string(32)->notNull(),
                'path'             => $this->string(64)->notNull(),
                'file_name'        => $this->string(128)->notNull(),
                'file_source_name' => $this->string(128)->notNull(),
                'file_source_time' => $this->integer(11)->notNull()->defaultValue(0),
                'file_source_url'  => $this->string(256)->null(),
                'created_at'       => $this->datetime()->null(),
            ],
            $tableOptions
        );

        $this->addColumn('site_user', 'img_id', $this->integer()->defaultValue(null)->null());
        $this->addColumn('site_user', 'small_img_id', $this->integer()->defaultValue(null)->null());
        $this->createIndex(
            'idx-site_user-img_id',
            'site_user',
            'img_id'
        );
        $this->createIndex(
            'idx-site_user-small_img_id',
            'site_user',
            'small_img_id'
        );

        $this->addForeignKey(
            'fk-site_user-img_id',
            'site_user',
            'img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-site_user-small_img_id',
            'site_user',
            'small_img_id',
            'files',
            'id',
            'SET NULL',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-site_user-img_id','site_user');
        $this->dropForeignKey('fk-site_user-small_img_id','site_user');
        $this->dropColumn('site_user', 'img_id');
        $this->dropColumn('site_user', 'small_img_id');
        $this->dropTable('files');
    }
}
