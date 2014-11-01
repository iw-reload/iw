<?php

namespace frontend\components\task;

use common\models\Base;
use common\models\Task;
use frontend\components\task\events\ConstructBuildingTaskEvent;
use frontend\components\task\events\ModelModifiedEvent;
use frontend\components\task\tasks\AbstractTask;
use frontend\components\task\tasks\ConstructBuildingTask;
use frontend\components\task\tasks\UpdateStockTask;
use frontend\interfaces\TaskInterface;
use frontend\interfaces\TaskProviderInterface;
use frontend\objects\PopulationCalculator;
use frontend\objects\ProductionCalculator;
use frontend\objects\StorageCalculator;
use yii\base\Component;
use yii\base\Event;
use Yii;

/**
 * Description of TaskComponent
 *
 * @author ben
 */
class TaskComponent extends Component
{
  /**
   * @var \SplObjectStorage
   */
  private $_dirtyModels = null;
  
  public function init()
  {
    parent::init();
    
    $this->_dirtyModels = new \SplObjectStorage();

    Event::on(
      AbstractTask::className(),
      AbstractTask::EVENT_MODEL_MODIFIED,
      function( ModelModifiedEvent $event ) {
        $this->addDirtyModel( $event->model );
      }
    );
    
    Event::on(
      ConstructBuildingTask::className(),
      ConstructBuildingTask::EVENT_BEFORE_BUILDING_CONSTRUCTED,
      function( ConstructBuildingTaskEvent $event ) {
        $this->updateStock( $event->base, $event->time );
      }
    );
  }
  
  /**
   * @todo count how often method is called. On exit, decrease counter. When
   *       counter reaches zero, save all modified models.
   *       --> this handles the case where execution of some tasks load new
   *       models, which in turn start to execute tasks recursively.
   * @param TaskProviderInterface $taskProvider
   */
  public function executeFinishedTasks( TaskProviderInterface $taskProvider )
  {
    $aTaskModelIds = [];
    
    /* @var $finishedTaskModel Task */
    foreach ($taskProvider->getFinishedTasks() as $finishedTaskModel)
    {
      $aTaskModelIds[] = $finishedTaskModel->id;

      try
      {
        $class = $finishedTaskModel->type;
        $taskConfig = array_merge( $finishedTaskModel->data, [
          'class' => $class,
          'time' => $finishedTaskModel->finished,
          
        ]);
        $task = \Yii::createObject( $taskConfig );
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

      // $user = $this->getUser( $this, $finishedTaskModel );
      $task->execute();
    }   

    Task::deleteAll(['id' => $aTaskModelIds]);
  }
  
  public function constructBuilding()
  {
    
  }

  /**
   * Updates the base's stock to the given time.
   * 
   * @param Base $base
   * @param \DateTime $time
   */
  public function updateStock( $base, $time )
  {
    $productionCalculator = Yii::createObject( ProductionCalculator::className() );
    $storageCalculator = Yii::createObject( StorageCalculator::className() );
    $populationCalculator = Yii::createObject( PopulationCalculator::className() );

    $updateStockTask = new UpdateStockTask();
    $updateStockTask->from = $base->getDateTimeStoredLastUpdate();
    $updateStockTask->population = $populationCalculator->run( $base );
    $updateStockTask->production = $productionCalculator->calculateProduction( $base );
    $updateStockTask->stock = $base->getStock();
    $updateStockTask->storage = $storageCalculator->calculateStorage( $base );
    $updateStockTask->to = $time;
    $updateStockTask->user = $base->user;
    $updateStockTask->execute();
    
    $base->stored_last_update = $time;
    $base->setStock( $updateStockTask->stock );
    
    $this->addDirtyModel( $base );
  }
  
  /**
   * @param \yii\db\ActiveRecordInterface $model
   */
  public function addDirtyModel( $model )
  {
    $this->_dirtyModels->attach( $model );
  }
  
  public function saveDirtyModels()
  {
    $this->_dirtyModels->rewind();
    
    while ($this->_dirtyModels->valid())
    {
      /* @var $model \yii\db\ActiveRecordInterface */
      $model = $this->_dirtyModels->current();
      $model->save();
      
      $this->_dirtyModels->next();
    }
    
    // SplObjectStorage doesn't have a clear() method?!
    $this->_dirtyModels = new \SplObjectStorage();
  }
}
