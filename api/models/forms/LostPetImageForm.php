<?php

namespace api\models\forms;

use common\models\LostPet;
use common\models\upload\LostPetImageMiddleUploadForm;
use common\models\upload\LostPetImageUploadForm;
use common\models\upload\PetImageSmallUploadForm;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

class LostPetImageForm extends Model
{
    public $lost_pet_id;

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
            [['lost_pet_id'], 'required'],
            [['lost_pet_id'], 'currentUser'],
        ];
    }

    public function currentUser(): void
    {
        $pet = LostPet::find()->where(['id' => $this->lost_pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
        if (!$pet) {
            $this->addError('pet_id', 'Pet id is not correct.');
        }
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $petImageUploadForm = new LostPetImageUploadForm();
        $petImageMiddleUploadForm = new LostPetImageMiddleUploadForm();
        $petImageSmallUploadForm = new PetImageSmallUploadForm();
        $petImageUploadForm->loadInstance();
        if ($petImageUploadForm->validate() && $petImageUploadForm->upload()) {
            $petImageMiddleUploadForm->file = $petImageUploadForm->file;
            $petImageSmallUploadForm->file = $petImageUploadForm->file;
            $petImageMiddleUploadForm->upload();
            $petImageSmallUploadForm->upload();

            /** @var LostPet $pet */
            $pet = LostPet::find()->where(['id' => $this->lost_pet_id])->one();
            if ($pet->img_id) {
                $pet->img->delete();
            }
            if ($pet->middle_img_id) {
                $pet->middleImg->delete();
            }
            if ($pet->small_img_id) {
                $pet->smallImg->delete();
            }
            $pet->img_id = $petImageUploadForm->id;
            $pet->middle_img_id = $petImageMiddleUploadForm->id;
            $pet->small_img_id = $petImageSmallUploadForm->id;
            $pet->save(false);
            return true;
        }

        if ($errors = $petImageUploadForm->getErrors()) {
            $this->addErrors($errors);
        }
        return false;
    }
}
