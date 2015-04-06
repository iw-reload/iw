<?php

namespace frontend\components\task\events;

use yii\base\Event;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ConstructBuildingTaskEvent extends Event
{
  /**
   * @var \common\models\Base
   */
  public $base;
  /**
   * @var integer
   */
  public $buildingId;
  /**
   * @var \DateTime
   */
  public $time;
  /**
   * @var \common\models\User
   */
  public $user;
}
