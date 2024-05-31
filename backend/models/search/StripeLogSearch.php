<?php

namespace backend\models\search;

use common\models\StripeLog;
use Yii;
use yii\data\ActiveDataProvider;

class StripeLogSearch extends StripeLog
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['event', 'data'], 'string'],
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
            ]
        );

        $query->andFilterWhere(['like', 'event', $this->event]);
        $query->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
