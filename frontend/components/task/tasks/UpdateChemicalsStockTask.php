<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ResourceOverflowEvent;
use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdateChemicalsStockTask
 *
 * @author ben
 */
class UpdateChemicalsStockTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    $chemicalsProductionPerSecond = $this->production->chemicals / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    $chemicalsDiff = $chemicalsProductionPerSecond * $timeInSeconds;
    $newChemicals = $this->stock->chemicals + $chemicalsDiff;

    if ($newChemicals <= 0)
    {
      $this->stock->chemicals = 0;
    }
    else if ($newChemicals <= $this->storage->chemicals)
    {
      $this->stock->chemicals = $newChemicals;
    }
    else /* if ($storage->chemicals < $newChemicals) */
    {
      $overflow = $newChemicals - $this->storage->chemicals;
      $this->triggerResourceOverflow( ResourceOverflowEvent::RES_CHEMICALS, $overflow );
      $this->stock->chemicals = $this->storage->chemicals;
    }
  }
}
