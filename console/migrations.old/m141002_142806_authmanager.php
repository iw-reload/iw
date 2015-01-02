<?php

use console\migrations\SqlScriptMigration;

class m141002_142806_authmanager extends SqlScriptMigration
{
  public function safeUp()
  {
    return $this->executeScript( '@yii/rbac/migrations/schema-mysql.sql' );
  }
}
