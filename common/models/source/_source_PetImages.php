<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pet_images".
 *
 * @property int $id
 * @property int $pet_id
 * @property int $small_img_id
 * @property int $middle_img_id
 * @property int $img_id
 *
 * @property Pet $pet
 */
class _source_PetImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pet_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pet_id', 'small_img_id', 'middle_img_id', 'img_id'], 'required'],
            [['pet_id', 'small_img_id', 'middle_img_id', 'img_id'], 'integer'],
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
            'small_img_id' => 'Small Img ID',
            'middle_img_id' => 'Middle Img ID',
            'img_id' => 'Img ID',
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
