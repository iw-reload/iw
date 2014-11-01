<?php

namespace frontend\widgets;

use common\components\TimeComponent;
use yii\base\Widget;
use yii\di\Instance;
use Yii;

/**
 * Renders the time needed to build something (for buildings, ships, ...).
 * 
 * @author ben
 */
class DurationWidget extends Widget
{
  /**
   * @var string the application component ID of the TimeComponent
   */
  public $time = 'time';
  
  /**
   *
   * @var \DateInterval
   */
  public $duration = null;
  
  public function run()
  {
    // TODO initialize formatter settings at begin of request from user settings
    Yii::$app->formatter->timeZone = 'Europe/Berlin';
    
    $now = $this->getTimeComponent()->getStartTime();
    $end = clone $now;
    $end->add( $this->duration );
    
    // TODO introduce constant for DateInterval format
    $strDuration = $this->duration->format('%H:%I:%S');
    $strEnd = Yii::$app->formatter->asDatetime( $end );
    return "<div>Duration: {$strDuration}, until: {$strEnd}</div>";
  }

  /**
   * @return TimeComponent
   */
  private function getTimeComponent() {
    return Instance::ensure( $this->time, TimeComponent::className() );
  }
}
