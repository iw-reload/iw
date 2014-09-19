<?php

namespace frontend\validators;

use yii\validators\Validator;
use common\models\User;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Description of BaseOwnedByUserValidator
 *
 * @author ben
 */
class BaseOwnedByUserValidator extends Validator
{
  /**
   * @var User
   */
  public $user = null;
  
  /**
   * @inheritdoc
   */
  public function init()
  {
    parent::init();
    
    if (!$this->user instanceof User) {
      throw new InvalidConfigException('BaseOwnedByUserValidator::$user must be an instance of common\models\User.');
    }
    
    if ($this->message === null) {
      $this->message = Yii::t('app', 'Base {value} is not owned by user {username}.');
    }
  }  
  
  protected function validateValue($value)
  {
    $baseOwnedByUser = false;
    
    foreach ($this->user->bases as $base) {
      if ($base->id == $value) {
        $baseOwnedByUser = true;
        break;
      }
    }
    
    if ($baseOwnedByUser)
    {
      $result = null;
    }
    else
    {
      $result = [ $this->message, [
        'value' => $value,
        'username' => $this->user->name,
      ]];
    }
    
    return $result;
  }

}
