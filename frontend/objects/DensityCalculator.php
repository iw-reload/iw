<?php

namespace frontend\objects;

use yii\base\Object;

/**
 * Calculates resource densities of a celestial body.
 * 
 * For some resources, the density can change over time. For example, the Ice
 * density of every planet will decrease over time, depending on how much Ice
 * you produce.
 * 
 * But also iron and chemicals densities can decrease, if a robo miner is used
 * to produce these resources from a celestial body.
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class DensityCalculator extends Object
{
  private $minimalDensity     = 0.0;
  private $currentDensity     = 1.0;
  private $currentProduction  = 0.0;
  private $densityReduction   = 0.005;
  private $perProducedUnits   = 500;
  
  public function setMinimalDensity($minimalDensity)
  {
    $this->minimalDensity = $minimalDensity;
  }

  public function setCurrentDensity($currentDensity)
  {
    $this->currentDensity = $currentDensity;
  }

  public function setCurrentProduction($currentProduction)
  {
    $this->currentProduction = $currentProduction;
  }
    
  /**
   * Calculates the new density in 24 hours, based on the current production.
   * The density in 24h can't drop below the configured minimal density.
   * @return double
   */
  public function calculateDensityIn24h()
  {
    $multiplier = $this->densityReduction / $this->perProducedUnits;
    $calculatedDensityIn24h = $this->currentDensity - ($this->currentProduction * $multiplier);
    return max( $this->minimalDensity, $calculatedDensityIn24h );
  }
  
  /**
   * Calculates the density reduction in 24 hours.
   * @return double
   */
  public function calculateDensityReductionIn24h()
  {
    $densityIn24Hours = $this->calculateDensityIn24h();
    return $this->currentDensity - $densityIn24Hours;
  }
  
  /**
   * Calculate the density after a given number of seconds, considering the
   * current production and the production change as a result of the density
   * change.
   */
  public function calculateDensity( $seconds )
  {
    $secondsPerDay  = 24 * 60 * 60;
    $calc = clone $this;
    
    // calculate in steps of whole days as long as possible
    while ($seconds > $secondsPerDay)
    {
      $densityIn24h = $calc->calculateDensityIn24h();
      $productionIn24h = $calc->currentProduction / $calc->currentDensity * $densityIn24h;
      
      $calc->currentDensity = $densityIn24h;
      $calc->currentProduction = $productionIn24h;
      
      $seconds -= $secondsPerDay;
    }
    
    // interpolate remaining fraction of a day
    $densityIn24h = $calc->calculateDensityIn24h();
    
    // Use linear interpolation. Easiest to implement.
    // TODO implement more realistic interpolation based on available data.
    // TODO also take into account that we might reach the minimum density
    //      earlier than in 24 hours
    $densityReductionIn24h = $calc->calculateDensityReductionIn24h();
    $densityReductionInXSeconds = $densityReductionIn24h / $secondsPerDay * $seconds;
    
    return $calc->currentDensity - $densityReductionInXSeconds;
  }
}
