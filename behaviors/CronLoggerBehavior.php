<?php
namespace yii2mod\cron\behaviors;

use Yii;
use yii\base\Behavior;
use yii\console\Controller;
use yii2mod\cron\models\CronScheduleModel;

/**
 * CronLoggerBehavior allows logging of the console command running schedule.
 * This behavior adjusts the application event handles to intercept any errors and exception and log
 * them properly.
 *
 * You may adjust log result using the exit code, which is returned by command action.
 * In order to mark success, command action should return '0'.
 * In order to signal an error, command action should return string error message.
 *
 * Usage:
 * <code>
 * class MyCommand extends CConsoleCommand
 * {
 *     public function behaviors()
 *     {
 *         return array(
 *             'mutexBehavior' => array(
 *                 'class' => 'CronLoggerBehavior',
 *                 'actions' => array('index'),
 *             ),
 *         );
 *     }
 * }
 * </code>
 *
 * @author  Roman Protsenko <protsenko@zfort.com>
 * @author  Klimov Paul <klimov@zfort.com>
 * @author  Dmitry Semenov <disemx@gmail.com>
 * @version $Id$
 * @package default
 * @since   1.0
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
    public $actions = array();

    /**
     * @var string error message
     */
    public $message = '';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
            Controller::EVENT_AFTER_ACTION => 'afterAction',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($event)
    {
        if (isset($this->actions) && is_array($this->actions) && in_array(strtolower($event->action->id), $this->actions)) {
            /* @var CConsoleCommand $sender */
            $sender = $event->sender;
            $command = $sender->id . '/' . $sender->action->id;
            $this->schedule = new CronScheduleModel();
            $this->schedule->startCronSchedule($command);
            $this->setupApplicationErrorHandlers();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterAction($event)
    {
        if ($this->schedule) {
            $exitCode = (int)$event->result;
            if ($exitCode == 0) {
                $exitCode = 'success';
            } else {
                $exitCode = 'error';
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
}