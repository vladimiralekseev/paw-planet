<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "response_lost_pet".
 *
 * @property int $id
 * @property int $lost_pet_id
 * @property int $request_owner_id
 * @property string $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property LostPet $lostPet
 * @property SiteUser $requestOwner
 */
class _source_ResponseLostPet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response_lost_pet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lost_pet_id', 'request_owner_id', 'status'], 'required'],
            [['lost_pet_id', 'request_owner_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 64],
            [['lost_pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => LostPet::class, 'targetAttribute' => ['lost_pet_id' => 'id']],
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
            'lost_pet_id' => 'Lost Pet ID',
            'request_owner_id' => 'Request Owner ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
