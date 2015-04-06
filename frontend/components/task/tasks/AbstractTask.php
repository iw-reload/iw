<?php

namespace frontend\components\task\tasks;

use common\models\User;
use frontend\components\task\events\ModelModifiedEvent;
use frontend\interfaces\TaskInterface;
use yii\base\Component;
use yii\db\ActiveRecordInterface;

/**
 * Abstract base class for all tasks.
 * 
 * Every task executes with a time hint. This time hint defines when something
 * happened. For example when constructing a building, the time hint points
 * to the time when the building has been finished. When updating resources,
 * the time hint points to the time the resources should be updated to.
 * 
 * We extend Component so we can trigger events.
 *
 * @property User $user
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
abstract class AbstractTask extends Component implements TaskInterface
{
  const EVENT_MODEL_MODIFIED = 'EVENT_MODEL_MODIFIED';
  
  /**
   * @var \DateTime
   */
  private $time;
  
  public function init()
  {
    parent::init();
    
    if (!$this->time instanceof \DateTime) {
      throw new \yii\base\InvalidConfigException('AbstractTask::$time must be an instance of \DateTime.');
    }
  }

  
  public function getTime() {
    return $this->time;
  }

  public function setTime( \DateTime $time ) {
    $this->time = $time;
  }

  public function execute() {
    $className = $this->className();
    \Yii::trace( "Executing {$className}", __METHOD__);
  }

  /**
   * This event is provided so all tasks can trigger an event if they modify
   * one of our models. The tasks framework will care for collecting modified
   * models and to save them after all tasks have been executed. This ensures
   * we don't save models more often then necessary.
   * 
   * @param ActiveRecordInterface $model
   */
  protected function triggerModelModified( ActiveRecordInterface $model )
  {
    $this->trigger(self::EVENT_MODEL_MODIFIED, new ModelModifiedEvent([
      'model' => $model,
    ]));
  }
}
