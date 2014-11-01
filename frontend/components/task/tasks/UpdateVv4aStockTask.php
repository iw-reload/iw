<?php

namespace frontend\components\task\tasks;

use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateVv4aStockTask
 *
 * @author ben
 */
class UpdateVv4aStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $vv4aProductionPerSecond = $this->production->vv4a / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    
    $this->stock->vv4a += $vv4aProductionPerSecond * $timeInSeconds;
  }

}
