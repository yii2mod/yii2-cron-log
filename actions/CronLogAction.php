<?php

namespace yii2mod\cron\actions;

use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii2mod\cron\models\CronScheduleModel;

/**
 * Class CronLogAction
 * @package yii2mod\cron\actions
 */
class CronLogAction extends Action
{
    /**
     * @var string
     */
    public $view = '@vendor/yii2mod/yii2-cron-log/views/index';

    /**
     * Run action
     */
    public function run()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CronScheduleModel::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $this->controller->render($this->view, [
            'dataProvider' => $dataProvider
        ]);
    }
}