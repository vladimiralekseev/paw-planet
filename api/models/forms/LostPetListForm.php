<?php

namespace api\models\forms;

use common\models\LostPet;
use yii\base\Model;
use yii\db\ActiveQuery;

class LostPetListForm extends Model
{
    public $age_from;
    public $age_to;
    public $breed_ids;
    public $color_ids;
    public $type;
    public $distance;
    public $lat;
    public $lng;
    public $nickname;

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
            [['breed_ids', 'color_ids', 'nickname'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'], //xss protection
            [['age_from', 'age_to', 'distance'], 'integer'],
            [['breed_ids', 'color_ids', 'nickname'], 'string'],
            [['lat', 'lng'], 'double'],
            ['type', 'in', 'range' => ['lost', 'found']],
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
        $query = LostPet::find();
        $query->innerJoinWith(['user']);
        if (!empty($this->distance) && !empty($this->lat) && !empty($this->lng)) {
            $query->select(
                [
                    LostPet::tableName() . '.*',
                    '( 3959 * acos( cos( radians(' . $this->lat . ') ) * cos( radians( lost_pet.latitude ) )
                  * cos( radians(lost_pet.longitude) - radians(' . $this->lng . ')) + sin(radians(' . $this->lat . '))
                  * sin( radians(lost_pet.latitude)))) AS distance'
                ]
            );
            $query->orHaving(['<=', 'distance', $this->distance]);
        } else {
            $query->select(
                [
                    LostPet::tableName() . '.*',
                    'distance' => "FLOOR(0)"
                ]
            );
        }
        if ($this->breed_ids) {
            $query->andWhere(['breed_id' => explode(',', $this->breed_ids)]);
        }
        if ($this->nickname) {
            $query->andWhere(['like', 'nickname', $this->nickname]);
        }
        if ($this->color_ids) {
            $query->innerJoinWith(['petColors']);
            $query->andWhere(['color_id' => explode(',', $this->color_ids)]);
        }
        if ($this->type) {
            $query->andWhere(['type' => $this->type]);
        }
        if ($this->age_from) {
            $query->andWhere(['>=', 'age', (int)$this->age_from]);
        }
        if ($this->age_to) {
            $query->andWhere(['<=', 'age', (int)$this->age_to]);
        }
        return $query;
    }
}
