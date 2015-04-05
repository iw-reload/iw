<?php

namespace tests\codeception\common\unit\models;

use Codeception\Specify;
use common\entities\CelestialBody as CelestialBodyEntity;
use tests\codeception\common\unit\TestCase;
use Yii;

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
    
    $universeEntity = new \common\entities\Universe();
    $universeEntity->setMaxCelestialBodies( 1 );
    $universeEntity->setMaxSystems( 1 );
    $universeEntity->setMinCelestialBodies( 1 );
    $universeEntity->setMinSystems( 1 );
    $this->em->persist( $universeEntity );
    
    $galaxyEntity = new \common\entities\Galaxy();
    $galaxyEntity->setNumber( 0 );
    $galaxyEntity->setUniverse( $universeEntity );
    $this->em->persist( $galaxyEntity );
    
    $systemEntity = new \common\entities\System();
    $systemEntity->setGalaxy( $galaxyEntity );
    $systemEntity->setNumber( 0 );
    $this->em->persist( $systemEntity );
    
    $asteroidEntity = new \common\entities\Asteroid();
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
