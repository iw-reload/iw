<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ResourceOverflowEvent;
use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateIceStockTask
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class UpdateIceStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $iceProductionPerSecond = $this->production->ice / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    $iceDiff = $iceProductionPerSecond * $timeInSeconds;
    $newIce = $this->stock->ice + $iceDiff;

    if ($newIce <= 0)
    {
      $this->stock->ice = 0;
    }
    else if ($newIce <= $this->storage->ice)
    {
      $this->stock->ice = $newIce;
    }
    else /* if ($storage->ice < $newIce) */
    {
      $overflow = $newIce - $this->storage->ice;
      $this->triggerResourceOverflow( ResourceOverflowEvent::RES_ICE, $overflow );
      $this->stock->ice = $this->storage->ice;
    }
  }

}
