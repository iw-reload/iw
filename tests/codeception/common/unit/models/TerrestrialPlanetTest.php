<?php

namespace tests\codeception\common\unit\models;

use Codeception\Specify;
use common\entities\CelestialBody as CelestialBodyEntity;
use common\entityRepositories\CelestialBodySpecialty as CelestialBodySpecialtyRepository;
use common\objects\CelestialBodyFactory;
use tests\codeception\common\unit\TestCase;
use Yii;

class TerrestrialPlanetTest extends TestCase
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
    // 1) an terrestrial planet
    
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
    
    $terrestrialPlanetEntity = new \common\entities\TerrestrialPlanet();
    $terrestrialPlanetEntity->setNumber( 0 );
    $terrestrialPlanetEntity->setSystem( $systemEntity );
    $this->em->persist( $terrestrialPlanetEntity );
    
    $this->em->flush();
    
    $this->celestialBody = $terrestrialPlanetEntity;
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->shallowClone();
  }

  public function testReset()
  {
    $celestialBodyEntity = $this->celestialBody;
    $celestialBodySpecialtiesRepository = $this->em->getRepository( \common\entities\CelestialBodySpecialty::class );
    /* @var $celestialBodySpecialtiesRepository CelestialBodySpecialtyRepository */
    
    $this->specify
      ( 'Resetting a celestial body should make it suitable for new players.'
      , function () use ($celestialBodyEntity, $celestialBodySpecialtiesRepository)
    {
      $terrestrialPlanetModel = CelestialBodyFactory::create( $celestialBodyEntity, $celestialBodySpecialtiesRepository );
      $terrestrialPlanetModel->reset();

      expect( 'there should be no outpost on the celestial body', $celestialBodyEntity->getOutpost() )
        ->null();
      expect( 'the celestial body should have one specialty', $celestialBodyEntity->getSpecialties()->count() )
        ->equals( 1 );
      expect( 'the celestial body should have a moon', $celestialBodyEntity->getSpecialties() )
        ->contains( $celestialBodySpecialtiesRepository->getMoon() );
      expect( 'building costs should be at 100%', $celestialBodyEntity->getSpecs()->getEffects()->getBuildingCost() )
        ->equals( 1.0 );
      expect( 'building time should be at 100%', $celestialBodyEntity->getSpecs()->getEffects()->getBuildingTime() )
        ->equals( 1.0 );
      expect( 'fleet scanner range should be at 100%', $celestialBodyEntity->getSpecs()->getEffects()->getFleetScannerRange() )
        ->equals( 1.0 );
      expect( 'research should be at 100%', $celestialBodyEntity->getSpecs()->getEffects()->getResearchPoints() )
        ->equals( 1.0 );
      expect( 'taxes should be at 100%', $celestialBodyEntity->getSpecs()->getEffects()->getTaxes() )
        ->equals( 1.0 );
      expect( 'gravity should be at 100%', $celestialBodyEntity->getSpecs()->getGravity() )
        ->equals( 1.0 );
      expect( 'living conditions should be at 100%', $celestialBodyEntity->getSpecs()->getLivingConditions() )
        ->equals( 1.0 );
      expect( 'chemicals should be at 100%', $celestialBodyEntity->getSpecs()->getResourceDensity()->getChemicals() )
        ->equals( 1.0 );
      expect( 'ice should be at 30%', $celestialBodyEntity->getSpecs()->getResourceDensity()->getIce() )
        ->equals( 0.3 );
      expect( 'iron should be at 100%', $celestialBodyEntity->getSpecs()->getResourceDensity()->getIron() )
        ->equals( 1.0 );
    });
  }
}
