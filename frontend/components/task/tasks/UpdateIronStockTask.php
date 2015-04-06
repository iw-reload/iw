<?php

namespace frontend\components\task\tasks;

use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateIronStockTask
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class UpdateIronStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $ironProductionPerSecond = $this->production->iron / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    
    $this->stock->iron += $ironProductionPerSecond * $timeInSeconds;
  }

}
