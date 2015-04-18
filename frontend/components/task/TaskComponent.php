<?php

namespace frontend\components\task;

use common\components\TimeComponent;
use common\models\Base;
use common\models\Task;
use common\models\User;
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
use yii\di\Instance;
use Yii;


/**
 * Description of TaskComponent
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class TaskComponent extends Component
{
  /**
   * @var \SplObjectStorage
   */
  private $_dirtyModels = null;
  /**
   * @todo for debugging purposes. Don't know yet how to avoid loading multiple
   * AR instances for the same db record.
   * @var \SplObjectStorage
   */
  private $_dirtyModelsInfo = [];
  /**
   * @var string the application component ID of the TimeComponent
   */
  public $time = 'time';
  
  public function init()
  {
    parent::init();
    
    $this->_dirtyModels = new \SplObjectStorage();
    $this->_dirtyModelsInfo = [];

    // whenever a base is loaded, update its stock
//    Event::on( Base::className(), Base::EVENT_AFTER_FIND,
//      function ($event) {
//        $this->updateStock( $event->sender );
//      }
//    );
    
    // whenever a user is loaded, execute the user's finished tasks
//    Event::on( User::className(), User::EVENT_AFTER_FIND,
//      function ($event) {
//        $this->executeFinishedTasks( $event->sender );
//      }
//    );
    
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

      $class = $finishedTaskModel->type;
      $taskConfig = array_merge( $finishedTaskModel->data, [
        'class' => $class,
        'time' => $finishedTaskModel->getDateTimeFinished(),
        'userId' => $finishedTaskModel->user_id,
        'baseFinder' => \Yii::$app->get('baseManager'),
        'userFinder' => \Yii::$app->get('userManager'),
      ]);
      
      $task = \Yii::createObject( $taskConfig );

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
  public function updateStock( $base, $time=null )
  {
    if ($time === null) {
      $time = $this->getTimeComponent()->getStartTime();
    }    
    
    $productionCalculator = Yii::createObject( ProductionCalculator::className() );
    $storageCalculator = Yii::createObject( StorageCalculator::className() );
    $populationCalculator = Yii::createObject( PopulationCalculator::className() );

    $updateStockTask = new UpdateStockTask([
      'from' => $base->getDateTimeStoredLastUpdate(),
      'population' => $populationCalculator->run( $base ),
      'production' => $productionCalculator->calculateProduction( $base ),
      'stock' => $base->getStock(),
      'storage' => $storageCalculator->calculateStorage( $base ),
      'to' => $time,
    ]);
    $updateStockTask->execute();
    
    $base->setDateTimeStoredLastUpdate( $time );
    $base->setStock( $updateStockTask->stock );
    
    $this->addDirtyModel( $base );
  }
  
  /**
   * @param \yii\db\ActiveRecord $model
   */
  public function addDirtyModel( $model )
  {
    // --- debug - start ------------------------------------------------------
    // make sure there's always only one instance referencing a DB record
    $dsn = $model->getDb()->dsn;
    $table = $model->tableName();
    $pk = $model->getPrimaryKey();    
    $modelId = "{$dsn}:{$table}:{$pk}";
   
    if (array_key_exists($modelId,$this->_dirtyModelsInfo))
    {
      $storedModel = $this->_dirtyModelsInfo[$modelId];
      
      if ($storedModel !== $model) {
        throw new \yii\base\NotSupportedException("Can't handle two instances for tabel '$table', id '$pk' (dsn '$dsn').");
      }
    }
    
    $this->_dirtyModelsInfo[$modelId] = $model;
    // --- debug - end --------------------------------------------------------
        
    $this->_dirtyModels->attach( $model );
  }
  
  public function saveDirtyModels()
  {
    $this->_dirtyModels->rewind();
    
    while ($this->_dirtyModels->valid())
    {
      /* @var $model \yii\db\ActiveRecord */
      $model = $this->_dirtyModels->current();
      $model->save();
      
      $this->_dirtyModels->next();
    }
    
    // SplObjectStorage doesn't have a clear() method?!
    $this->_dirtyModels = new \SplObjectStorage();
    $this->_dirtyModelsInfo = [];
  }
  
  /**
   * @return TimeComponent
   */
  private function getTimeComponent() {
    return Instance::ensure( $this->time, TimeComponent::className() );
  }
}
