<?php

namespace frontend\interfaces;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
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
