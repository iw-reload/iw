<?php

namespace frontend\behaviors;

use common\components\TimeComponent;
use common\models\Base;
use frontend\components\task\TaskComponent;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\di\Instance;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class UpdateStockBehavior extends Behavior
{
  /**
   * @var string the application component ID of the TaskComponent
   */
  public $task = 'task';
  /**
   * @var string the application component ID of the TimeComponent
   */
  public $time = 'time';
  
  public function attach($owner)
  {
    if (!$owner instanceof Base)
    {
      $behaviorClass = $this->className();
      $ownerClass = Base::className();
      throw new InvalidConfigException("'{$behaviorClass}' must only be attached to '{$ownerClass}' instances.");
    }
    
    parent::attach($owner);
  }

  
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
    ];
  }
  
  public function afterFind()
  {
    $taskComponent = $this->getTaskComponent();
    $base = $this->getBase();
    $now = $this->getTimeComponent()->getStartTime();

    $taskComponent->updateStock( $base, $now );
  }
  
  /**
   * @return Base
   */
  private function getBase()
  {
    return $this->owner;
  }
  
  /**
   * @return TaskComponent
   */
  private function getTaskComponent()
  {
    return Instance::ensure( $this->task, TaskComponent::className() );
  }
  
  /**
   * @return TimeComponent
   */
  private function getTimeComponent() {
    return Instance::ensure( $this->time, TimeComponent::className() );
  }
}
