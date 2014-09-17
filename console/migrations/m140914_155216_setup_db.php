<?php


class m140914_155216_setup_db extends SqlScriptMigration
{  
  public function up()
  {
    return $this->executeScript( '@app/../architecture/iw-140914_155216_setup_db.sql' );
  }

  public function down()
  {
    echo "m140914_155216_setup_db cannot be reverted.\n";
    return false;
  }
}
