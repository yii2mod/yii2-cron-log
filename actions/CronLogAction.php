<?php

namespace yii2mod\cron\actions;

use Yii;
use yii\base\Action;
use yii2mod\cron\models\search\CronScheduleSearch;

/**
 * Class CronLogAction
 * @package yii2mod\cron\actions
 */
class CronLogAction extends Action
{
    /**
     * @var string name of the view, which should be rendered.
     */
    public $view = '@vendor/yii2mod/yii2-cron-log/views/index';

    /**
     * Lists of all cron logs.
     */
    public function run()
    {
        $searchModel = new CronScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}