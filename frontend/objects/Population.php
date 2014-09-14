<?php

namespace frontend\objects;

use yii\base\Object;

/**
 * @author ben
 */
class Population extends Object
{
  public $growth = 0.0;
  public $max = 0;
  public $space = 0;
  public $employed = 0;
  public $current = 0;
}
