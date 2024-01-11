<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pet_available".
 *
 * @property int $id
 * @property int $pet_id
 * @property int $day
 * @property int $available
 *
 * @property Pet $pet
 */
class _source_PetAvailable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pet_available';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pet_id', 'day', 'available'], 'required'],
            [['pet_id', 'day', 'available'], 'integer'],
            [['pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pet::class, 'targetAttribute' => ['pet_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pet_id' => 'Pet ID',
            'day' => 'Day',
            'available' => 'Available',
        ];
    }

    /**
     * Gets query for [[Pet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPet()
    {
        return $this->hasOne(Pet::class, ['id' => 'pet_id']);
    }
}
