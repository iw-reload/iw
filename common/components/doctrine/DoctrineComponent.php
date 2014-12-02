<?php

namespace common\components\doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;

/**
 * Description of DoctrineComponent
 *
 * @author ben
 */
class DoctrineComponent extends \yii\base\Component
{
  private $_connection = null;
  private $_entityManager = null;
  
  /**
   * This will be used to create the DBAL connection.
   * 
   * @see "http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html"
   * @var array
   */
  public $connection = [
    // $connectionParams array
    'params' => [],
    // \Doctrine\DBAL\Configuration instance or callback creating such an instance
    'config' => null,
    // \Doctrine\Common\EventManager instance or callback creating such an instance
    'eventManager' => null,
  ];

  /**
   * This will be used to create the EntityManager instance.
   *
   * The connection used to create the EntityManager will always be created by
   * a call to getConnection(). You can configure how it will be created using
   * DoctrineComponent::$connection.
   * 
   * @see "http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/advanced-configuration.html"
   * @var array
   */
  public $entityManager = [
    // \Doctrine\ORM\Configuration instance or callback creating such an instance
    'config' => null,
    // \Doctrine\Common\EventManager instance or callback creating such an instance
    'eventManager' => null,
  ];
  
  /**
   * @return \Doctrine\ORM\EntityManagerInterface
   */
  public function getEntityManager()
  {
    if ($this->_entityManager === null) {
      $this->createEntityManager();
    }
    
    return $this->_entityManager;
  }
  
  
  /**
   * @return \Doctrine\DBAL\Connection
   */
  public function getConnection()
  {
    if ($this->_connection === null) {
      $this->createConnection();
    }
    
    return $this->_connection;
  }
  

  private function createEntityManager()
  {
    $conn = $this->getConnection();
    $config = array_key_exists('config', $this->entityManager) ? $this->entityManager['config'] : null;
    $eventManager = array_key_exists('eventManager', $this->entityManager) ? $this->entityManager['eventManager'] : null;
    
    $this->_entityManager = EntityManager::create(
      $conn
      , is_callable($config) ? call_user_func($config) : $config
      , is_callable($eventManager) ? call_user_func($eventManager) : $eventManager
      );
  }
  
  
  private function createConnection()
  {
    $connectionParams = array_key_exists('params', $this->connection) ? $this->connection['params'] : [];
    $config = array_key_exists('config', $this->connection) ? $this->connection['config'] : null;
    $eventManager = array_key_exists('eventManager', $this->connection) ? $this->connection['eventManager'] : null;
    
    $this->_connection = DriverManager::getConnection(
      $connectionParams
      , is_callable($config) ? call_user_func($config) : $config
      , is_callable($eventManager) ? call_user_func($eventManager) : $eventManager
      );
  }
}
