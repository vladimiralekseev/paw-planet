<?php

namespace backend\models\search;

use common\models\Pet;
use Yii;
use yii\data\ActiveDataProvider;

class PetSearch extends Pet
{
    public function rules(): array
    {
        return [
            [['id', 'status', 'age'], 'integer'],
            [['nickname', 'description', 'needs', 'good_with'], 'string'],
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
                'id' => $this->id,
                'status' => $this->status,
                'age' => $this->age,
            ]
        );

        $query->andFilterWhere(['like', 'nickname', $this->nickname]);
        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'needs', $this->needs]);
        $query->andFilterWhere(['like', 'good_with', $this->good_with]);

        return $dataProvider;
    }
}
