<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\queries;

use yii\db\ActiveQuery;

/**
 * Description of TaskQuery
 *
 * @author ben
 */
class TaskQuery extends ActiveQuery
{
  public function finished()
  {
    // TODO create date time helper in order to support high resolution time
    // - always use $_SERVER['REQUEST_TIME_FLOAT'] as "now", so everything in
    //   one request happens at once.
    // - not sure if $_SERVER['REQUEST_TIME_FLOAT'] is available for cli
    //   invocations. Wrapper must handle it.
    // TODO ensure MySQL stores microseconds
    //      @see "http://dev.mysql.com/doc/refman/5.7/en/fractional-seconds.html"
    //      requires MySQL 5.6.4. (currently on 5.5.38)
    $now = \DateTime::createFromFormat( 'U.u', $_SERVER['REQUEST_TIME_FLOAT']);
    $this->andWhere( 'finished <= :now', [
      ':now' => $now->format('Y-m-d\TH:i:s.uO'),
    ]);
    return $this;
  }
}
