<?php

namespace tests\codeception\common\unit\entityRepositories;

use Codeception\Specify;
use common\entities\CelestialBody as CelestialBodyEntity;
use common\entityRepositories\CelestialBody as CelestialBodyRepository;
use tests\codeception\common\unit\TestCase;
use Yii;

/**
 * Login form test
 */
class CelestialBodyTest extends TestCase
{
  use Specify;

  /**
   * @var CelestialBodyRepository
   */
  private $repo = null;
  /**
   * @var CelestialBodyEntity
   */
  private $freeCelestialBody;
  
  public function setUp()
  {
    parent::setUp();
    
    $this->clearDb();
    
    // Prepare a universe with two planets.
    // 1) an asteroid
    // 2) an ice giant
    // The ice giant has an outpost on it, the asteroid is free.
    
    $universeEntity = new \common\entities\Universe();
    $universeEntity->setMaxCelestialBodies( 2 );
    $universeEntity->setMaxSystems( 1 );
    $universeEntity->setMinCelestialBodies( 2 );
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
    
    $iceGiantEntity = new \common\entities\IceGiant();
    $iceGiantEntity->setNumber( 1 );
    $iceGiantEntity->setSystem( $systemEntity );
    $this->em->persist( $iceGiantEntity );
    
    $outpostEntity = new \common\entities\Outpost();
    $outpostEntity->setName("Someone's outpost");
    $outpostEntity->setCelestialBody( $iceGiantEntity );
    $this->em->persist( $outpostEntity );
    $this->em->flush();
    
    $this->freeCelestialBody = $asteroidEntity;
    $this->repo = $this->em->getRepository('common\entities\CelestialBody');
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->shallowClone();
  }

  public function testGetFreeCelestialBody()
  {
    $this->specify('CelestialBodyRepository should be able to find free Celestial Bodies', function () {
      $celestialBody = $this->repo->getFreeCelestialBody();
      expect('celestial body should be found', $celestialBody instanceof CelestialBodyEntity)->equals( true );
      expect('the celestial body should not have an owner', $celestialBody->hasOutpost())->equals( false );
      expect('the celestial body has the correct id', $celestialBody->getId())->equals( $this->freeCelestialBody->getId() );
    });
    
    // Specify clones our properties, this makes "$this->freeCelestialBody" a
    // new object. Since doctrine uses OIDs to identify objects, the cloning
    // causes the cloned object to be identified as "not managed".
    // --> Deactivate cloning!
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->ignore('freeCelestialBody');
    
    $this->specify('CelestialBodyRepository should not be able to find free Celestial Bodies if there are not more', function ()
    {
      // after this, there are no more free celestial bodies left.
      $outpostEntity = new \common\entities\Outpost();
      $outpostEntity->setName("Another outpost");
      $outpostEntity->setCelestialBody( $this->freeCelestialBody );
      
      $this->em->persist( $outpostEntity );
      $this->em->flush();
      
      // Since there's no more free celestial body, we expect this method to throw an exception.
      $this->repo->getFreeCelestialBody();
    },[
      'throws' => 'Doctrine\ORM\NoResultException',
    ]);
  }

}
