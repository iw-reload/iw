<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ResourceOverflowEvent;
use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateWaterStockTask
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class UpdateWaterStockTask extends AbstractUpdateStockTask
{
  const EVENT_WATER_OVERFLOW = 'waterOverflow';

  public function execute()
  {
    $waterProductionPerSecond = $this->production->water / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    $waterDiff = $waterProductionPerSecond * $timeInSeconds;
    $newWater = $this->stock->water + $waterDiff;

    if ($newWater <= 0)
    {
      $this->stock->water = 0;
    }
    else if ($newWater <= $this->storage->water)
    {
      $this->stock->water = $newWater;
    }
    else /* if ($storage->water < $newWater) */
    {
      $overflow = $newWater - $this->storage->water;
      $this->triggerResourceOverflow( ResourceOverflowEvent::RES_WATER, $overflow );
      $this->stock->water = $this->storage->water;
    }
  }

}
