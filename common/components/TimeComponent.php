<?php

namespace common\components;

use yii\base\Component;

/**
 * Description of TimeComponent
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class TimeComponent extends Component
{
  private $_startTime = null;

  public function getStartTime()
  {
    if ($this->_startTime === null) {
      $this->initRequestTime();
    }
    
    return $this->_startTime;
  }
  
  private function initRequestTime()
  {
    // @see "https://bugs.php.net/bug.php?id=61497"
//    $requestTimeFloat = filter_input( INPUT_SERVER, 'REQUEST_TIME_FLOAT', FILTER_VALIDATE_FLOAT );
//    
//    if ($requestTimeFloat)
//    {
//      $this->_startTime = \DateTime::createFromFormat( 'U.u', $requestTimeFloat );
//    }
//    else
//    {
//      $this->_startTime = new \DateTime();
//    }
    
    if (array_key_exists('REQUEST_TIME_FLOAT',$_SERVER))
    {
      $requestTimeFloat = filter_var( $_SERVER['REQUEST_TIME_FLOAT'], FILTER_VALIDATE_FLOAT );
      
      if ($requestTimeFloat) {
        $this->_startTime = \DateTime::createFromFormat( 'U.u', $requestTimeFloat );
      }
    }
    
    if ($this->_startTime === null) {
      $this->_startTime = new \DateTime();
    }
  }
}
