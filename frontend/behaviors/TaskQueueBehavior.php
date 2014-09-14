<?php

namespace frontend\behaviors;

use frontend\objects\TaskQueue;
use yii\base\Behavior;

/**
 * Provides the owner with a TaskQueue for executing tasks.
 *
 * @author ben
 */
class TaskQueueBehavior extends Behavior
{
  /**
   * @var TaskQueue
   */
  public $taskQueue = null;
  
  public function init() {
    parent::init();
    $this->taskQueue = new TaskQueue();
  }
}
