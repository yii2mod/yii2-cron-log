<?php
/**
 * Created by PhpStorm.
 * User: semenov
 * Date: 08.07.14
 * Time: 11:13
 */

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
 *                 'mutexActions' => array('index'),
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
 * @package zfort\mutex\behavior
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
     * @var integer exit code, which should be returned by console command in case it
     * is terminated due to mutex lock.
     */
    public $mutexExitCode = 100;

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
        return $this->getOwner()->getName() . '-' . $action;
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
        return in_array(strtolower($action), $this->mutexActions);
    }

    /**
     * Responds to {@link CConsoleCommand::onBeforeAction} event.
     * Override this method and make it public if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     *
     * @param CConsoleCommandEvent $event event parameter
     */
    public function beforeAction($event)
    {
        if ($this->checkIsMutexAction($event->action)) {
            $mutexName = $this->composeMutexName($event->action);
            if (!$this->getMutex()->acquire($mutexName)) {
                echo "Execution terminated: command is already running.\n";
                $event->stopCommand = true;
                $event->exitCode = $this->mutexExitCode;
            }
        }
    }

    /**
     * Responds to {@link CConsoleCommand::onAfterAction} event.
     * Override this method and make it public if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     *
     * @param CConsoleCommandEvent $event event parameter
     */
    public function afterAction($event)
    {
        if ($this->checkIsMutexAction($event->action)) {
            $mutexName = $this->composeMutexName($event->action);
            $this->getMutex()->release($mutexName);
        }
    }
}