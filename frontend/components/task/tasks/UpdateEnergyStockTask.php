<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ResourceOverflowEvent;
use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateEnergyStockTask
 *
 * @author ben
 */
class UpdateEnergyStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $energyProductionPerSecond = $this->production->energy / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    $energyDiff = $energyProductionPerSecond * $timeInSeconds;
    $newEnergy = $this->stock->energy + $energyDiff;

    if ($newEnergy <= 0)
    {
      $this->stock->energy = 0;
    }
    else if ($newEnergy <= $this->storage->energy)
    {
      $this->stock->energy = $newEnergy;
    }
    else /* if ($storage->energy < $newEnergy) */
    {
      $overflow = $newEnergy - $this->storage->energy;
      $this->triggerResourceOverflow( ResourceOverflowEvent::RES_ENERGY, $overflow );
      $this->stock->energy = $this->storage->energy;
    }
  }

}
