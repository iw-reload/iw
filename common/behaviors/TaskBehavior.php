<?php

namespace common\behaviors;

use common\models\Task;
use common\models\User;
use frontend\interfaces\TaskInterface;
use yii\base\Behavior;
use yii\db\ActiveRecord;

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
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
    ];
  }
 
  public function afterFind()
  {
    // TODO use TaskQueue
    
    $model = $this->owner;
    $aFinishedTaskModels = $model->getFinishedTasks()->all();
    
    if (empty($aFinishedTaskModels)) {
      return;
    }
    
    $aTaskModelIds = [];
    
    /* @var $finishedTaskModel Task */
    foreach ($aFinishedTaskModels as $finishedTaskModel)
    {
      $aTaskModelIds[] = $finishedTaskModel->id;

      try
      {
        $class = $finishedTaskModel->type;
        $task = \Yii::createObject( array_merge($finishedTaskModel->data,['class' => $class]) );
      }
      catch (\Exception $ex)
      {
        \Yii::error($ex->getMessage());
        continue;
      }

      if (!($task instanceof TaskInterface))
      {
        \Yii::error( "'$finishedTaskModel->type' does not implement TaskInterface!" );
        continue;
      }
      
      $user = $this->getUser( $this, $finishedTaskModel );
      $task->execute( $user );
    }   

    Task::deleteAll(['id' => $aTaskModelIds]);
    $model->save();
  }
  
  /**
   * @todo problem: once any task loads a user instance, we end up with
   *       infinite recursion (we're in User::afterFind).
   * @param TaskBehavior $taskBehavior
   * @param Task $taskModel
   */
  static private function getUser( $taskBehavior, $taskModel )
  {
    if ($taskBehavior->owner instanceof User && (int)$taskBehavior->owner->id === (int)$taskModel->user_id) {
      return $taskBehavior->owner;
    } else {
      return null;
    }
  }
}
