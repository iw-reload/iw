<?php

namespace common\behaviors;

use common\models\Task;
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
    
    /* @var $finishedTask Task */
    foreach ($aFinishedTaskModels as $finishedTaskModel)
    {
      $aTaskModelIds[] = $finishedTaskModel->id;
      $task = unserialize( $finishedTaskModel->data );
      
      // TODO check $task implements the Task Interface.
      // TODO which data should we pass to $runnable?
      //      We always have the TaskModel at hand.
      //      We can check for related ARs (BaseTask/ AccountTask/ ...)
      
      $task->run( $finishedTaskModel, $model );
    }   

    Task::deleteAll( ['id' => $aTaskModelIds] );
    $model->save();
  }
}
