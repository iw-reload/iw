<?php

namespace frontend\objects;

use yii\base\Object;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ConstructionInfo extends Object
{
  public $base = '';
  public $building = '';
  public $finished = null;
  public $countdown = 0;
}
