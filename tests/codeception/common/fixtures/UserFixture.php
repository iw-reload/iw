<?php

namespace tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class UserFixture extends ActiveFixture
{
  public function __construct($config = [])
  {
    $this->tableName = 'user';
    parent::__construct($config);
  }

}
