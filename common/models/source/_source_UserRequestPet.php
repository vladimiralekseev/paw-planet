<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_request_pet".
 *
 * @property int $id
 * @property int $pet_id
 * @property int $request_owner_id
 * @property string $type
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Pet $pet
 * @property SiteUser $requestOwner
 */
class _source_UserRequestPet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_request_pet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pet_id', 'request_owner_id', 'type', 'status'], 'required'],
            [['pet_id', 'request_owner_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'status'], 'string', 'max' => 64],
            [['pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pet::class, 'targetAttribute' => ['pet_id' => 'id']],
            [['request_owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteUser::class, 'targetAttribute' => ['request_owner_id' => 'id']],
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
            'request_owner_id' => 'Request Owner ID',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    /**
     * Gets query for [[RequestOwner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestOwner()
    {
        return $this->hasOne(SiteUser::class, ['id' => 'request_owner_id']);
    }
}
