<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $dir
 * @property string $path
 * @property string $file_name
 * @property string $file_source_name
 * @property int $file_source_time
 * @property string|null $file_source_url
 * @property string|null $created_at
 *
 * @property LostPet[] $lostPets
 * @property LostPet[] $lostPets0
 * @property LostPet[] $lostPets1
 * @property Pet[] $pets
 * @property Pet[] $pets0
 * @property Pet[] $pets1
 * @property Review[] $reviews
 * @property Review[] $reviews0
 * @property SiteUser[] $siteUsers
 * @property SiteUser[] $siteUsers0
 */
class _source_Files extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dir', 'path', 'file_name', 'file_source_name'], 'required'],
            [['file_source_time'], 'integer'],
            [['created_at'], 'safe'],
            [['dir'], 'string', 'max' => 32],
            [['path'], 'string', 'max' => 64],
            [['file_name', 'file_source_name'], 'string', 'max' => 128],
            [['file_source_url'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dir' => 'Dir',
            'path' => 'Path',
            'file_name' => 'File Name',
            'file_source_name' => 'File Source Name',
            'file_source_time' => 'File Source Time',
            'file_source_url' => 'File Source Url',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[LostPets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLostPets()
    {
        return $this->hasMany(LostPet::class, ['img_id' => 'id']);
    }

    /**
     * Gets query for [[LostPets0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLostPets0()
    {
        return $this->hasMany(LostPet::class, ['middle_img_id' => 'id']);
    }

    /**
     * Gets query for [[LostPets1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLostPets1()
    {
        return $this->hasMany(LostPet::class, ['small_img_id' => 'id']);
    }

    /**
     * Gets query for [[Pets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets()
    {
        return $this->hasMany(Pet::class, ['img_id' => 'id']);
    }

    /**
     * Gets query for [[Pets0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets0()
    {
        return $this->hasMany(Pet::class, ['middle_img_id' => 'id']);
    }

    /**
     * Gets query for [[Pets1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets1()
    {
        return $this->hasMany(Pet::class, ['small_img_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['pet_img_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Review::class, ['user_img_id' => 'id']);
    }

    /**
     * Gets query for [[SiteUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUsers()
    {
        return $this->hasMany(SiteUser::class, ['img_id' => 'id']);
    }

    /**
     * Gets query for [[SiteUsers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUsers0()
    {
        return $this->hasMany(SiteUser::class, ['small_img_id' => 'id']);
    }
}
