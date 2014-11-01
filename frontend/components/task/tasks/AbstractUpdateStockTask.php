<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ResourceOverflowEvent;
use frontend\components\task\tasks\AbstractTask;
use frontend\objects\Population;
use frontend\objects\Resources;

/**
 * Base class for Update{Xyz}StockTask classes.
 *
 * @property \DateTime $to alias for TaskInterface::$time
 * @author ben
 */
abstract class AbstractUpdateStockTask extends AbstractTask
{
  const EVENT_RESOURCE_OVERFLOW = 'EVENT_RESOURCE_OVERFLOW';
  
  /**
   * @var Resources 
   */
  public $stock;
  /**
   * @var Resources 
   */
  public $production;
  /**
   * @var Resources 
   */
  public $storage;
  /**
   * @var Population
   */
  public $population;
  /**
   * @var \DateTime 
   */
  public $from;

  public function getTo() {
    return $this->getTime();
  }
  
  public function setTo($time) {
    $this->setTime($time);
  }
  
  protected function triggerResourceOverflow( $resource, $amount )
  {
    $this->trigger(self::EVENT_RESOURCE_OVERFLOW, new ResourceOverflowEvent([
      'resource' => $resource,
      'amount' => $amount,
    ]));
  }
}
