<?php

namespace api\models\forms;

use common\models\Pet;
use common\models\PetImages;
use common\models\upload\PetImageMiddleUploadForm;
use common\models\upload\PetImageSmallUploadForm;
use common\models\upload\PetImageUploadForm;
use Yii;
use yii\base\Model;

class PetImageAsMainForm extends Model
{
    public $pet_image_id;

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
            [['pet_image_id'], 'required'],
            [['pet_image_id'], 'currentUser'],
        ];
    }

    public function currentUser(): void
    {
        $petImage = PetImages::find()->where(
            [
                PetImages::tableName() . '.id' => $this->pet_image_id,
                'user_id'                      => Yii::$app->user->identity->id
            ]
        )
            ->joinWith(['pet'])
            ->one();
        if (!$petImage) {
            $this->addError('pet_id', 'Pet image id is not correct.');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var PetImages $petImage */
        $petImage = PetImages::find()->where(
            [
                PetImages::tableName() . '.id' => $this->pet_image_id,
                'user_id'                      => Yii::$app->user->identity->id
            ]
        )
            ->joinWith(['pet'])
            ->one();

        $petImage->pet->img_id = $petImage->img_id;
        $petImage->pet->middle_img_id = $petImage->middle_img_id;
        $petImage->pet->small_img_id = $petImage->small_img_id;
        $petImage->pet->save(false);
        return true;
    }
}
