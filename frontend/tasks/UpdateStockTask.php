<?php

namespace frontend\tasks;

use frontend\interfaces\TaskInterface;
use frontend\objects\AbstractTask;
use frontend\objects\Resources;
use frontend\objects\TaskQueue;

/**
 * Description of UpdateStockTask
 *
 * @author ben
 */
class UpdateStockTask extends AbstractTask implements TaskInterface
{
  /**
   * @var Resources 
   */
  public $stock;
  
  public function execute($params)
  {
    $tasks = [
      new UpdateChemicalsStockTask(),
      new UpdateCurrentPopulationTask(),
      new UpdateEnergyStockTask(),
      new UpdateIceStockTask(),
      new UpdateIronStockTask(),
      new UpdateSteelStockTask(),
      new UpdateVv4aStockTask(),
      new UpdateWaterStockTask(),
    ];

    $queue = $this->getQueue();
    
    foreach ($tasks as $task)
    {
      // Objects are assigned by reference, so all the update stock tasks
      // work on the same Resources instance, gradually updating it.
      $task->stock = $this->stock;
      
      $task->setTime( $this->getTime() );
      $queue->addTask( $task, $params );
    }
  }

}
