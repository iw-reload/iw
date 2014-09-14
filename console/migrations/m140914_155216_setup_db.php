<?php

use yii\db\Migration;

class m140914_155216_setup_db extends Migration
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
  
  public function up()
  {
    $script = Yii::getAlias('@app/../architecture/iw.sql');
    $user = $this->db->username;
    $pwd = $this->db->password;
    list ($host,$dbName) = $this->getDbInfo();    
    
    $command = "mysql -u{$user} -p{$pwd} -h {$host} -D {$dbName} < {$script}";
    $output = [];
    $result = 0;
    exec( $command, $output, $result );
    
    if (intval($result) === 0)
    {
      return true;
    }
    else
    {
      foreach ($output as $line) {
        echo $line . "\n";
      }
      return false;
    }
  }

  public function down()
  {
    echo "m140914_155216_setup_db cannot be reverted.\n";
    return false;
  }
}
