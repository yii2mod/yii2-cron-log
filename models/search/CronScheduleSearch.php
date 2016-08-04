<?php

namespace yii2mod\cron\models\search;

use yii\data\ActiveDataProvider;
use yii2mod\cron\models\CronScheduleModel;

/**
 * Class CronScheduleSearch
 * @package yii2mod\cron\models\search
 */
class CronScheduleSearch extends CronScheduleModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'jobCode', 'messages'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'jobCode', $this->jobCode]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'messages', $this->messages]);

        return $dataProvider;
    }
}