<?php
namespace yii2mod\cron\behaviors;

use yii\base\Behavior;
use yii\console\Controller;


/**
 * MutexConsoleCommandBehavior allows console command actions being run with mutex protection.
 *
 * Usage:
 * <code>
 * class MyCommand extends Controller
 * {
 *     public function behaviors()
 *     {
 *         return array(
 *             'mutexBehavior' => array(
 *                 'class' => 'yii2mod\cron\behaviors\MutexConsoleCommandBehavior',
 *                 'mutexActions' => array('index'),  // OR ['*'] - attach to all actions
 *             ),
 *         );
 *     }
 * }
 * </code>
 *
 * @method \Controller getOwner()
 *
 * @author  Klimov Paul <klimov@zfort.com>
 * @author  Dmitry Semenov <disemx@gmail.com>
 * @version $Id$
 * @package yii2mod\cron\behaviors
 * @since   1.0
 */
class MutexConsoleCommandBehavior extends Behavior
{
    /**
     * @var string name of the mutex application component.
     */
    public $mutex = 'mutex';
    /**
     * @var array list of action names, which mutex should be applied to.
     */
    public $mutexActions = array();


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
     * @return Mutex mutex application component instance.
     */
    public function getMutex()
    {
        return \Yii::$app->get($this->mutex);
    }

    /**
     * Composes the mutex name.
     *
     * @param string $action command action name.
     *
     * @return string mutex name.
     */
    protected function composeMutexName($action)
    {
        return $this->owner->getUniqueId() . '-' . $action;
    }

    /**
     * Checks if specified action is among mutex actions.
     *
     * @param string $action action name.
     *
     * @return boolean whether action should be under mutex.
     */
    public function checkIsMutexAction($action)
    {
        return in_array(strtolower($action), $this->mutexActions) || in_array('*', $this->mutexActions);
    }


    /**
     * @param $event
     * @return bool
     */
    public function beforeAction($event)
    {
        if ($this->checkIsMutexAction($event->action->id)) {
            $mutexName = $this->composeMutexName($event->action->id);
            if (!$this->getMutex()->acquire($mutexName)) {
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
