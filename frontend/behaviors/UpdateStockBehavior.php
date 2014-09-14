<?php

namespace frontend\behaviors;

use common\models\Base;
use frontend\objects\TaskQueue;
use frontend\tasks\UpdateStockTask;
use frontend\objects\Resources;
use frontend\objects\ProductionCalculator;
use frontend\objects\StorageCalculator;
use frontend\objects\PopulationCalculator;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * @author ben
 */
class UpdateStockBehavior extends Behavior
{
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
    ];
  }
  
  public function afterFind()
  {
    $base = $this->getBase();
    $now = new \DateTime();
    
    $stock = new Resources();
    $stock->chemicals = $base->stored_chemicals;
    $stock->credits = $base->stored_credits;
    $stock->energy = $base->stored_energy;
    $stock->ice = $base->stored_ice;
    $stock->iron = $base->stored_iron;
    $stock->population = $base->stored_people;
    $stock->steel = $base->stored_steel;
    $stock->vv4a = $base->stored_vv4a;
    $stock->water = $base->stored_water;
    
    $updateStockTask = new UpdateStockTask();
    $updateStockTask->setTime( $now );
    $updateStockTask->stock = $stock;
    
    /* @var $productionCalculator ProductionCalculator */
    $productionCalculator = Yii::createObject( ProductionCalculator::className() );
    /* @var $production \frontend\objects\Resources */
    $production = $productionCalculator->calculateProduction( $base );
    
    $storageCalculator = Yii::createObject( StorageCalculator::className() );
    $storage = $storageCalculator->calculateStorage( $base );
    
    /* @var $populationCalculator PopulationCalculator */
    $populationCalculator = Yii::createObject( PopulationCalculator::className() );
    $population = $populationCalculator->run( $base );
    
    $params = [
      'production' => $production,
      'storage' => $storage,
      'population' => $population,
      'from'  => new \DateTime($base->stored_last_update),
      'to' => $now,
    ];
    
    $taskQueue = $this->getTaskQueue();
    $taskQueue->addTask( $updateStockTask, $params );
    $taskQueue->execute();
    
    $base->stored_chemicals = $stock->chemicals;
    $base->stored_credits = $stock->credits;
    $base->stored_energy = $stock->energy;
    $base->stored_ice = $stock->ice;
    $base->stored_iron = $stock->iron;
    $base->stored_people = $stock->population;
    $base->stored_steel = $stock->steel;
    $base->stored_vv4a = $stock->vv4a;
    $base->stored_water = $stock->water;    
  }
  
  /**
   * @return TaskQueue
   */
  private function getTaskQueue() {
    return $this->owner->taskQueue;
  }
  
  /**
   * @return Base
   */
  private function getBase() {
    return $this->owner;
  }
}
