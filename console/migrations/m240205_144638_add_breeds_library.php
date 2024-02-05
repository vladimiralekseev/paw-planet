<?php

use common\models\Breed;
use yii\db\Migration;

/**
 * Class m240205_144638_add_breeds_library
 */
class m240205_144638_add_breeds_library extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $file = fopen(__DIR__ . '/_breeds-list.csv', 'r');
        while (($line = fgetcsv($file)) !== false) {
            $name = ucwords(strtolower(trim($line[0])));
            $breed = new Breed(['name' => $name]);
            $breed->save();
        }
        fclose($file);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240205_144638_add_breeds_library cannot be reverted.\n";

        return false;
    }
}
