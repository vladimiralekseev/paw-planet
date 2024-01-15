<?php

namespace api\models\forms;

use common\models\Pet;
use common\models\PetAvailable;
use yii\base\Model;
use yii\db\ActiveQuery;

class PetListForm extends Model
{
    public $age_from;
    public $age_to;
    public $breed_ids;
    public $for_borrow;
    public $for_walk;
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
            [['age_from', 'age_to', 'for_borrow', 'for_walk'], 'integer'],
            [['breed_ids'], 'string'],
            ['for_borrow', 'in', 'range' => [0, 1]],
            ['for_walk', 'in', 'range' => [0, 1]],
            ['available', 'in', 'range' => [1, 2, 3, 4, 5, 6, 7]],
        ];
    }

    public function getQuery(): ActiveQuery
    {
        $query = Pet::find();
        if ($this->breed_ids) {
            $query->andWhere(['breed_id' => explode(',', $this->breed_ids)]);
        }
        if ((bool)$this->for_borrow) {
            $query->andWhere(['for_borrow' => 1]);
        }
        if ((bool)$this->for_walk) {
            $query->andWhere(['for_walk' => 1]);
        }
        if ($this->age_from) {
            $query->andWhere(['>=', 'age', (int)$this->age_from]);
        }
        if ($this->age_to) {
            $query->andWhere(['<=', 'age', (int)$this->age_to]);
        }
        if ($this->available) {
            $query->innerJoinWith(['petAvailables']);
            $query->andWhere([PetAvailable::tableName() . '.day' => explode(',', $this->available)]);
        }
        return $query;
    }
}
