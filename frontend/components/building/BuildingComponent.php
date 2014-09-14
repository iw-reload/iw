<?php

namespace frontend\components\building;

use frontend\interfaces\BuildingFinderInterface;
use yii\base\Component;
use yii\caching\Cache;
use yii\caching\DbDependency;
use yii\db\Connection;
use yii\di\Instance;
use PDO;


/**
 * Component providing access to building definitions.
 * 
 * It cares for reading the definitions from database and for caching them,
 * so we don't need to access the database too often.
 * 
 * Since the frontend application is not meant to modify building definitions,
 * this component only provides read-only access.
 * 
 * @author ben
 */
class BuildingComponent extends Component implements BuildingFinderInterface
{
  private $buildings = null;
  private $buildingsByGroup = null;
  
  /**
    * @var string the application component ID of the DB connection.
    */
  public $db = 'db';
  
  /**
    * @var string the application component ID of the cache.
    */
  public $cache = 'cache';
  
  /**
   * @param int $id
   * @return frontend\models\Building
   */
  public function getById( $id )
  {
    $buildings = $this->getBuildings();
    return $buildings[$id];
  }
  
  /**
   * @return string[]
   */
  public function getGroups()
  {
    $buildingsByGroup = $this->getBuildingsByGroup();
    return array_keys( $buildingsByGroup );
  }
  
  /**
   * @param string $group
   * @return frontend\models\Building[]
   */
  public function getByGroup( $group )
  {
    $buildingsByGroup = $this->getBuildingsByGroup();
    return array_key_exists( $group, $buildingsByGroup )
      ? $buildingsByGroup[$group]
      : [];
  }
  
  /**
   * @return Building[] the buildings indexed by the building's id.
   */
  private function getBuildings()
  {
    if ($this->buildings === null) {
      $this->buildings = $this->getCachedBuildings();
    }
    
    return $this->buildings;
  }
  
  /**
   * @return array of ImmutableBuilding[]. First array is indexed by the
   * building's group, the second array is indexed by the building's id.
   */
  private function getBuildingsByGroup()
  {
    // If not already available, group buildings using the cached buildings
    // list.
    // We could cache the grouped buildings themselves, but I'm not sure
    // if it is worth the effort. Grouping should be fast (objects are not
    // copied, only referenced), while caching includes (de)serializing.
    // Also, we needed to care for cache dependencies once again, which meant
    // another call to the DB, or some way to reference the cache dependency
    // of the cached buildings list. Not sure if this is possible, but
    // yii\caching\GroupDependency might do the trick.
    if ($this->buildingsByGroup === null)
    {
      $this->buildingsByGroup = [];
      array_walk($this->getBuildings(), function($building) {
        /* @var $building Building */
        $this->buildingsByGroup[$building->group][$building->id] = $building;
      });
    }
    
    return $this->buildingsByGroup;
  }

  /*
   * @return ImmutableBuilding[] the buildings indexed by their id.
   */
  private function getCachedBuildings()
  {
    $cache = Instance::ensure( $this->cache, Cache::className() );
    /* @var $cache Cache */
    
    $key = __METHOD__;
    $value = $cache->get( $key );
    
    if ($value === false)
    {
      $value = $this->readBuildingsFromDb();
      
      // This statement will be executed whenever $cache->get() or
      // $cache->set() is called.
      $cacheDependency = new DbDependency([
        'db'  => $this->db,
        'sql' => 'SELECT modified FROM iw_reload.building ORDER BY modified DESC LIMIT 1;',
      ]);
      
      // The value will never expire (special value 0)
      // It may be invalidated earlier if a building's data is changed in the
      // database.
      $cache->set( $key, $value, 0, $cacheDependency );
    }
    
    return $value;
  }
  
  /**
   * Reads all buildings from database.
   * 
   * We don't support a $modifiedSince parameter, because it would not detect
   * deleted rows. Instead, we simply read the definition of all buildings.
   * Basically, since building definitions are not expected to change, we can
   * keep the result in cache. So this method should not be called very often.
   * 
   * @return Building[] the buildings defined in the database in an array,
   * indexed by the building's PK.
   */
  private function readBuildingsFromDb()
  {
    $db = Instance::ensure( $this->db, Connection::className() );
    /* @var $db Connection */

    $sql = <<<'EOT'
SELECT [[id]],
  [[group]],
  [[name]],
  [[image]],
  [[description]],
  [[cost_iron]] AS costIron,
  [[cost_steel]] AS costSteel,
  [[cost_chemicals]] AS costChemicals,
  [[cost_vv4a]] AS costVv4a,
  [[cost_ice]] AS costIce,
  [[cost_water]] AS costWater,
  [[cost_energy]] AS costEnergy,
  [[cost_people]] AS costPeople,
  [[cost_credits]] AS costCredits,
  TIME_FORMAT( [[cost_time]], 'PT%kH%iM%sS' ) as [[costTime]],
  [[balance_iron]] AS balanceIron,
  [[balance_steel]] AS balanceSteel,
  [[balance_chemicals]] AS balanceChemicals,
  [[balance_vv4a]] AS balanceVv4a,
  [[balance_ice]] AS balanceIce,
  [[balance_water]] AS balanceWater,
  [[balance_energy]] AS balanceEnergy,
  [[balance_people]] AS balancePeople,
  [[balance_credits]] AS balanceCredits,
  [[balance_satisfaction]] AS balanceSatisfaction,
  [[storage_chemicals]] AS storageChemicals,
  [[storage_ice_and_water]] AS storageIceAndWater,
  [[storage_energy]] AS storageEnergy,
  [[shelter_iron]] AS shelterIron,
  [[shelter_steel]] AS shelterSteel,
  [[shelter_chemicals]] AS shelterChemicals,
  [[shelter_vv4a]] AS shelterVv4a,
  [[shelter_ice_and_water]] AS shelterIceAndWater,
  [[shelter_energy]] AS shelterEnergy,
  [[shelter_people]] AS shelterPeople,
  [[highscore_points]] AS highscorePoints
FROM {{%building}}
;
EOT;
    $mutableBuildings = [];
    $dataReader = $db->createCommand( $sql )->query();
    while ($row = $dataReader->read())
    {
      $building = new MutableBuilding();
      $building->setBalanceChemicals( $row['balanceChemicals'] );
      $building->setBalanceCredits( $row['balanceCredits'] );
      $building->setBalanceEnergy( $row['balanceEnergy'] );
      $building->setBalanceIce( $row['balanceIce'] );
      $building->setBalanceIron( $row['balanceIron'] );
      $building->setBalancePeople( $row['balancePeople'] );
      $building->setBalanceSatisfaction( $row['balanceSatisfaction'] );
      $building->setBalanceSteel( $row['balanceSteel'] );
      $building->setBalanceVv4a( $row['balanceVv4a'] );
      $building->setBalanceWater( $row['balanceWater'] );
      $building->setCostChemicals( $row['costChemicals'] );
      $building->setCostCredits( $row['costCredits'] );
      $building->setCostEnergy( $row['costEnergy'] );
      $building->setCostIce( $row['costIce'] );
      $building->setCostIron( $row['costIron'] );
      $building->setCostPeople( $row['costPeople'] );
      $building->setCostSteel( $row['costSteel'] );
      $building->setCostTime( $row['costTime'] );
      $building->setCostVv4a( $row['costVv4a'] );
      $building->setCostWater( $row['costWater'] );
      $building->setDescription( $row['description'] );
      $building->setGroup( $row['group'] );
      $building->setHighscorePoints( $row['highscorePoints'] );
      $building->setId( $row['id'] );
      $building->setImage( $row['image'] );
      $building->setName( $row['name'] );
      $building->setShelterChemicals( $row['shelterChemicals'] );
      $building->setShelterEnergy( $row['shelterEnergy'] );
      $building->setShelterIceAndWater( $row['shelterIceAndWater'] );
      $building->setShelterIron( $row['shelterIron'] );
      $building->setShelterPeople( $row['shelterPeople'] );
      $building->setShelterSteel( $row['shelterSteel'] );
      $building->setShelterVv4a( $row['shelterVv4a'] );
      $building->setStorageChemicals( $row['storageChemicals'] );
      $building->setStorageEnergy( $row['storageEnergy'] );
      $building->setStorageIceAndWater( $row['storageIceAndWater'] );
      
      $mutableBuildings[$building->getId()] = $building;
    }
    
    return $mutableBuildings;
  }
  
}



