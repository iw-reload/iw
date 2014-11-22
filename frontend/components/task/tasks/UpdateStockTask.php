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
    parent::execute();
    
    // Objects are assigned by reference, so all the update stock tasks
    // work on the same Resources instance, gradually updating it.
    $aConfig = [
      'from' => $this->from,
      'population' => $this->population,
      'production' => $this->production,
      'stock' => $this->stock,
      'storage' => $this->storage,
      'to' => $this->to,
    ];
    
    $tasks = [
      new UpdateChemicalsStockTask($aConfig),
      new UpdateCurrentPopulationTask($aConfig),
      new UpdateEnergyStockTask($aConfig),
      new UpdateIceStockTask($aConfig),
      new UpdateIronStockTask($aConfig),
      new UpdateSteelStockTask($aConfig),
      new UpdateVv4aStockTask($aConfig),
      new UpdateWaterStockTask($aConfig),
    ];

    foreach ($tasks as $task) {
      $task->execute();
    }
  }
}
