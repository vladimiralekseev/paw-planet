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
    public $distance;
    public $lat;
    public $lng;

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
            [['breed_ids'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'], //xss protection
            [['age_from', 'age_to', 'for_borrow', 'for_walk', 'distance'], 'integer'],
            [['breed_ids'], 'string'],
            [['lat', 'lng'], 'double'],
            ['for_borrow', 'in', 'range' => [0, 1]],
            ['for_walk', 'in', 'range' => [0, 1]],
            ['available', 'in', 'range' => [1, 2, 3, 4, 5, 6, 7]],
            [['lat', 'lng'], 'required', 'when' => function($model) {
                return $model->distance;
            }, 'message' => "Distance requires Lat and Lng options"],
            [['distance'], 'required', 'when' => function($model) {
                return $model->lat && $model->lng;
            }, 'message' => "Lat and Lng options require a distance"],
        ];
    }

    public function getQuery(): ActiveQuery
    {
        $query = Pet::find()->where([Pet::tableName() . '.status' => Pet::STATUS_ACTIVE]);
        $query->innerJoinWith(['user']);
        if (!empty($this->distance) && !empty($this->lat) && !empty($this->lng)) {
            $query->select(
                [
                    Pet::tableName() . '.*',
                    '( 3959 * acos( cos( radians(' . $this->lat . ') ) * cos( radians( site_user.latitude ) )
                  * cos( radians(site_user.longitude) - radians(' . $this->lng . ')) + sin(radians(' . $this->lat . '))
                  * sin( radians(site_user.latitude)))) AS distance'
                ]
            );
            $query->orHaving(['<=', 'distance', $this->distance]);
        } else {
            $query->select(
                [
                    Pet::tableName() . '.*',
                    'distance' => "FLOOR(0)"
                ]
            );
        }
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
