<?php

namespace api\models\forms;

use common\models\Pet;
use Yii;
use yii\base\Model;

/**
 * Class PetForm
 */
class PetForm extends Model
{
    public $nickname;
    public $description;
    public $needs;
    public $good_with;
    public $age;
    public $breed_id;
    public $pet_id;

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
            [['nickname', 'breed_id'], 'required'],
            [['breed_id', 'age', 'pet_id'], 'integer'],
            [['nickname'], 'string', 'max' => 128],
            [['description', 'needs', 'good_with'], 'string', 'max' => 1024],
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Pet $pet */
        if ($this->pet_id) {
            $pet = Pet::find()->where(['id' => $this->pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
            if (!$pet) {
                $this->addError('pet_id', 'Pet Id is invalid');
                return false;
            }
        } else {
            $pet = new Pet(['user_id' => Yii::$app->user->identity->id]);
        }

        $pet->nickname = $this->nickname;
        $pet->description = $this->description;
        $pet->needs = $this->needs;
        $pet->good_with = $this->good_with;
        $pet->breed_id = $this->breed_id;
        $pet->age = $this->age;

        if ($pet->validate() && $r = $pet->save()) {
            $this->pet_id = $pet->id;
            return true;
        }
        $this->addErrors($pet->getErrors());
        return false;
    }
}
