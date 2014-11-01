<?php

namespace frontend\components\task\tasks;

use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateSteelStockTask
 *
 * @author ben
 */
class UpdateSteelStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $steelProductionPerSecond = $this->production->steel / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    
    $this->stock->steel += $steelProductionPerSecond * $timeInSeconds;
  }

}
