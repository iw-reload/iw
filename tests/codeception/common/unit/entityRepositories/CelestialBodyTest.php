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
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  private $em = null;
  /**
   * @var CelestialBodyRepository
   */
  private $repo = null;
  
  public function setUp()
  {
    parent::setUp();
    
    /* @var $doctrine \common\components\doctrine\DoctrineComponent */
    $doctrine = Yii::$app->get('doctrine');
    $em = $doctrine->getEntityManager();

    $this->em = $em;
    $this->repo = $this->em->getRepository('common\entities\CelestialBody');
  }

  protected function tearDown()
  {
    parent::tearDown();
  }

  public function testGetFreeCelestialBody()
  {
    $repo = $this->repo;
    
    // TODO: ensure there is a free celestial body
//    $this->specify('CelestialBodyRepository should be able to find free Celestial Bodies', function () use ($repo) {
//      $celestialBody = $repo->getFreeCelestialBody();
//      expect('celestial body should be found', $celestialBody instanceof CelestialBodyEntity)->equals( true );
//      expect('the celestial body should not have an owner', $celestialBody->hasOutpost())->equals( false );
//    });
    
    // TODO: ensure there are no more free celestial bodies
    $this->specify('CelestialBodyRepository should not be able to find free Celestial Bodies', function () use ($repo) {
      $celestialBody = $repo->getFreeCelestialBody();
      expect('celestial body should not be found', $celestialBody)->null();
    });
  }

}
