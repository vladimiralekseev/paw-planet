<?php

use yii\db\Migration;
use yii\db\Query;

class m130524_201442_init extends Migration
{
    public function up()
    {
        try {
            $rows = (new Query())
                ->select(['id'])
                ->from('user')
                ->limit(1)
                ->all();
        } catch (Exception $e) {
            var_export($e->getMessage());
            throw new RuntimeException(
                $e->getMessage() . "\n\n" .
                "May be should run `php yii migrate --migrationPath=vendor/webvimark/module-user-management/migrations/`"
            );
        }
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('site_user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('site_user');
    }
}
