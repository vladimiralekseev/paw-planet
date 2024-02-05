<?php

namespace backend\models\search;

use common\models\LostPet;
use Yii;
use yii\data\ActiveDataProvider;

class LostPetSearch extends LostPet
{
    public function rules(): array
    {
        return [
            [['id', 'status', 'age'], 'integer'],
            [['nickname', 'type'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
                ],
                'sort'       => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id'     => $this->id,
                'status' => $this->status,
                'age'    => $this->age,
                'type'   => $this->type,
            ]
        );

        $query->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
