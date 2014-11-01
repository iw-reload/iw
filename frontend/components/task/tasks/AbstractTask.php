<?php

namespace frontend\components\task\tasks;

use common\models\User;
use frontend\components\task\events\ModelModifiedEvent;
use frontend\interfaces\TaskInterface;
use yii\base\Component;

/**
 * Description of AbstractTask
 *
 * @property User $user
 * @author ben
 */
abstract class AbstractTask extends Component implements TaskInterface
{
  const EVENT_MODEL_MODIFIED = 'EVENT_MODEL_MODIFIED';
  
  /**
   * @var \DateTime
   */
  private $time;
  /**
   * @var User
   */
  private $user;
  
  public function getTime() {
    return $this->time;
  }

  public function setTime( \DateTime $time ) {
    $this->time = $time;
  }
  
  public function getUser() {
    return $this->user;
  }

  public function setUser(User $user) {
    $this->user = $user;
  }
    
  /**
   * @param \yii\db\ActiveRecordInterface $model
   */
  protected function triggerModelModified( $model )
  {
    $this->trigger(self::EVENT_MODEL_MODIFIED, new ModelModifiedEvent([
      'model' => $model,
    ]));
  }
}
