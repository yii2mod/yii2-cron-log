<?php

namespace yii2mod\cron\behaviors;

use Yii;
use yii\base\Behavior;
use yii\console\Controller;
use yii\mutex\Mutex;

/**
 * MutexConsoleCommandBehavior allows console command actions being run with mutex protection.
 *
 * Usage:
 * ```
 * class MyCommand extends Controller
 * {
 *     public function behaviors()
 *     {
 *         return [
 *             'mutexBehavior' => [
 *                 'class' => 'yii2mod\cron\behaviors\MutexConsoleCommandBehavior',
 *                 'mutexActions' => ['index'],  // OR ['*'] - attach to all actions
 *             ]
 *         ];
 *     }
 * }
 * ```
 *
 * Class MutexConsoleCommandBehavior
 *
 * @package yii2mod\cron\behaviors
 */
class MutexConsoleCommandBehavior extends Behavior
{
    /**
     * @var string name of the mutex application component
     */
    public $mutex = 'mutex';

    /**
     * @var array list of action names, which mutex should be applied to
     */
    public $mutexActions = [];

    /**
     * @var int time (in seconds) to wait for lock to be released. Defaults to zero meaning that method will return
     * false immediately in case lock was already acquired.
     */
    public $timeout = 0;

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
     * @return Mutex mutex application component instance
     */
    public function getMutex()
    {
        return Yii::$app->get($this->mutex);
    }

    /**
     * Composes the mutex name.
     *
     * @param string $action command action name
     *
     * @return string mutex name
     */
    protected function composeMutexName($action)
    {
        return $this->owner->getUniqueId() . '-' . $action;
    }

    /**
     * Checks if specified action is among mutex actions.
     *
     * @param string $action action name
     *
     * @return bool whether action should be under mutex
     */
    public function checkIsMutexAction($action)
    {
        return in_array(strtolower($action), $this->mutexActions) || in_array('*', $this->mutexActions);
    }

    /**
     * @param $event
     *
     * @return bool
     */
    public function beforeAction($event)
    {
        if ($this->checkIsMutexAction($event->action->id)) {
            $mutexName = $this->composeMutexName($event->action->id);
            if (!$this->getMutex()->acquire($mutexName, $this->timeout)) {
                echo "Execution terminated: command is already running.\n";
                $event->isValid = false;

                return false;
            }
        }
    }

    /**
     * @param $event
     */
    public function afterAction($event)
    {
        if ($this->checkIsMutexAction($event->action->id)) {
            $mutexName = $this->composeMutexName($event->action->id);
            $this->getMutex()->release($mutexName);
        }
    }
}
