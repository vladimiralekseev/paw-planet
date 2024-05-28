<?php

namespace backend\models\search;

use common\models\Product;
use Yii;
use yii\data\ActiveDataProvider;

class ProductSearch extends Product
{
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'type'], 'string'],
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
                'type'   => $this->type,
            ]
        );

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
