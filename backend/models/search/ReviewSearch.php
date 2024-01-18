<?php

namespace backend\models\search;

use common\models\Review;
use Yii;
use yii\data\ActiveDataProvider;

class ReviewSearch extends Review
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'short_description', 'description'], 'string'],
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
                        'id' => SORT_ASC,
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
            ]
        );

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'short_description', $this->short_description]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
