<?php

use common\models\Color;
use yii\db\Migration;

/**
 * Class m240205_150549_add_colors
 */
class m240205_150549_add_colors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $file = fopen(__DIR__ . '/_colors.csv', 'r');
        while (($line = fgetcsv($file)) !== false) {
            $name = ucwords(strtolower(trim($line[0])));
            $breed = new Color(['color' => $name]);
            $breed->save();
        }
        fclose($file);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240205_150549_add cannot be reverted.\n";

        return false;
    }
}
