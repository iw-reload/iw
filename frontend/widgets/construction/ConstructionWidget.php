<?php

namespace frontend\widgets\construction;

use frontend\interfaces\BuildingFinderInterface;
use frontend\objects\ConstructionInfo;
use yii\base\Widget;

/**
 * Renders the buildings construction overview for a given base.
 *
 * @author ben
 */
class ConstructionWidget extends Widget
{
  /**
   * @var BuildingFinderInterface
   */
  private $buildingFinder;
  
  /**
   * @var \frontend\interfaces\ConstructionTaskProvider
   */
  public $tasksProvider = null;
  public $displayImages = false;
  public $displayBase = false;

  
  public function __construct( BuildingFinderInterface $buildingFinder, $config = [] )
  {
    $this->buildingFinder = $buildingFinder;
    parent::__construct($config);
  }
  
  
  public function run()
  {
    $aConstructionTasks = $this->tasksProvider->getConstructionTasks();
    $aConstructionInfo = $this->convertTasksToInfo( $aConstructionTasks );
    
    return $this->render('constructionWidget',[
      'aConstructionInfo' => $aConstructionInfo,
    ]);
  }
  
  
  private function convertTasksToInfo( $aConstructionTasks )
  {
    /* @var $user \common\models\User */
    $user = \Yii::$app->user->identity;
    $aConstructionInfo = [];
    
    /* @var $taskModel \common\models\Task */
    foreach ($aConstructionTasks as $taskModel)
    {
      $baseId = $taskModel->data['baseId'];
      $base = $user->getBase( $baseId );
      
      $buildingId = $taskModel->data['buildingId'];
      $building = $this->buildingFinder->getById( $buildingId );
      
      // TODO get time from time component
      $now = new \DateTime();
      /* @var $remaining \DateInterval */
      $remaining = $taskModel->finished->diff( $now );
      
      $info = new ConstructionInfo();
      $info->base = $base->getLabel();
      $info->building = $building->getName();
      // TODO introduce constant for DateInterval format
      $info->countdown = $remaining->format('%H:%I:%S');
      $info->finished = $taskModel->finished;
      
      $aConstructionInfo[] = $info;
    }
    
    return $aConstructionInfo;
  }
}
