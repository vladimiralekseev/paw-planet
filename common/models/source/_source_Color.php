<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property int $id
 * @property string $color
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PetColor[] $petColors
 */
class _source_Color extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['color'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PetColors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetColors()
    {
        return $this->hasMany(PetColor::class, ['color_id' => 'id']);
    }
}
