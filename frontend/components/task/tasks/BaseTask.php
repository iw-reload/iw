<?php

namespace frontend\components\task\tasks;

use common\models\Base;
use frontend\components\task\tasks\AbstractTask;

/**
 * For Tasks related to a user's base.
 *
 * @property Base $base
 * @author ben
 */
abstract class BaseTask extends AbstractTask
{
  private $base;
  
  public function getBase() {
    return $this->base;
  }

  public function setBase(Base $base) {
    $this->base = $base;
  }
  
}
