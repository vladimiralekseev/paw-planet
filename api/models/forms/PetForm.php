<?php

namespace api\models\forms;

use common\models\Pet;
use common\models\PetAvailable;
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
    public $available;

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
            [['breed_id', 'age'], 'integer'],
            [['nickname'], 'string', 'max' => 128],
            [['description', 'needs', 'good_with'], 'string', 'max' => 1024],
            [['available'], 'checkAvailable'],
        ];
    }

    public function checkAvailable(): void
    {
        foreach (explode(',', $this->available) as $day) {
            if (!in_array($day, [1, 2, 3, 4, 5, 6, 7], false)) {
                $this->addError('available', 'Available is invalid: \'' . $day . '\'');
            }
        }
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
                $this->addError('pet_id', 'Pet Id is not yours');
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
        $isNewRecord = $pet->isNewRecord;

        if ($pet->validate() && $r = $pet->save()) {
            $this->pet_id = $pet->id;
            PetAvailable::deleteAll(['pet_id' => $pet->id]);
            foreach (explode(',', $this->available) as $day) {
                $petAvailable = new PetAvailable(
                    [
                        'pet_id'    => $pet->id,
                        'day'       => $day,
                        'available' => 1,
                    ]
                );
                $petAvailable->save();
            }
            if ($isNewRecord) {
                $this->sendEmail($pet);
            }
            return true;
        }
        $this->addErrors($pet->getErrors());
        return false;
    }

    private function sendEmail(Pet $pet): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                'pet/pet-new',
                ['pet' => $pet]
            )
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo(Yii::$app->params['emailTo'])
            ->setBcc(Yii::$app->params['emailBcc'])
            ->setSubject('New pet was added')
            ->send();
    }
}
