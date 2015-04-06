<?php

namespace common\behaviors;

use common\components\universe\UniverseComponent;
use common\models\Base;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * Cares for creating a base for newly created users.
 * 
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class CreateBaseBehavior extends Behavior
{
  /**
   * @var string the application component ID of the UniverseComponent
   */
  public $universe = 'universe';
  
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
    ];
  }
 
  public function afterInsert()
  {
    $user = $this->getUser();
    $universe = $this->getUniverseComponent();

    // 1) Find a celestial body for the player and reset its attributes
    //    to default values.
    $celestialBody = $universe->resetCelestialBody(
      $universe->chooseCelestialBodyForNewPlayer()
    );

    if (!$celestialBody->save()) {
      throw new Exception( 'Failed to save celestial body. Errors: ' . print_r($celestialBody->errors,true) );
    }

    $base = new Base();
    $base->id = $celestialBody->id;
    $base->user_id = $user->id;
    $base->name = Yii::t( 'app', "{username}'s colony", [
      'username' => $user->name,
    ]);
    // TODO make configurable
    $base->stored_iron        = 5000;
    $base->stored_steel       = 2000;
    $base->stored_chemicals   = 5000;
    $base->stored_vv4a        = 0;
    $base->stored_ice         = 5000;
    $base->stored_water       = 5000;
    $base->stored_energy      = 5000;
    $base->stored_people      = 500;
    $base->stored_credits     = 5000;
    //$base->stored_last_update = ;

    if (!$base->save()) {
      throw new \Exception( 'Failed to save base. Errors: ' . print_r($base->errors,true) );
    }
  }
  
  /**
   * @return \common\models\User
   */
  private function getUser()
  {
    return $this->owner;
  }
  
  /**
   * @return UniverseComponent
   */
  private function getUniverseComponent() {
    return Instance::ensure( $this->universe, UniverseComponent::className() );
  }
  
}
