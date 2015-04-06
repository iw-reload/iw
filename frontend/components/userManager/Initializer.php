<?php

namespace frontend\components\userManager;

use frontend\components\userManager\UserManager;
use yii\base\Behavior;
use yii\di\Instance;

/**
 * Description of UserManagerInitializer
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class Initializer extends Behavior
{
  /**
   * @var string the application component ID of the UserManager
   */
  public $userManager = 'userManager';
  
  public function attach($owner)
  {
    parent::attach($owner);
    Instance::ensure( $this->userManager, UserManager::className() );
  }
}
