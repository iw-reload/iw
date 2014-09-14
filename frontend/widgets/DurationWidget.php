<?php

namespace frontend\widgets;

use yii\base\Widget;
use Yii;

/**
 * Renders the time needed to build something (for buildings, ships, ...).
 * 
 * @author ben
 */
class DurationWidget extends Widget
{
  /**
   *
   * @var \DateInterval
   */
  public $duration = null;
  
  
  public function run()
  {
    // TODO get time from time component
    $now = new \DateTime();
    
    // TODO initialize formatter settings at begin of request from user settings
    Yii::$app->formatter->timeZone = 'Europe/Berlin';
    
    $end = clone $now;
    $end->add( $this->duration );
    
    // TODO introduce constant for DateInterval format
    $strDuration = $this->duration->format('%H:%I:%S');
    $strEnd = Yii::$app->formatter->asDatetime( $end );
    return "<div>Duration: {$strDuration}, until: {$strEnd}</div>";
  }

}
