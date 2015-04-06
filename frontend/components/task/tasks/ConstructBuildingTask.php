<?php

namespace frontend\components\task\tasks;

use frontend\components\task\events\ConstructBuildingTaskEvent;
use frontend\components\task\tasks\DeferredTask;

/**
 * Constructs a building on a base.
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ConstructBuildingTask extends DeferredTask
{
  const EVENT_BEFORE_BUILDING_CONSTRUCTED = 'EVENT_BEFORE_BUILDING_CONSTRUCTED';
  const EVENT_AFTER_BUILDING_CONSTRUCTED = 'EVENT_AFTER_BUILDING_CONSTRUCTED';
  
  private $baseId;
  private $buildingId;
  private $userId;

  public function init()
  {
    parent::init();
    
    if (!is_int($this->baseId)) {
      throw new \yii\base\InvalidConfigException('ConstructBuildingTask::$baseId must be an integer.');
    }
    
    if (!is_int($this->buildingId)) {
      throw new \yii\base\InvalidConfigException('ConstructBuildingTask::$buildingId must be an integer.');
    }
    
    if (!is_int($this->userId)) {
      throw new \yii\base\InvalidConfigException('ConstructBuildingTask::$userId must be an integer.');
    }
  }
  
  public function getBaseId() {
    return $this->baseId;
  }

  public function setBaseId($baseId) {
    $this->baseId = $baseId;
  }

  public function getBuildingId() {
    return $this->buildingId;
  }

  public function setBuildingId($buildingId) {
    $this->buildingId = $buildingId;
  }
  
  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }
    
  public function execute()
  {
    parent::execute();
    
    $base = $this->getBase( $this->getBaseId() );
    $event = new ConstructBuildingTaskEvent([
      'base' => $base,
      'buildingId' => $this->getBuildingId(),
      'time' => $this->getTime(),
      'user' => $this->getUser($this->getUserId()),
    ]);

    $this->trigger(self::EVENT_BEFORE_BUILDING_CONSTRUCTED, $event );
    $base->increaseBuildingCounter( $this->getBuildingId() );
    $this->trigger(self::EVENT_AFTER_BUILDING_CONSTRUCTED, $event );
  }
}
