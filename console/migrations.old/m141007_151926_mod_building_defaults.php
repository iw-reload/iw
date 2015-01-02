<?php

use console\migrations\SqlScriptMigration;

class m141007_151926_mod_building_defaults extends SqlScriptMigration
{
  public function up()
  {
    return $this->executeScript( '@app/../architecture/iw-141007_151926_mod_building_defaults.sql' );
  }
}
