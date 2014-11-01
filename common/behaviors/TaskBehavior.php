<?php

namespace common\behaviors;

use frontend\components\task\TaskComponent;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\di\Instance;

/**
 * Cares for executing tasks that have been finished.
 * 
 * Attached to ActiveRecord classes that support delayed tasks, this behavior
 * cares for executing those tasks that have already been finished when an
 * instance of the ActiveRecord implementation is read from DB.
 * 
 * This way, we always get ActiveRecord implementations that are up-to-date.
 * 
 * For example, imagine a Base. A player might construct buildings in that
 * base, which are represented as Tasks. If we load the Base, the bahavior
 * checks whether the building (represented by the task) is finished or not. If
 * it is finished, the behavior executes the Task, which in turn implements the
 * logic to update the base with all necessary information (maybe the building
 * produces something, unlocks something, ...).
 * 
 * Executed tasks will be deleted from DB.
 * 
 * In the end, the Base will be saved. This way, by simply loading the Base,
 * we get an instance with all finished tasks applied, which is also in sync
 * with the DB.
 *
 * @author ben
 */
class TaskBehavior extends Behavior
{
  /**
   * @var string the application component ID of the TaskComponent
   */
  public $task = 'task';
  
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
    ];
  }
  
  public function attach($owner)
  {
    if (!$owner instanceof \frontend\interfaces\TaskProviderInterface)
    {
      $behaviorClass = $this->className();
      $ownerClass = '\frontend\interfaces\TaskProviderInterface';
      throw new InvalidConfigException("'{$behaviorClass}' must only be attached to '{$ownerClass}' instances.");
    }
    
    parent::attach($owner);
  }

  public function afterFind()
  {
    $taskComponent = $this->getTaskComponent();
    $taskComponent->executeFinishedTasks( $this->owner );
  }
  
  /**
   * @return TaskComponent
   */
  private function getTaskComponent()
  {
    return Instance::ensure( $this->task, TaskComponent::className() );
  }
}
