<?php

namespace frontend\tasks;

use frontend\objects\AbstractTask;


/**
 * For Tasks related to a user's base.
 *
 * @author ben
 */
abstract class BaseTask extends AbstractTask
{
  private $baseId;
  
  public function getBaseId() {
    return $this->baseId;
  }

  public function setBaseId($baseId) {
    $this->baseId = $baseId;
  }
  
}
