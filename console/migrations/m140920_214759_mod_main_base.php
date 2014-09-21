<?php

use console\migrations\SqlScriptMigration;
use common\components\universe\UniverseComponent;

class m140920_214759_mod_main_base extends SqlScriptMigration
{
  public function safeUp()
  {
    $result = $this->executeScript( '@app/../architecture/iw-140920_214759_mod_main_base.sql' );
    
    if ($result)
    {
      /* @var $universe UniverseComponent */
      $universe = \Yii::$app->universe;
      $planets = $universe->createUniverse();
      
      foreach ($planets as $planet) {
        $planet->save();
      }
    }
    
    return $result;
  }

  public function down()
  {
    echo "m140920_214759_mod_main_base cannot be reverted.\n";
    return false;
  }
}
