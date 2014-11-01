<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ConstructBuildingTaskEvent;
use frontend\components\task\tasks\BaseTask;

/**
 * Constructs a building on a base.
 *
 * @author ben
 */
class ConstructBuildingTask extends BaseTask
{
  const EVENT_BEFORE_BUILDING_CONSTRUCTED = 'EVENT_BEFORE_BUILDING_CONSTRUCTED';
  const EVENT_AFTER_BUILDING_CONSTRUCTED = 'EVENT_AFTER_BUILDING_CONSTRUCTED';
  
  private $buildingId;
  
  public function getBuildingId() {
    return $this->buildingId;
  }

  public function setBuildingId($buildingId) {
    $this->buildingId = $buildingId;
  }
  
  public function execute()
  {
    $event = new ConstructBuildingTaskEvent([
      'base' => $this->base,
      'buildingId' => $this->getBuildingId(),
      'time' => $this->getTime(),
      'user' => $this->user,
    ]);

    $this->trigger(self::EVENT_BEFORE_BUILDING_CONSTRUCTED, $event );
    $this->base->increaseBuildingCounter( $this->getBuildingId() );
    $this->trigger(self::EVENT_AFTER_BUILDING_CONSTRUCTED, $event );
  }
}
