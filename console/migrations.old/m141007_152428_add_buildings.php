<?php

use common\models\Building;
use common\objects\BuildingGroup;

use yii\db\Migration;

class m141007_152428_add_buildings extends Migration
{
  const COLONY_HEADQUARTERS = 'Colony Headquarters';
  const SMALL_IRON_MINE = 'Small Iron Mine';
  const SOLAR_PANELS = 'Solar Panels';
  const ICE_CRUSHER = 'Sirius Corporation\'s Designer Ice-Crusher';
  const ICE_MELTER = 'Immersion Heater MkIV';
  
  private function addColonyHeadquarters()
  {
    $model = new Building();
    $model->loadDefaultValues();
    $model->name = self::COLONY_HEADQUARTERS;
    $model->description = 'TODO: add description';
    $model->group = BuildingGroup::SPECIAL;
    $model->cost_time = '12:00:00';
    $model->balance_iron = 100;
    $model->balance_energy = 100;
    $model->balance_steel = 25;
    $model->storage_chemicals = 5000;
    $model->storage_energy = 5000;
    $model->storage_ice_and_water = 5000;
    $model->limit = 1;
    
    if (!$model->save()) {      
      throw new yii\base\Exception('Failed to save building: ' . print_r($model->firstErrors,true));
    }
  }

  private function addSmallIronMine()
  {
    $model = new Building();
    $model->loadDefaultValues();
    $model->name = self::SMALL_IRON_MINE;
    $model->description = 'TODO: add description';
    $model->group = BuildingGroup::ECONOMY;
    $model->cost_time = '03:00:00';
    $model->balance_iron = 20;
    $model->balance_energy = -1;
    
    if (!$model->save()) {      
      throw new yii\base\Exception('Failed to save building: ' . print_r($model->firstErrors,true));
    }
  }

  private function addSolarPanels()
  {
    $model = new Building();
    $model->loadDefaultValues();
    $model->name = self::SOLAR_PANELS;
    $model->description = 'TODO: add description';
    $model->group = BuildingGroup::ECONOMY;
    $model->cost_time = '01:00:00';
    $model->balance_energy = 20;
    
    if (!$model->save()) {      
      throw new yii\base\Exception('Failed to save building: ' . print_r($model->firstErrors,true));
    }
  }

  private function addIceCrusher()
  {
    $model = new Building();
    $model->loadDefaultValues();
    $model->name = self::ICE_CRUSHER;
    $model->description = 'TODO: add description';
    $model->group = BuildingGroup::ECONOMY;
    $model->cost_time = '04:00:00';
    $model->balance_ice = 70; // +70 ice/h @ 100% ice desity --> +21 ice/h @ 30% ice desity
    $model->balance_energy = -1;
    
    if (!$model->save()) {      
      throw new yii\base\Exception('Failed to save building: ' . print_r($model->firstErrors,true));
    }
  }

  private function addIceMelter()
  {
    $model = new Building();
    $model->loadDefaultValues();
    $model->name = self::ICE_MELTER;
    $model->description = 'TODO: add description';
    $model->group = BuildingGroup::ECONOMY;
    $model->cost_time = '04:00:00';
    $model->balance_water = 10;
    
    if (!$model->save()) {      
      throw new yii\base\Exception('Failed to save building: ' . print_r($model->firstErrors,true));
    }
  }

  public function up()
  {
    $this->addColonyHeadquarters();
    $this->addSmallIronMine();
    $this->addSolarPanels();
    $this->addIceCrusher();
    $this->addIceMelter();
  }

  public function down()
  {
    $buildingNames = [
      self::COLONY_HEADQUARTERS,
      self::SMALL_IRON_MINE,
      self::SOLAR_PANELS,
      self::ICE_CRUSHER,
      self::ICE_MELTER,
    ];
    
    Building::deleteAll(['name' => $buildingNames]);
    return true;
  }
}
