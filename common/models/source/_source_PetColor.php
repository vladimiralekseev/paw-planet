<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pet_color".
 *
 * @property int $id
 * @property int $color_id
 * @property int $lost_pet_id
 * @property string|null $created_at
 *
 * @property Color $color
 * @property LostPet $lostPet
 */
class _source_PetColor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pet_color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color_id', 'lost_pet_id'], 'required'],
            [['color_id', 'lost_pet_id'], 'integer'],
            [['created_at'], 'safe'],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::class, 'targetAttribute' => ['color_id' => 'id']],
            [['lost_pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => LostPet::class, 'targetAttribute' => ['lost_pet_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_id' => 'Color ID',
            'lost_pet_id' => 'Lost Pet ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Color]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::class, ['id' => 'color_id']);
    }

    /**
     * Gets query for [[LostPet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLostPet()
    {
        return $this->hasOne(LostPet::class, ['id' => 'lost_pet_id']);
    }
}
