<?php

namespace frontend\components\userManager;

use common\models\User;
use frontend\interfaces\UserFinderInterface;
use yii\base\Component;
use yii\base\Event;

/**
 * Loads users by id, ensuring they won't be loaded twice.
 *
 * @author ben
 */
class UserManager extends Component implements UserFinderInterface
{
  private $users = [];
  
  public function init()
  {
    parent::init();
    
    Event::on( User::className(), User::EVENT_AFTER_FIND, function ($event) {
      $this->addUser( $event->sender );
    });
  }

  /**
   * Will be called whenever a User is loaded and triggers its EVENT_AFTER_FIND.
   * 
   * @param User $model
   * @throws \yii\base\NotSupportedException
   */
  public function addUser( User $model )
  {
    $id = $model->id;
  
    if (array_key_exists($id,$this->users)) {
      throw new \yii\base\NotSupportedException( "User '$id' loaded twice." );
    }
    
    $this->users[$id] = $model;
  }
  
  public function getUserById( $id )
  {
    if (!array_key_exists($id,$this->users)) {
      $this->loadUser( $id );
    }
    
    return $this->users[$id];
  }

  /**
   * Loads a user. The user will be added to the manager's loaded users array
   * indirectly, because we attached to User::EVENT_AFTER_FIND.
   * 
   * @param int $id
   */
  private function loadUser( $id )
  {
    User::find()
      ->where(['id' => $id])
      ->one();
  }
}
