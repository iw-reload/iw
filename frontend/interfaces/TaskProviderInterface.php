<?php

namespace frontend\interfaces;

/**
 * @author ben
 */
interface TaskProviderInterface
{
  /**
   * @return \common\models\Task[]
   */
  public function getTasks();
  /**
   * @return \common\models\Task[]
   */
  public function getFinishedTasks();
}
