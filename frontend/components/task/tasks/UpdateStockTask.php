<?php

namespace frontend\components\task\tasks;

use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateStockTask
 *
 * @author ben
 */
class UpdateStockTask extends AbstractUpdateStockTask
{
  public function execute()
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

    foreach ($tasks as $task)
    {
      // Objects are assigned by reference, so all the update stock tasks
      // work on the same Resources instance, gradually updating it.

      $task->from = $this->from;
      $task->population = $this->population;
      $task->production = $this->production;
      $task->stock = $this->stock;
      $task->storage = $this->storage;
      $task->to = $this->to;
      
      $task->setTime( $this->getTime() );
      $task->execute();
    }
  }
}
