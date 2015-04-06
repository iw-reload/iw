<?php

namespace frontend\components\task\events;

use yii\base\Event;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ModelModifiedEvent extends Event
{
  /**
   * @var \yii\db\ActiveRecordInterface
   */
  public $model;
}
