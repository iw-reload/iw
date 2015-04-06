<?php

namespace frontend\interfaces;

/**
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
interface TaskInterface
{
  /**
   * @return \DateTime The task's time context.
   */
  public function getTime();
  /**
   * @param \DateTime $time The task's time context.
   */
  public function setTime( \DateTime $time );
  /**
   * Executes the task.
   */
  public function execute();
}
