<?php

namespace yii2mod\cron\actions;

use Yii;
use yii\base\Action;

/**
 * Class CronLogAction
 *
 * @package yii2mod\cron\actions
 */
class CronLogAction extends Action
{
    /**
     * @var string name of the view, which should be rendered
     */
    public $view = '@vendor/yii2mod/yii2-cron-log/views/index';

    /**
     * @var string search class name for searching
     */
    public $searchClass = 'yii2mod\cron\models\search\CronScheduleSearch';

    /**
     * Lists of all cron logs.
     */
    public function run()
    {
        $searchModel = Yii::createObject($this->searchClass);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
