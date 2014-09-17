<?php

use console\migrations\SqlScriptMigration;

class m140917_213245_add_external_identities extends SqlScriptMigration
{
  public function up()
  {
    return $this->executeScript( '@app/../architecture/iw-140917_213245_add_external_identities.sql' );
  }

  public function down()
  {
    echo "m140917_213245_add_external_identities cannot be reverted.\n";
    return false;
  }
}
