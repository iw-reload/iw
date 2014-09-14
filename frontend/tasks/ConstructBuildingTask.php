<?php

namespace frontend\tasks;

use frontend\tasks\BaseTask;
use common\models\Base;

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
    
  public function execute($params)
  {
    /* @var $base Base */
    $base = $params['base'];
    $base->increaseBuildingCounter( $this->getBuildingId() );
  }

}
