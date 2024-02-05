<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lost_pet".
 *
 * @property int $id
 * @property string $nickname
 * @property int|null $breed_id
 * @property int $user_id
 * @property int|null $img_id
 * @property int|null $middle_img_id
 * @property int|null $small_img_id
 * @property int|null $age
 * @property string $type
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string $address
 * @property string|null $when
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 *
 * @property Breed $breed
 * @property Files $img
 * @property Files $middleImg
 * @property PetColor[] $petColors
 * @property ResponseLostPet[] $responseLostPets
 * @property Files $smallImg
 * @property SiteUser $user
 */
class _source_LostPet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lost_pet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname', 'user_id', 'type', 'address'], 'required'],
            [['breed_id', 'user_id', 'img_id', 'middle_img_id', 'small_img_id', 'age', 'status'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['when', 'created_at', 'updated_at'], 'safe'],
            [['nickname', 'address'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 8],
            [['country', 'state', 'city'], 'string', 'max' => 64],
            [['breed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Breed::class, 'targetAttribute' => ['breed_id' => 'id']],
            [['img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['img_id' => 'id']],
            [['middle_img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['middle_img_id' => 'id']],
            [['small_img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['small_img_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteUser::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'breed_id' => 'Breed ID',
            'user_id' => 'User ID',
            'img_id' => 'Img ID',
            'middle_img_id' => 'Middle Img ID',
            'small_img_id' => 'Small Img ID',
            'age' => 'Age',
            'type' => 'Type',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'address' => 'Address',
            'when' => 'When',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Breed]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBreed()
    {
        return $this->hasOne(Breed::class, ['id' => 'breed_id']);
    }

    /**
     * Gets query for [[Img]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImg()
    {
        return $this->hasOne(Files::class, ['id' => 'img_id']);
    }

    /**
     * Gets query for [[MiddleImg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMiddleImg()
    {
        return $this->hasOne(Files::class, ['id' => 'middle_img_id']);
    }

    /**
     * Gets query for [[PetColors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetColors()
    {
        return $this->hasMany(PetColor::class, ['lost_pet_id' => 'id']);
    }

    /**
     * Gets query for [[ResponseLostPets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponseLostPets()
    {
        return $this->hasMany(ResponseLostPet::class, ['lost_pet_id' => 'id']);
    }

    /**
     * Gets query for [[SmallImg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmallImg()
    {
        return $this->hasOne(Files::class, ['id' => 'small_img_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::class, ['id' => 'user_id']);
    }
}
