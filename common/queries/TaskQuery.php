<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\queries;

use common\components\TimeComponent;
use yii\db\ActiveQuery;
use yii\di\Instance;

/**
 * Description of TaskQuery
 *
 * @author ben
 */
class TaskQuery extends ActiveQuery
{
  /**
   * @var string the application component ID of the TimeComponent
   */
  public $time = 'time';
  
  public function finished()
  {
    // TODO ensure MySQL stores microseconds
    //      @see "http://dev.mysql.com/doc/refman/5.7/en/fractional-seconds.html"
    //      requires MySQL 5.6.4. (currently on 5.5.38)
    $now = $this->getTimeComponent()->getStartTime();
    $this->andWhere( 'finished <= :now', [
      ':now' => $now->format('Y-m-d\TH:i:s.uO'),
    ]);
    return $this;
  }
  
  /**
   * @return TimeComponent
   */
  private function getTimeComponent() {
    return Instance::ensure( $this->time, TimeComponent::className() );
  }
}
