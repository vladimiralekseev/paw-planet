<?php

namespace api\models\forms;

use common\models\Breed;
use common\models\Color;
use common\models\LostPet;
use common\models\PetColor;
use DateTime;
use Exception;
use Yii;
use yii\base\Model;

class LostPetForm extends Model
{
    public $nickname;
    public $age;
    public $breed_id;
    public $pet_id;
    public $color_ids;
    public $latitude;
    public $longitude;
    public $country;
    public $state;
    public $city;
    public $address;
    public $type;
    public $when;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['color_ids', 'country', 'state', 'city', 'address', 'type', 'when', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'double'],
            [['breed_id', 'age'], 'integer'],
            [['when'], 'safe'],
            [['nickname'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 8],
            ['type', 'in', 'range' => ['lost', 'found']],
            ['when', 'checkWhen'],
            ['color_ids', 'checkColorIds'],
            [['country', 'state', 'city', 'address'], 'string', 'max' => 64],
            [
                ['breed_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Breed::class,
                'targetAttribute' => ['breed_id' => 'id']
            ],
        ];
    }

    public function checkColorIds(): void
    {
        foreach (explode(',', $this->color_ids) as $id) {
            $color = Color::find()->where(['id' => $id])->one();
            if (!$color) {
                $this->addError('color_ids', 'Color Id \'' . $id . '\' is not correct');
            }
        }
    }

    public function checkWhen(): void
    {
        try {
            $when = (new DateTime($this->when))->format('Y-m-d');
            if ($when !== $this->when) {
                $this->addError('when', 'When has incorrect format. Example: ' . $when);
            }
        } catch (Exception $e) {
            $this->addError('when', 'When has incorrect format');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var LostPet $pet */
        if ($this->pet_id) {
            $pet = LostPet::find()->where(['id' => $this->pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
            if (!$pet) {
                $this->addError('pet_id', 'Pet Id is not yours');
                return false;
            }
        } else {
            $pet = new LostPet(['user_id' => Yii::$app->user->identity->id]);
        }

        $pet->nickname = $this->nickname;
        $pet->breed_id = $this->breed_id;
        $pet->age = $this->age;
        $pet->latitude = $this->latitude;
        $pet->longitude = $this->longitude;
        $pet->country = $this->country;
        $pet->state = $this->state;
        $pet->city = $this->city;
        $pet->address = $this->address;
        $pet->type = $this->type;
        $pet->when = $this->when;
        if ($pet->validate() && $r = $pet->save()) {
            $this->pet_id = $pet->id;
            PetColor::deleteAll(['lost_pet_id' => $pet->id]);
            foreach (explode(',', $this->color_ids) as $id) {
                $petColor = new PetColor(
                    [
                        'lost_pet_id' => $pet->id,
                        'color_id' => $id,
                    ]
                );
                $petColor->save();
            }
            return true;
        }

        $this->addErrors($pet->getErrors());
        return false;
    }
}
