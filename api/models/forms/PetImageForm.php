<?php

namespace api\models\forms;

use common\models\Pet;
use common\models\PetImages;
use common\models\upload\PetImageMiddleUploadForm;
use common\models\upload\PetImageSmallUploadForm;
use common\models\upload\PetImageUploadForm;
use Yii;
use yii\base\Model;

class PetImageForm extends Model
{
    public $pet_id;
    public $set_as_main;

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
            [['pet_id'], 'required'],
            [['pet_id'], 'currentUser'],
            ['set_as_main', 'in', 'range' => [0, 1]],
        ];
    }

    public function currentUser(): void
    {
        $pet = Pet::find()->where(['id' => $this->pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
        if (!$pet) {
            $this->addError('pet_id', 'Pet id is not correct.');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $petImageUploadForm = new PetImageUploadForm();
        $petImageMiddleUploadForm = new PetImageMiddleUploadForm();
        $petImageSmallUploadForm = new PetImageSmallUploadForm();
        $petImageUploadForm->loadInstance();
        if ($petImageUploadForm->validate() && $petImageUploadForm->upload()) {
            $petImageMiddleUploadForm->file = $petImageUploadForm->file;
            $petImageSmallUploadForm->file = $petImageUploadForm->file;
            $petImageMiddleUploadForm->upload();
            $petImageSmallUploadForm->upload();

            $petImage = new PetImages(
                [
                    'pet_id'        => $this->pet_id,
                    'img_id'        => $petImageUploadForm->id,
                    'middle_img_id' => $petImageMiddleUploadForm->id,
                    'small_img_id'  => $petImageSmallUploadForm->id,
                ]
            );
            $petImage->save();

            /** @var Pet $pet */
            $pet = Pet::find()->where(['id' => $this->pet_id])->one();

            if ($this->set_as_main || !$pet->img_id) {
                $pet->img_id = $petImageUploadForm->id;
                $pet->middle_img_id = $petImageMiddleUploadForm->id;
                $pet->small_img_id = $petImageSmallUploadForm->id;
                $pet->save(false);
            }
            return true;
        }

        if ($errors = $petImageUploadForm->getErrors()) {
            $this->addErrors($errors);
        }
        return false;
    }
}
