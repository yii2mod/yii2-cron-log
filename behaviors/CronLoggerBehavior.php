<?php

namespace yii2mod\cron\behaviors;

use Yii;
use yii\base\Behavior;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii2mod\cron\models\CronScheduleModel;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/**
 * CronLoggerBehavior allows logging of the console command running schedule.
 * This behavior adjusts the application event handles to intercept any errors and exception and log
 * them properly.
 *
 * You may adjust log result using the exit code, which is returned by command action.
 * In order to signal an error, command action should return string error message.
 *
 * ~~~
 * class JobCommand extends Controller
 * {
 *     public function behaviors()
 *     {
 *         return [
 *             'cronLogger' => [
 *                 'class' => 'CronLoggerBehavior',
 *                 'actions' => ['index'], // OR ['*'] - attach to all actions
 *             ],
 *         ];
 *     }
 * }
 * ~~~
 */
class CronLoggerBehavior extends Behavior
{
    /**
     * @var CronScheduleModel
     */
    protected $schedule;

    /**
     * @var array list of action names, which should be logged.
     */
    public $actions = [];

    /**
     * @var string error message
     */
    public $message = '';

    /**
     * Declares event handlers for the [[owner]]'s events.
     *
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
            Controller::EVENT_AFTER_ACTION => 'afterAction',
        ];
    }

    /**
     * Before action
     * @param $event \yii\base\ActionEvent
     */
    public function beforeAction($event)
    {
        if (in_array($event->action->id, $this->actions) || in_array('*', $this->actions)) {
            $sender = $event->sender;
            $command = $sender->id . '/' . $sender->action->id . $this->getActionParams();
            $this->schedule = new CronScheduleModel();
            $this->schedule->startCronSchedule($command);
            $this->setupApplicationErrorHandlers();
        }
    }

    /**
     * After action
     * @param $event \yii\base\ActionEvent
     */
    public function afterAction($event)
    {
        if ($this->schedule) {
            if ($event->result === false) {
                $exitCode = CronScheduleStatus::ERROR;
            } else {
                $exitCode = CronScheduleStatus::SUCCESS;
            }
            $this->schedule->endCronSchedule($exitCode);
            $this->schedule = null;
        }
    }

    /**
     * Sets up application error event handlers.
     */
    protected function setupApplicationErrorHandlers()
    {
        $errorHandler = Yii::$app->get('errorHandler');
        $errorHandler->schedule = &$this->schedule;
    }

    /**
     * Get action params
     *
     * @return string
     */
    private function getActionParams()
    {
        $result = '';
        $requestParams = Yii::$app->request->getParams();
        ArrayHelper::remove($requestParams, 0);

        foreach ($requestParams as $key => $value) {
            if (is_string($key)) {
                $result .= " --{$key}={$value}";
            } else {
                $result .= " {$value}";
            }
        }

        return $result;
    }
}
