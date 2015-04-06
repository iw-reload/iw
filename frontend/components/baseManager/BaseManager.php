<?php

namespace frontend\components\baseManager;

use common\models\Base;
use frontend\interfaces\BaseFinderInterface;
use yii\base\Component;
use yii\base\Event;

/**
 * Loads bases by id, ensuring they won't be loaded twice.
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class BaseManager extends Component implements BaseFinderInterface
{
  private $bases = [];

  public function init()
  {
    parent::init();
    
    Event::on( Base::className(), Base::EVENT_AFTER_FIND, function ($event) {
      $this->addBase( $event->sender );
    });
  }
 
  public function getBaseById( $id )
  {
    if (!array_key_exists($id,$this->bases)) {
      $this->loadBase( $id );
    }
    
    return $this->bases[$id];
  }

  /**
   * Loads a base. The base will be added to the manager's loaded bases array
   * indirectly, because we attached to Base::EVENT_AFTER_FIND.
   * 
   * @param int $id
   */
  private function loadBase( $id )
  {
    Base::find()
      ->where(['id' => $id])
      ->one();
  }
  
  /**
   * Will be called whenever a Base is loaded and triggers its EVENT_AFTER_FIND.
   * 
   * @param Base $model
   * @throws \yii\base\NotSupportedException
   */
  private function addBase( Base $model )
  {
    $id = $model->id;
  
    if (array_key_exists($id,$this->bases)) {
      throw new \yii\base\NotSupportedException( "Base '$id' loaded twice." );
    }
    
    $this->bases[$id] = $model;
  }
}
