<?php

namespace api\models\forms;

use common\models\PetImages;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

class PetImageDeleteForm extends Model
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

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(): bool
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
        if ($petImage->pet->img_id === $petImage->img_id) {
            /** @var PetImages[] $petImages */
            $petImages = PetImages::find()->where(['pet_id' => $petImage->pet->id])
                ->andWhere(['not', ['img_id' => $petImage->img_id]])
                ->all();
            if (count($petImages) > 0) {
                $petImage->pet->img_id = $petImages[0]->img_id;
                $petImage->pet->middle_img_id = $petImages[0]->middle_img_id;
                $petImage->pet->small_img_id = $petImages[0]->small_img_id;
            } else {
                $petImage->pet->img_id = null;
                $petImage->pet->middle_img_id = null;
                $petImage->pet->small_img_id = null;
            }
            $petImage->pet->save(false);
        }
        return (bool)$petImage->delete();
    }
}
