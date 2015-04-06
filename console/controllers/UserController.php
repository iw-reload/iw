<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\User;

/**
 * Description of UserController
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class UserController extends Controller
{
  public function __construct($id, $module, $config = array())
  {
    $this->defaultAction = 'create';
    parent::__construct($id, $module, $config);
  }

  public function actionCreate( $userName )
  {
    $user = new User();
    $user->name = $userName;
    
    if (!$user->save())
    {
      foreach ($user->getFirstErrors() as $error) {
        echo $error . "\n";
      }
    }
  }
}
