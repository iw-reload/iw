<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\objects;

use frontend\interfaces\TaskInterface;

/**
 * Description of AbstractTask
 *
 * @author ben
 */
abstract class AbstractTask implements TaskInterface
{
  /**
   * @var TaskQueue
   */
  private $queue = null;
  
  /**
   * @var DateTime
   */
  private $time;
  
  public function getQueue() {
    return $this->queue;
  }

  public function setQueue($queue) {
    $this->queue = $queue;
  }
  
  public function getTime() {
    return $this->time;
  }

  public function setTime(\DateTime $time) {
    $this->time = $time;
  }

}
