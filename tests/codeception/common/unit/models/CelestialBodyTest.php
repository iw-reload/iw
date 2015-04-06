<?php

namespace tests\codeception\common\unit\models;

use Codeception\Specify;
use common\entities\celestialBodies\Asteroid as AsteroidEntity;
use common\entities\CelestialBody as CelestialBodyEntity;
use common\entities\Galaxy as GalaxyEntity;
use common\entities\System as SystemEntity;
use common\entities\Universe as UniverseEntity;
use tests\codeception\common\unit\TestCase;

class CelestialBodyTest extends TestCase
{
  use Specify;

  /**
   * @var CelestialBodyEntity
   */
  private $celestialBody = null;
  
  public function setUp()
  {
    parent::setUp();
    
    // Prepare a universe with one planet.
    // 1) an asteroid
    
    $universeEntity = new UniverseEntity();
    $universeEntity->setMaxCelestialBodies( 1 );
    $universeEntity->setMaxSystems( 1 );
    $universeEntity->setMinCelestialBodies( 1 );
    $universeEntity->setMinSystems( 1 );
    $this->em->persist( $universeEntity );
    
    $galaxyEntity = new GalaxyEntity();
    $galaxyEntity->setNumber( 0 );
    $galaxyEntity->setUniverse( $universeEntity );
    $this->em->persist( $galaxyEntity );
    
    $systemEntity = new SystemEntity();
    $systemEntity->setGalaxy( $galaxyEntity );
    $systemEntity->setNumber( 0 );
    $this->em->persist( $systemEntity );
    
    $asteroidEntity = new AsteroidEntity();
    $asteroidEntity->setNumber( 0 );
    $asteroidEntity->setSystem( $systemEntity );
    $this->em->persist( $asteroidEntity );
    
    $this->em->flush();
    
    $this->em = $this->em;
    $this->celestialBody = $asteroidEntity;
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->shallowClone();
  }
}
