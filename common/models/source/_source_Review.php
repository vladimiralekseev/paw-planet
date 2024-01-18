<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $short_description
 * @property int|null $user_img_id
 * @property int|null $pet_img_id
 * @property string|null $date
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Files $petImg
 * @property Files $userImg
 */
class _source_Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['user_img_id', 'pet_img_id'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['name', 'short_description'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 512],
            [['pet_img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['pet_img_id' => 'id']],
            [['user_img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['user_img_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'short_description' => 'Short Description',
            'user_img_id' => 'User Img ID',
            'pet_img_id' => 'Pet Img ID',
            'date' => 'Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PetImg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetImg()
    {
        return $this->hasOne(Files::class, ['id' => 'pet_img_id']);
    }

    /**
     * Gets query for [[UserImg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserImg()
    {
        return $this->hasOne(Files::class, ['id' => 'user_img_id']);
    }
}
