<?php

namespace frontend\components\task\events;

use yii\base\Event;

/**
 * @author ben
 */
class ModelModifiedEvent extends Event
{
  /**
   * @var \yii\db\ActiveRecordInterface
   */
  public $model;
}
