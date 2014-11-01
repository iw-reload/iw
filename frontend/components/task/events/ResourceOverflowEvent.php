<?php

namespace frontend\components\task\events;

use yii\base\Event;

/**
 * @author ben
 */
class ResourceOverflowEvent extends Event
{
  const RES_CHEMICALS = 'RES_CHEMICALS';
  const RES_ICE = 'RES_ICE';
  const RES_WATER = 'RES_WATER';
  const RES_ENERGY = 'RES_ENERGY';
  /**
   * @var string one of the RES_* constants
   */
  public $resource;
  /**
   * @var int how much overflow
   */
  public $amount;
}
