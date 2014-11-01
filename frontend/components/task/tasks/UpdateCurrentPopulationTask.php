<?php

namespace frontend\components\task\tasks;

use frontend\components\task\tasks\AbstractUpdateStockTask;

/**
 * Description of UpdatePopulationStockTask
 *
 * @author ben
 */
class UpdateCurrentPopulationTask extends AbstractUpdateStockTask
{
  public function execute()
  {
    // TODO population growth is not linear. So this needs to be interpolated.
    $populationGrowthPerSecond = $this->population->growth / 3600;
    $timeInSeconds = $this->to->getTimestamp() - $this->from->getTimestamp();
    
    // TODO measuring overflows - emit a signal of overflown resources
    //      whoever kicks of the UpdateStockTasks can connect to the signals
    //      and map the lost resources to a base or a user or an alliance or...
    //      just make sure this class does not deal with such stuff directly
    //      keep concerns separated
    $newPopulation = $this->population->current + $populationGrowthPerSecond * $timeInSeconds;
    $this->stock->population = \min( $newPopulation, $this->population->max, $this->population->space );
  }

}
