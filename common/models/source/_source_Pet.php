<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pet".
 *
 * @property int $id
 * @property string $nickname
 * @property int|null $breed_id
 * @property int|null $user_id
 * @property int|null $img_id
 * @property int|null $middle_img_id
 * @property int|null $small_img_id
 * @property int|null $age
 * @property int $for_borrow
 * @property int $for_walk
 * @property string|null $description
 * @property string|null $needs
 * @property string|null $good_with
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Breed $breed
 * @property Files $img
 * @property PetAvailable[] $petAvailables
 * @property PetImages[] $petImages
 * @property Files $smallImg
 * @property SiteUser $user
 */
class _source_Pet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname'], 'required'],
            [['breed_id', 'user_id', 'img_id', 'middle_img_id', 'small_img_id', 'age', 'for_borrow', 'for_walk'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nickname'], 'string', 'max' => 128],
            [['description', 'needs', 'good_with'], 'string', 'max' => 1024],
            [['breed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Breed::class, 'targetAttribute' => ['breed_id' => 'id']],
            [['img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['img_id' => 'id']],
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
            'for_borrow' => 'For Borrow',
            'for_walk' => 'For Walk',
            'description' => 'Description',
            'needs' => 'Needs',
            'good_with' => 'Good With',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * Gets query for [[PetAvailables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetAvailables()
    {
        return $this->hasMany(PetAvailable::class, ['pet_id' => 'id']);
    }

    /**
     * Gets query for [[PetImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetImages()
    {
        return $this->hasMany(PetImages::class, ['pet_id' => 'id']);
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
