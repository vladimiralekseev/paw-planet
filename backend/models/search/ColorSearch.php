<?php

namespace backend\models\search;

use common\models\Color;
use Yii;
use yii\data\ActiveDataProvider;

class ColorSearch extends Color
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['color'], 'string'],
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
                        'color' => SORT_ASC,
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

        $query->andFilterWhere(['like', 'color', $this->name]);

        return $dataProvider;
    }
}
