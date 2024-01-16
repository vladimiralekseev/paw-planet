<?php

namespace api\models\forms;

use common\models\Pet;
use Yii;
use yii\base\Model;

class PetUpdateFieldForm extends Model
{
    public const FIELD_BORROW = 'for_borrow';
    public const FIELD_WALK = 'for_walk';

    public $pet_id;
    public $status;
    public $field;

    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pet::class, 'targetAttribute' => ['pet_id' => 'id']],
            [['pet_id'], 'currentUser'],
            [['status'], 'in', 'range' => [0, 1]],
            [['field'], 'in', 'range' => [self::FIELD_BORROW, self::FIELD_WALK]],
        ];
    }

    public function currentUser(): void
    {
        $petImage = Pet::find()->where(
            [
                'id'      => $this->pet_id,
                'user_id' => Yii::$app->user->identity->id
            ]
        )
            ->one();
        if (!$petImage) {
            $this->addError('pet_id', 'Pet id is not yours.');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Pet $pet */
        $pet = Pet::find()->where(['id' => $this->pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
        $pet->{$this->field} = $this->status;
        $pet->save(false);
        return true;
    }
}
