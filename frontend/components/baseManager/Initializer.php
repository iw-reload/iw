<?php

namespace frontend\components\baseManager;

use frontend\components\baseManager\BaseManager;
use yii\base\Behavior;
use yii\di\Instance;

/**
 * Description of BaseManagerInitializer
 *
 * @author ben
 */
class Initializer extends Behavior
{
  /**
   * @var string the application component ID of the BaseManager
   */
  public $baseManager = 'baseManager';
  
  public function attach($owner)
  {
    parent::attach($owner);
    Instance::ensure( $this->baseManager, BaseManager::className() );
  }
}
