<?php

namespace frontend\tasks;

use frontend\interfaces\TaskInterface;
use frontend\objects\AbstractTask;
use frontend\objects\Resources;

/**
 * Description of UpdateVv4aStockTask
 *
 * @author ben
 */
class UpdateVv4aStockTask extends AbstractTask implements TaskInterface
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
    
    $vv4aProductionPerSecond = $production->vv4a / 3600;
    $timeInSeconds = $to->getTimestamp() - $from->getTimestamp();
    
    $this->stock->vv4a += $vv4aProductionPerSecond * $timeInSeconds;
  }

}
