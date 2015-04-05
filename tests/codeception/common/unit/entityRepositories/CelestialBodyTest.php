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
  private $celestialBodyForNewPlayer;
  
  public function setUp()
  {
    parent::setUp();
    
    $this->clearDb();
    
    // Prepare a universe with
    // - 2 galaxies (0: chaos, 1: normal)
    // - chaos galaxy has 1 system with one free planet
    // - normal galaxy has 2 systems
    // -- 0: system with 1 free planet but a nebula
    // -- 1: system with 1 free planet and 1 planet with an outpost
    
    $universeEntity = new \common\entities\Universe();
    $universeEntity->setMaxCelestialBodies( 2 );
    $universeEntity->setMaxSystems( 2 );
    $universeEntity->setMinCelestialBodies( 1 );
    $universeEntity->setMinSystems( 1 );
    $this->em->persist( $universeEntity );
    
    // --- chaos galaxy -------------------------------------------------------
    
    $g_0 = new \common\entities\Galaxy();
    $g_0->setNumber( 0 );
    $g_0->setUniverse( $universeEntity );
    $this->em->persist( $g_0 );
    
    $s_0_0 = new \common\entities\System();
    $s_0_0->setGalaxy( $g_0 );
    $s_0_0->setNumber( 0 );
    $this->em->persist( $s_0_0 );
    
    $cb_0_0_0 = new \common\entities\Asteroid();
    $cb_0_0_0->setNumber( 0 );
    $cb_0_0_0->setSystem( $s_0_0 );
    $this->em->persist( $cb_0_0_0 );
    
    // --- normal galaxy ------------------------------------------------------
    
    $g_1 = new \common\entities\Galaxy();
    $g_1->setNumber( 1 );
    $g_1->setUniverse( $universeEntity );
    $this->em->persist( $g_1 );

    // --- system with nebula -------------------------------------------------
    
    $redNebula = new \common\entities\SystemWideModifier();
    $redNebula->setLabel('roter Nebel');
    $redNebula->setNebulaColor(hexdec('FF0000'));
    $this->em->persist( $redNebula );
    
    $s_1_0 = new \common\entities\System();
    $s_1_0->setGalaxy( $g_1 );
    $s_1_0->setModifier( $redNebula );
    $s_1_0->setNumber( 0 );
    $this->em->persist( $s_1_0 );
    
    $cb_1_0_0 = new \common\entities\IceGiant();
    $cb_1_0_0->setNumber( 0 );
    $cb_1_0_0->setSystem( $s_1_0 );
    $this->em->persist( $cb_1_0_0 );
    
    // --- system without nebula ----------------------------------------------

    $s_1_1 = new \common\entities\System();
    $s_1_1->setGalaxy( $g_1 );
    $s_1_1->setNumber( 1 );
    $this->em->persist( $s_1_1 );
    
    $cb_1_1_0 = new \common\entities\Void();
    $cb_1_1_0->setNumber( 0 );
    $cb_1_1_0->setSystem( $s_1_1 );
    $this->em->persist( $cb_1_1_0 );
    
    $cb_1_1_1 = new \common\entities\TerrestrialPlanet();
    $cb_1_1_1->setNumber( 1 );
    $cb_1_1_1->setSystem( $s_1_1 );
    $this->em->persist( $cb_1_1_1 );
    
    $outpostEntity = new \common\entities\Outpost();
    $outpostEntity->setName("Someone's outpost");
    $outpostEntity->setCelestialBody( $cb_1_1_1 );
    $this->em->persist( $outpostEntity );
    $this->em->flush();
    
    // $cb_1_1_0 is the only celestial body suitable for a new player.
    // all other celestial bodies are in a chaos galaxy, in a system with
    // nebula, or have an outpost
    $this->celestialBodyForNewPlayer = $cb_1_1_0;
    $this->repo = $this->em->getRepository( CelestialBodyEntity::class );
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->shallowClone();
  }

  public function testFindForNewPlayer()
  {
    $this->specify('CelestialBodyRepository should be able to find a Celestial Body for a new player', function () {
      $celestialBody = $this->repo->findForNewPlayer();
      expect('celestial body should be found', $celestialBody instanceof CelestialBodyEntity)->equals( true );
      expect('the celestial body should not have an owner', $celestialBody->hasOutpost())->equals( false );
      expect('the celestial body should not be in a chaos galaxy', $celestialBody->getSystem()->getGalaxy()->getNumber() % 4)->notEquals( 0 );
      expect('the celestial body should not be in a system causing effects', $celestialBody->getSystem()->hasModifier())->false();
    });
    
    // Specify clones our properties, this makes "$this->freeCelestialBody" a
    // new object. Since doctrine uses OIDs to identify objects, the cloning
    // causes the cloned object to be identified as "not managed".
    // --> Deactivate cloning!
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->ignore('celestialBodyForNewPlayer');
    
    $this->specify('CelestialBodyRepository should not be able to find a Celestial Body for a new player if there are no more', function ()
    {
      // after this, there are no more free celestial bodies left.
      $outpostEntity = new \common\entities\Outpost();
      $outpostEntity->setName("Another outpost");
      $outpostEntity->setCelestialBody( $this->celestialBodyForNewPlayer );
      
      $this->em->persist( $outpostEntity );
      $this->em->flush();
      
      // Since there's no more free celestial body, we expect this method to throw an exception.
      $this->repo->findForNewPlayer();
    },[
      'throws' => 'Doctrine\ORM\NoResultException',
    ]);
  }
}
