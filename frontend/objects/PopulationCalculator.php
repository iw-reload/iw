<?php

namespace frontend\objects;

use common\models\Base;
use frontend\interfaces\BuildingFinderInterface;
use frontend\objects\Population;
use yii\base\Object;

/**
 * @author ben
 */
class PopulationCalculator extends Object
{
  /**
   * @var BuildingFinderInterface
   */
  private $buildingFinder;
  /**
   * @var Base
   */
  private $base;
  /**
   * @var double
   */
  private $populationGrowth = null;
  /**
   * @var int
   */
  private $baseMaxPopulation = null;
  /**
   * @var int
   */
  private $residentialsSpace = null;
  /**
   * @var int
   */
  private $employedPopulation = null;
  

  public function __construct( BuildingFinderInterface $buildingFinder, $config = [] )
  {
    $this->buildingFinder = $buildingFinder;
    parent::__construct($config);
  }

  
  /**
   * @param \common\models\Base $base
   * @return \frontend\objects\Population
   */
  public function run( Base $base )
  {
    $this->clear();
    $this->base = $base;
    
    return new Population([
      'max' => $this->getBaseMaxPopulation(),
      'growth' => $this->getPopulationGrowth(),
      'space' => $this->getResidentialsSpace(),
      'employed' => $this->getEmployedPopulation(),
      'current' => $this->getCurrentPopulation(),
    ]);
  }

  
  private function clear()
  {
    $this->populationGrowth = null;
    $this->baseMaxPopulation = null;
    $this->residentialsSpace = null;
    $this->employedPopulation = null;
  }

  
  private function getPopulationGrowth()
  {
    if ($this->populationGrowth === null) {
      $this->calculatePopulationGrowth();
    }
    
    return $this->populationGrowth;
  }

  
  /**
   * Quote from http://doku.icewars.de/index.php/FAQ#Wie_schnell_w.C3.A4chst_meine_Bev.C3.B6lkerung.3F
   * 
   * > Wie schnell wächst meine Bevölkerung?
   * > 
   * > Das Wachstum der Bevölkerung ist abhängig von der schon vorhandenen.
   * > D.h. die Wachstumsrate wächst ebenfalls. Dabei gibt es aber an zwei
   * > Punkten einen deutlichen Einbruch. Bei Überschreiten der Schwelle von
   * > 1000 Einwohnern und bei 10.000 ist das Wachstum langsamer. Außerdem
   * > hängt das Wachstum noch von den Lebensbedingungen auf dem Planeten ab.
   * > Bessere Lebensbedingungen bedeuten mehr Wachstum, schlechtere weniger.
   * > Auf einem Planeten mit 0% Lebensbedingungen will sich die Bevölkerung
   * > überhaupt nicht mehr vermehren.
   * > 
   * > Als Formel:
   * > 
   * > Bis 1000 Einwohner
   * > (Vorhandene Bevölkerung / 20 * Lebensbedingungen / max(1, Gravitation))
   * > + Bevwachstummodifikator = Wachstumsrate 
   * > 
   * > Bis 10.000 Einwohner
   * > (Vorhandene Bevölkerung / 50 * Lebensbedingungen / max(1, Gravitation))
   * > + Bevwachstummodifikator = Wachstumsrate 
   * > 
   * > Bis 100.000 Einwohner
   * > (Vorhandene Bevölkerung / 100 * Lebensbedingungen / max(1, Gravitation))
   * > + Bevwachstummodifikator = Wachstumsrate 
   * > 
   * > Über 100.000 Einwohner 
   * > (Vorhandene Bevölkerung / 200 * Lebensbedingungen / max(1, Gravitation))
   * > + Bevwachstummodifikator = Wachstumsrate
   * > 
   * > Wenn die Formel plötzlich nicht mehr zu stimmen scheint und das
   * > Bevökerungswachstum statt zu steigen sinkt, dann liegt das fast immer an
   * > fehlendem Wohnraum. Baut schnell ein Haus und ihr werdet sehen, dass das 
   * > Wachstum wieder der Formel entspricht.
   * >
   * > Bevwachstummodifikator wird durch bestimmte Forschungen verursacht und
   * > in der Produktionsübersicht angezeigt.
   * 
   * @todo implement Bevwachstummodifikator
   */
  private function calculatePopulationGrowth()
  {
    // ensure dependencies are calculated
    $residentialsSpace = $this->getResidentialsSpace();
    $currentPopulation = $this->getCurrentPopulation();
    
    // population does not grow, if there is no room left
    if ($currentPopulation >= $residentialsSpace)
    {
      $this->populationGrowth = 0.0;
    }
    else
    {
      // TODO implement - people die if there's no water left and no water is produced.
      //      However, I'm not sure at what rate they die.
      // TODO implement. depends on certain researches.
      $populationGrowthModifier = 0.0;
      $livingConditions = $this->base->celestialBody->living_conditions;
      $gravity = $this->base->celestialBody->gravity;
      $mutiplier = $this->getPopulationGrowthModifier( $currentPopulation );

      $this->populationGrowth = ($currentPopulation / $mutiplier * $livingConditions / \max(1.0,$gravity))
        + $populationGrowthModifier;
    }
  }
 
  
  private function getPopulationGrowthModifier( $population )
  {
    if ($population < 1000) {
      $mutiplier = 20;
    } elseif ($population < 10000) {
      $mutiplier = 50;
    } elseif ($population < 100000) {
      $mutiplier = 100;
    } else {
      $mutiplier = 200;
    }
    
    return $mutiplier;
  }
  
  
  private function getBaseMaxPopulation()
  {
    if ($this->baseMaxPopulation === null) {
      $this->calculateBaseMaxPopulation();
    }
    
    return $this->baseMaxPopulation;
  }


  private function calculateBaseMaxPopulation()
  {
    // TODO implement get max population from celestialBody.
    // TODO take into account special buildings that might raise the celestialBody's
    //      max population (@see "http://doku.icewars.de/index.php/Asteroidenwohnplattform")
    $this->baseMaxPopulation = PHP_INT_MAX;
  }

  
  private function getCurrentPopulation()
  {
    return $this->base->stored_people;
  }
  
  
  private function getResidentialsSpace()
  {
    if ($this->residentialsSpace === null) {
      $this->calculateResidentialsSpace();
    }
    
    return $this->residentialsSpace;
  }
  
  
  private function calculateResidentialsSpace()
  {
    // TODO initial population space provided by the colony headquarters?
    $populationSpace = 150;

    $buildingCounters = $this->base->getBuildingCounters();
    $residentialBuildings = $this->buildingFinder->getByGroup('residential buildings');
    
    foreach ($residentialBuildings as $id => $building)
    {
      $counter = $buildingCounters[$id];
      $populationSpace += $counter * $building->getBalancePeople();
    }
    
    $this->residentialsSpace = $populationSpace;
  }
  
  
  private function getEmployedPopulation()
  {
    if ($this->employedPopulation === null) {
      $this->calculateEmployedPopulation();
    }
    
    return $this->employedPopulation;
  }
  
  /**
   * @TODO Currently, we can have more people employed than there are on the
   *       planet. Not sure how IW handled it, but maybe we can create strikes
   *       or drop production if there are less people than could be employed?
   */
  private function calculateEmployedPopulation()
  {
    $this->employedPopulation = 0;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      /* @var $building \frontend\models\Building */
      $building = $this->buildingFinder->getById( $id );
      
      if ($building->getBalancePeople() < 0) {
        $this->employedPopulation += $counter * \abs($building->getBalancePeople());
      }
    }
  }
  
}
