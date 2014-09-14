<?php

namespace frontend\objects;

use yii\base\Object;

/**
 * @author ben
 */
class ConstructionInfo extends Object
{
  public $base = '';
  public $building = '';
  public $finished = null;
  public $countdown = 0;
}
