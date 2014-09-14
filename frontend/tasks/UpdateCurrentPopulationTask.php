<?php

namespace frontend\tasks;

use frontend\interfaces\TaskInterface;
use frontend\objects\AbstractTask;
use frontend\objects\Population;
use frontend\objects\Resources;

/**
 * Description of UpdatePopulationStockTask
 *
 * @author ben
 */
class UpdateCurrentPopulationTask extends AbstractTask implements TaskInterface
{
  /**
   * @var Resources 
   */
  public $stock;
  
  public function execute($params)
  {
    $population = $params['population'];
    $from       = $params['from'];
    $to         = $params['to'];

    /* @var $population Population */
    /* @var $from \DateTime */
    /* @var $to \DateTime */
    
    // TODO population growth is not linear. So this needs to be interpolated.
    $populationGrowthPerSecond = $population->growth / 3600;
    $timeInSeconds = $to->getTimestamp() - $from->getTimestamp();
    
    // TODO measuring overflows - emit a signal of overflown resources
    //      whoever kicks of the UpdateStockTasks can connect to the signals
    //      and map the lost resources to a base or a user or an alliance or...
    //      just make sure this class does not deal with such stuff directly
    //      keep concerns separated
    $newPopulation = $population->current + $populationGrowthPerSecond * $timeInSeconds;
    $this->stock->population = \min( $newPopulation, $population->max, $population->space );
  }

}
