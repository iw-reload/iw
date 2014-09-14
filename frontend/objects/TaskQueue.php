<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\objects;

use frontend\interfaces\TaskInterface;

/**
 * Description of TaskQueue
 *
 * @author ben
 */
class TaskQueue
{
  private $tasks;
  
  /**
   * @param \frontend\interfaces\TaskInterface $task
   * @param mixed $params to be passed to the task when executed
   */
  public function addTask( TaskInterface $task, $params )
  {
    $task->setQueue( $this );
    
    $this->tasks[] = [
      'task' => $task,
      'params' => $params,
    ];
    
    // sort tasks by time
    // TODO optimize. Instead of inserting and sorting, insert at the correct
    // place.
    // @see "http://stackoverflow.com/a/3797526"
    usort( $this->tasks, function( $lhs, $rhs ){
      if ($lhs['task']->getTime() == $rhs['task']->getTime()) {
        return 0;
      }

      return ($lhs['task']->getTime() < $rhs['task']->getTime()) ? -1 : 1;
    });
  }
  
  public function execute()
  {
    // Don't use foreach or for
    // Some tasks might insert new tasks into the queue.
    while (!empty($this->tasks))
    {
      $entry = array_shift( $this->tasks );
      /* @var $task TaskInterface */
      $task = $entry['task'];
      $params = $entry['params'];
      $task->execute( $params );
    }
  }
}
