<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\interfaces;

/**
 *
 * @author ben
 */
interface TaskInterface
{
  public function getTime();
  public function setTime( \DateTime $time );
  
  public function getQueue();
  public function setQueue( $queue );
  
  public function execute( $user );
}
