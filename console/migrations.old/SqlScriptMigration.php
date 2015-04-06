<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;

/**
 * Description of SqlScriptMigration
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class SqlScriptMigration extends Migration
{
  private function getDbInfo()
  {
    list($driver,$connectionString) = split(':', $this->db->dsn);
    $connectionConfig = split(';',$connectionString);

    $result = [
      0 => '',
      1 => '',
    ];
    
    foreach ($connectionConfig as $configItem)
    {
      list($key,$value) = split( '=', $configItem );
      
      if ($key === 'host') {
        $result[0] = $value;
      }
      
      if ($key === 'dbname') {
        $result[1] = $dbname = $value;
      }
    }
    
    return $result;
  }
  
  protected function executeScript( $filepath )
  {
    $script = Yii::getAlias( $filepath );
    $user = $this->db->username;
    $pwd = $this->db->password;
    list ($host,$dbName) = $this->getDbInfo();    
    
    $command = "mysql -u{$user} -p{$pwd} -h {$host} -D {$dbName} < {$script}";
    $output = [];
    $commandResult = 0;
    exec( $command, $output, $commandResult );
    
    if (intval($commandResult) === 0)
    {
      $result = true;
    }
    else
    {
      foreach ($output as $line) {
        echo $line . "\n";
      }
      $result = false;
    }
    
    return $result;
  }
}
