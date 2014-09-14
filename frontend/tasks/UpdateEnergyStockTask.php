<?php

namespace frontend\tasks;

use frontend\interfaces\TaskInterface;
use frontend\objects\AbstractTask;
use frontend\objects\Resources;

/**
 * Description of UpdateEnergyStockTask
 *
 * @author ben
 */
class UpdateEnergyStockTask extends AbstractTask implements TaskInterface
{
  /**
   * @var Resources 
   */
  public $stock;
  
  public function execute($params)
  {
    $production = $params['production'];
    $storage    = $params['storage'];
    $from       = $params['from'];
    $to         = $params['to'];

    /* @var $production Resources */
    /* @var $storage Resources */
    /* @var $from \DateTime */
    /* @var $to \DateTime */
    
    $energyProductionPerSecond = $production->energy / 3600;
    $timeInSeconds = $to->getTimestamp() - $from->getTimestamp();
    
    // TODO measuring overflows - emit a signal of overflown resources
    //      whoever kicks of the UpdateStockTasks can connect to the signals
    //      and map the lost resources to a base or a user or an alliance or...
    //      just make sure this class does not deal with such stuff directly
    //      keep concerns separated
    $energy = $this->stock->energy + $energyProductionPerSecond * $timeInSeconds;
    $this->stock->energy = \min( $energy, $storage->energy );
  }

}
