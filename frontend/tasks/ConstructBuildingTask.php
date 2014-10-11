<?php

namespace frontend\tasks;

use frontend\tasks\BaseTask;
use common\models\User;

/**
 * Constructs a building on a base.
 *
 * @author ben
 */
class ConstructBuildingTask extends BaseTask
{
  private $buildingId;
  
  public function getBuildingId() {
    return $this->buildingId;
  }

  public function setBuildingId($buildingId) {
    $this->buildingId = $buildingId;
  }
  
  /**
   * @param User $user
   */
  public function execute( $user )
  {
    foreach ($user->bases as $base)
    {
      $baseId = (int)$base->id;
      $taskBaseId = (int)$this->getBaseId();
      
      if ($baseId !== $taskBaseId) {
        continue;
      }
      
      $base->increaseBuildingCounter( $this->getBuildingId() );
      break;
    }
  }

}
