<?php

namespace frontend\widgets\resource;

use frontend\objects\DensityCalculator;
use frontend\objects\PopulationCalculator;
use frontend\objects\ProductionCalculator;
use frontend\objects\Resources;
use frontend\objects\ShelterCapacityCalculator;
use frontend\objects\StorageCalculator;
use yii\base\Widget;
use Yii;

/**
 * Renders the resource overview for a given base.
 *
 * @author ben
 */
class ResourceWidget extends Widget
{
  /**
   * @var \common\models\Base
   */
  public $base = null;
  
  public function run()
  {
    /* @var $productionCalculator ProductionCalculator */
    $productionCalculator = Yii::createObject( ProductionCalculator::className() );
    /* @var $production \frontend\objects\Resources */
    $production = $productionCalculator->calculateProduction( $this->base );
    
    $storageCalculator = Yii::createObject( StorageCalculator::className() );
    $storage = $storageCalculator->calculateStorage( $this->base );
    
    /* @var $populationCalculator PopulationCalculator */
    $populationCalculator = Yii::createObject( PopulationCalculator::className() );
    $population = $populationCalculator->run( $this->base );
    
    /* @var $densityCalculator DensityCalculator */
    $densityCalculator = Yii::createObject( DensityCalculator::className() );
    $densityCalculator->setCurrentDensity( $this->base->celestialBody->density_ice );
    $densityCalculator->setCurrentProduction( $production->ice );
    // TODO minimum density depends on celestialBody type
    $densityCalculator->setMinimalDensity( 0.05 );
    
    /* @var $shelterCapacityCalculator ShelterCapacityCalculator */
    $shelterCapacityCalculator = Yii::createObject( ShelterCapacityCalculator::className() );
    $shelter = $shelterCapacityCalculator->calculateBaseCapacity( $this->base );
    
    $stock = new Resources();
    $stock->chemicals = $this->base->stored_chemicals;
    // TODO credits are per account, not per base
    $stock->credits = $this->base->stored_credits;
    $stock->energy = $this->base->stored_energy;
    $stock->ice = $this->base->stored_ice;
    $stock->iron = $this->base->stored_iron;
    $stock->population = $this->base->stored_people;
    $stock->steel = $this->base->stored_steel;
    $stock->vv4a = $this->base->stored_vv4a;
    $stock->water = $this->base->stored_water;
    
    return $this->render('resourceWidget',[
      'base' => $this->base,
      'production' => $production,
      'stock' => $stock,
      'storage' => $storage,
      'shelter' => $shelter,
      'population' => $population,
      'iceDensityChange' => $densityCalculator->calculateDensityReductionIn24h(),
    ]);
  }
}
