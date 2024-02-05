<?php

namespace backend\models\search;

use common\models\Review;
use common\models\SiteUser;
use Yii;
use yii\data\ActiveDataProvider;

class SiteUserSearch extends SiteUser
{
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['first_name', 'last_name', 'email'], 'string'],
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
                'status' => $this->status,
            ]
        );

        $query->andFilterWhere(['like', 'first_name', $this->first_name]);
        $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
