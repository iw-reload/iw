<?php

namespace tests\codeception\common\unit;

/**
 * @inheritdoc
 */
class TestCase extends \yii\codeception\TestCase
{
  public $appConfig = '@tests/codeception/config/common/unit.php';
  
  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $em = null;
  
  public function setUp()
  {
    parent::setUp();
    
    /* @var $doctrine \common\components\doctrine\DoctrineComponent */
    $doctrine = \Yii::$app->get('doctrine');
    $this->em = $doctrine->getEntityManager();
  }
  
  protected function clearDb()
  {
    // We can't rely on FK delete cascade as long as we're using sqlite for
    // tests. Doctrine doesn't enable FK support for it.
    $this->em->createQuery('delete from common\entities\Outpost')->execute();
    $this->em->createQuery('delete from common\entities\CelestialBody')->execute();
    $this->em->createQuery('delete from common\entities\System')->execute();
    $this->em->createQuery('delete from common\entities\Galaxy')->execute();
    $this->em->createQuery('delete from common\entities\CelestialBodyTypeSpecs')->execute();
    $this->em->createQuery('delete from common\entities\Universe')->execute();
    $this->em->createQuery('delete from common\entities\User')->execute();
    $this->em->flush();
  }
}
