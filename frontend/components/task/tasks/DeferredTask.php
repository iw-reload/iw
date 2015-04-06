<?php

namespace frontend\components\task\tasks;

use common\models\Base;
use common\models\User;
use frontend\interfaces\BaseFinderInterface;
use frontend\interfaces\UserFinderInterface;

/**
 * 
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
abstract class DeferredTask extends AbstractTask
{
  /**
   * @var BaseFinderInterface
   */
  private $_baseFinder;
  /**
   * @var UserFinderInterface
   */
  private $_userFinder;

  public function init()
  {
    parent::init();
    
    if (!$this->_baseFinder instanceof BaseFinderInterface) {
      throw new \yii\base\InvalidConfigException('DeferredTask::$_baseFinder must be an instance of BaseFinderInterface.');
    }
    
    if (!$this->_userFinder instanceof UserFinderInterface) {
      throw new \yii\base\InvalidConfigException('DeferredTask::$_userFinder must be an instance of UserFinderInterface.');
    }
  }
  
  public function setBaseFinder(BaseFinderInterface $baseFinder) {
    $this->_baseFinder = $baseFinder;
  }
  
  public function setUserFinder(UserFinderInterface $userFinder) {
    $this->_userFinder = $userFinder;
  }
  
  /**
   * TODO: once we use this, we can't use $user->bases any more. :o(
   *       (loads two instances of the same DB record)
   * 
   * @param int $id
   * @return Base
   */
  protected function getBase($id) {
    return $this->_baseFinder->getBaseById($id);
  }
  
  /**
   * @param int $id
   * @return User
   */
  protected function getUser($id) {
    return $this->_userFinder->getUserById($id);
  }
}
