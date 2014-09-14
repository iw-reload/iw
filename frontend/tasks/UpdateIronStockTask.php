<?php

namespace frontend\tasks;

use frontend\interfaces\TaskInterface;
use frontend\objects\AbstractTask;
use frontend\objects\Resources;

/**
 * Description of UpdateIronStockTask
 *
 * @author ben
 */
class UpdateIronStockTask extends AbstractTask implements TaskInterface
{
  /**
   * @var Resources 
   */
  public $stock;
  
  public function execute($params)
  {
    $production = $params['production'];
    $from       = $params['from'];
    $to         = $params['to'];

    /* @var $production Resources */
    /* @var $from \DateTime */
    /* @var $to \DateTime */
    
    $ironProductionPerSecond = $production->iron / 3600;
    $timeInSeconds = $to->getTimestamp() - $from->getTimestamp();
    
    $this->stock->iron += $ironProductionPerSecond * $timeInSeconds;
  }

}
