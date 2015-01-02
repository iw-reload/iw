<?php

namespace common\components\doctrine\console;

use common\components\doctrine\DoctrineComponent;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use yii\di\Instance;

/**
 * Description of DoctrineCommand
 *
 * @author ben
 */
class DoctrineController extends \yii\console\Controller
{
  /**
    * @var string the application component ID of the doctrine component.
    */
  public $doctrine = 'doctrine';
  
  public function runAction($id, $params = []) {
    // Skip \yii\console\Controller::runAction impl.
    // Don't care about options and arguments. Just pass the call through
    // to Doctrine's ConsoleRunner and let it handle everything.
    return \yii\base\Controller::runAction( $id, $params );
  }

  public function actionIndex()
  {
    unset( $_SERVER['argv'][1] );
    
    $option = '--' . \yii\console\Application::OPTION_APPCONFIG . '=';
    foreach ($_SERVER['argv'] as $key => $param)
    {
      if (strpos($param,$option) === 0)
      {
        $keyToUnset = $key;
        break;
      }
    }
    
    if (isset($keyToUnset))
    {
      unset( $_SERVER['argv'][$keyToUnset] );
      unset( $keyToUnset );
    }
    
    // Currently, symfony application uses a deprecated class (DialogHelper).
    // Don't throw an exception.
    $currentLevel = error_reporting();
    error_reporting( $currentLevel ^ E_USER_DEPRECATED );
    
    $doctrine = $this->getDoctrineComponent();
    $entityManager = $doctrine->getEntityManager();
    $helperSet = ConsoleRunner::createHelperSet($entityManager);
    $result = ConsoleRunner::run( $helperSet );
    return $result;
  }
  
  /**
   * @return DoctrineComponent
   */
  private function getDoctrineComponent() {
    return Instance::ensure( $this->doctrine, DoctrineComponent::className() );
  }
  
}
