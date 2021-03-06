<?php

namespace frontend\components\task\models;

use common\components\TimeComponent;
use common\models\Task;
use frontend\interfaces\BuildingFinderInterface;
use frontend\components\task\tasks\ConstructBuildingTask;
use frontend\validators\BaseOwnedByUserValidator;
use yii\base\Exception;
use yii\base\Model;
use yii\di\Instance;
use Yii;

/**
 * ConstructBuildingForm is the model behind the construct building form.
 * (Construction page, build action).
 */
class ConstructBuildingForm extends Model
{
  /**
   * @var BuildingFinderInterface
   */
  private $buildingFinder;
  /**
   * @var string the application component ID of the TimeComponent
   */
  public $time = 'time';
  
  public $baseId;
  public $buildingId;
  
  // TODO: introduce a ConstructionQueueRule
  //       The rule takes an integer (number of things in the queue) and
  //       responds with an factor indicating how much more expensive things
  //       should become.
  //       Later, the rule should take into account genetics.
  
  public function __construct( BuildingFinderInterface $buildingFinder, $config = [] )
  {
    $this->buildingFinder = $buildingFinder;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['baseId', 'buildingId'], 'required'],
      [['baseId', 'buildingId'], 'integer'],
      [['baseId', 'buildingId'], 'filter', 'filter' => 'intval'],
      
      // TODO implement validator
      //['buildingId', ValidBuildingId ],
      
      ['baseId', BaseOwnedByUserValidator::className(), 'user' => Yii::$app->user->identity],

      // TODO implement dependencies
      // TODO implement validator
      //['buildingId', 'buildingDependencies'],

      // TODO implement validator (depends on baseId)
      //['buildingId', 'buildingResources'],
    ];
  }

  /**
   * Creates the ConstructBuildingTask.
   */
  public function createTask()
  {
    $building = $this->buildingFinder->getById( $this->buildingId );
    
    /* @var $user \common\models\User */
    $user = Yii::$app->user->identity;
    $base = $user->getBase( $this->baseId );
    // TODO once the ConstructionQueueRule is implemented, use it to pass
    //      the currently appropriate factor.
    $base->payFor( $building, 1.0 );

    $now = $this->getTimeComponent()->getStartTime();
    $finished = clone $now;
    $finished->add( $building->getCostTime() );
    
    $taskModel = new Task();
    $taskModel->data = [
      'baseId'      => $this->baseId,
      'buildingId'  => $this->buildingId,
    ];
    $taskModel->dateTimeFinished = $finished;
    $taskModel->type = ConstructBuildingTask::className();
    $taskModel->user_id = $user->id;
    
    if (!$taskModel->save()) {
      throw new Exception('Failed to create task: ' . print_r($taskModel->firstErrors,true));
    }
  }
  
  /**
   * @return TimeComponent
   */
  private function getTimeComponent() {
    return Instance::ensure( $this->time, TimeComponent::className() );
  }
}
