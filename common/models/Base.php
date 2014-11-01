<?php

namespace common\models;

use common\models\BuildingsOnBase;
use frontend\behaviors\UpdateStockBehavior;
use frontend\components\building\BuildingComponent;
use frontend\interfaces\ConstructionTaskProvider;
use frontend\models\Building;
use frontend\objects\Resources;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\di\Instance;

/**
 * This is the model class for table "base".
 *
 * @property string $user_id
 * @property string $id
 * @property string $name
 * @property string $stored_iron
 * @property string $stored_steel
 * @property string $stored_chemicals
 * @property string $stored_vv4a
 * @property string $stored_ice
 * @property string $stored_water
 * @property string $stored_energy
 * @property string $stored_people
 * @property string $stored_credits TODO credits are global, not per base
 * @property string $stored_last_update the value as stored in DB
 * @property \DateTime $dateTimeStoredLastUpdate the stored_last_update value as \DateTime instance
 * @property string $produced_steel
 * @property string $produced_vv4a
 * @property string $produced_water
 * @property integer $created_at
 * @property integer $updated_at
 * 
 * @property CelestialBody $celestialBody
 * @property User $user
 * @property BuildingsOnBase[] $buildingsOnBases
 * @property Task[] $tasks
 * @property Task[] $finishedTasks
 */
class Base extends \yii\db\ActiveRecord implements ConstructionTaskProvider
{
  /**
   * @var string the application component ID of the BuildingComponent
   */
  public $building = 'building';
  
  private $production = [];
  private $productionCapacity = [];
  
  /**
   * @var array buildingId => number of buildings on base
   */
  private $buildingCounters = null;
  
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return '{{%base}}';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [ [ 'id', 'user_id', 'name', 'stored_iron', 'stored_steel',
          'stored_chemicals', 'stored_vv4a', 'stored_ice', 'stored_water',
          'stored_energy', 'stored_people', 'stored_credits',
        ], 'required'],
      [ [ 'id', 'user_id', 'stored_iron', 'stored_steel', 'stored_chemicals',
          'stored_vv4a', 'stored_ice', 'stored_water', 'stored_energy',
          'stored_people', 'stored_credits', 'produced_steel', 'produced_vv4a',
          'produced_water',
        ], 'integer'],
      [['name'], 'string', 'max' => 255],
      ['stored_last_update', 'date', 'format' => 'php:'.\DateTime::RFC3339 ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'user_id' => 'User ID',
      'name' => 'Name',
      'stored_iron' => 'Stored Iron',
      'stored_steel' => 'Stored Steel',
      'stored_chemicals' => 'Stored Chemicals',
      'stored_vv4a' => 'Stored Vv4a',
      'stored_ice' => 'Stored Ice',
      'stored_water' => 'Stored Water',
      'stored_energy' => 'Stored Energy',
      'stored_people' => 'Stored People',
      'stored_credits' => 'Stored Credits',
      'stored_last_update' => 'Storage Last Update',
      'produced_steel' => 'Produced Steel', 
      'produced_vv4a' => 'Produced VV4A', 
      'produced_water' => 'Produced Water', 
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  /** 
   * @inheritdoc 
   */ 
  public function behaviors() 
  { 
    return [ 
      TimestampBehavior::className(),
      UpdateStockBehavior::className(),
    ]; 
  } 
  
  /**
   * @return \DateTime
   */
  public function getDateTimeStoredLastUpdate() {
    return new \DateTime( $this->stored_last_update );
  }
  
  public function setDateTimeStoredLastUpdate( \DateTime $value ) {
    $this->stored_last_update = $value->format( \DateTime::RFC3339 );
  }  
  
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCelestialBody()
  {
    return $this->hasOne(CelestialBody::className(), ['id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getBuildingsOnBases()
  {
    return $this->hasMany(BuildingsOnBase::className(), ['base_id' => 'id']);
  }

  /**
   * Returns a label for this base. Can be used as text for links.
   */
  public function getLabel() {
    return "[{$this->celestialBody->label}] {$this->name}";
  }
  
  /**
   * @return Task[]
   */
  public function getTasks() {
    return $this->filterTasks( $this->user->tasks );
  }

  /**
   * @return Task[]
   */
  public function getFinishedTasks() {
    return $this->filterTasks( $this->user->finishedTasks );
  }
  
  /**
   * @return Task[]
   */
  public function getConstructionTasks() {
    return $this->filterTasks( $this->user->getConstructionTasks() );
  }
  
  /**
   * Filters the give array of tasks and only returns tasks associated with
   * this base.
   * @param Task[] $tasks
   */
  private function filterTasks( $tasks )
  {
    $result = [];
    
    foreach ($tasks as $task)
    {
      if (array_key_exists('baseId',$task->data)
        && $task->data['baseId'] == $this->id)
      {
        $result[] = $task;
      }
    }
    
    return $result;
  }

  /**
   * @return Resources
   */
  public function getStock()
  {
    $stock = new Resources();
    $stock->chemicals = $this->stored_chemicals;
    $stock->credits = $this->stored_credits;
    $stock->energy = $this->stored_energy;
    $stock->ice = $this->stored_ice;
    $stock->iron = $this->stored_iron;
    $stock->population = $this->stored_people;
    $stock->steel = $this->stored_steel;
    $stock->vv4a = $this->stored_vv4a;
    $stock->water = $this->stored_water;
    
    return $stock;
  }
  
  /**
   * @param Resources $stock
   */
  public function setStock( $stock )
  {
    $this->stored_chemicals = $stock->chemicals;
    $this->stored_credits = $stock->credits;
    $this->stored_energy = $stock->energy;
    $this->stored_ice = $stock->ice;
    $this->stored_iron = $stock->iron;
    $this->stored_people = $stock->population;
    $this->stored_steel = $stock->steel;
    $this->stored_vv4a = $stock->vv4a;
    $this->stored_water = $stock->water;
  }
  
  /**
   * Remove resources needed to construct a building from this base.
   * 
   * @param Building $building
   * @param float $factor
   */
  public function payFor( $building, $factor=1.0 )
  {
    // Make the factor negative. We want to substract the costs.
    $factor *= -1;
    
    $this->updateCounters([
      'stored_chemicals'  => $factor * $building->getCostChemicals(),
      'stored_credits'    => $factor * $building->getCostCredits(),
      'stored_energy'     => $factor * $building->getCostEnergy(),
      'stored_ice'        => $factor * $building->getCostIce(),
      'stored_iron'       => $factor * $building->getCostIron(),
      'stored_people'     => $factor * $building->getCostPeople(),
      'stored_steel'      => $factor * $building->getCostSteel(),
      'stored_vv4a'       => $factor * $building->getCostVv4a(),
      'stored_water'      => $factor * $building->getCostWater(),
    ]);
  }
  
  /**
   * Returns the amount of iron the base is capable of producing. This does not
   * mean the base is producing that amount of iron right now.
   * @todo iron production depends on iron density
   * @return double
   */
  public function getIronProductionCapacity()
  {
    return $this->getProductionCapacity( 'iron' );
  }

  
  /**
   * Returns the amount of steel the base is capable of producing. This does not
   * mean the base is producing that amount of steel right now.
   * @return double
   */
  public function getSteelProductionCapacity() {
    return $this->getProductionCapacity( 'steel' );
  }
  
  
  /**
   * Returns the amount of chemicals the base is capable of producing. This does not
   * mean the base is producing that amount of chemicals right now.
   * @todo chemicals production depends on chemicals density
   * @return double
   */
  public function getChemicalsProductionCapacity() {
    return $this->getProductionCapacity( 'chemicals' );
  }
  
  
  /**
   * Returns the amount of vv4a the base is capable of producing. This does not
   * mean the base is producing that amount of vv4a right now.
   * @return double
   */
  public function getVv4aProductionCapacity() {
    return $this->getProductionCapacity( 'vv4a' );
  }
  
  
  /**
   * Returns the theoretical growth of the planets population. This does not
   * mean the population is growing at that rate right now.
   * 
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
   * > Bevwachstummodifikator wird durch bestimmte Forschungen verursacht und
   * > in der Produktionsübersicht angezeigt.
   * 
   * @todo Refactor. I want such stuff outside this class. Something that is
   * not hard coded and can be configured, visualized, ...
   * 
   * @todo Implement $populationGrowthModifier
   * 
   * @return double
   */
  public function getPopulationGrowthCapacity()
  {
    $mutiplier = 1;
    
    if ($this->stored_people < 1000) {
      $mutiplier = 20;
    } elseif ($this->stored_people < 10000) {
      $mutiplier = 50;
    } elseif ($this->stored_people < 100000) {
      $mutiplier = 100;
    } else {
      $mutiplier = 200;
    }
    
    $livingConditions = $this->celestialBody->living_conditions;
    $gravity =  $this->celestialBody->gravity;
    $populationGrowthModifier = 0.0;
    
    return ($this->stored_people / $mutiplier * $livingConditions / max(1.0,$gravity))
      + $populationGrowthModifier;
  }
  
  
  /**
   * Returns the theoretical growth of the populations satisfaction. This does
   * not mean the satisfaction is growing at that rate right now.
   * @return double
   */
  public function getSatisfactionGrowthCapacity()
  {
    $taxes = 0.0;
    
    // satisfaction goes down in steps of 500 people at a time
    $populationPenaltyFactor = ceil( $this->stored_people / 500 );
    // people prefere low gravity
    $gravityFactor = $this->celestialBody->gravity;
    // together, this makes our penalty
    // == 0 in this case, because taxes == 0
    $penalty = $taxes * $populationPenaltyFactor * $gravityFactor;
        
    $balanceSatisfaction = $this->getProductionCapacity( 'satisfaction' );
    $balanceSatisfaction -= $penalty;
    
    return $balanceSatisfaction;
  }
  
  /**
   * Returns the amount of ice the base is capable of producing. This does not
   * mean the base is producing that amount of ice right now.
   * @todo ice production depends on ice density
   * @return double
   */
  public function getIceProductionCapacity() {
    return $this->getProductionCapacity( 'ice' );
  }
  
  
  /**
   * Returns the amount of water the base is capable of producing. This does not
   * mean the base is producing that amount of water right now.
   * @return double
   */
  public function getWaterProductionCapacity() {
    return $this->getProductionCapacity( 'water' );
  }
  
  
  /**
   * Returns the amount of energy the base is capable of producing. This does not
   * mean the base is producing that amount of energy right now.
   * @return double
   */
  public function getEnergyProductionCapacity() {
    return $this->getProductionCapacity( 'energy' );
  }
  
  
  /**
   * @see "http://www.icewars.de/tools/de/steuern/index.html"
   * @return double
   */
  public function getCreditsProductionCapacity()
  {
    $taxes = 1.0; // 100%
    
    // TODO: satisfaction does not play a role while calculating capacity
    // satisfaction goes down in steps of 500 people at a time
    $populationPenaltyFactor = ceil( $this->stored_people / 500 );
    // people prefere low gravity
    $gravityFactor = $this->celestialBody->gravity;
    // together, this makes our penalty
    $penalty = $taxes * $populationPenaltyFactor * $gravityFactor;
    
    // income
    $incomePerEmployee = 0.05;
    $unemploymentPay = 0.02;

    $buildingsComponent = Instance::ensure( $this->building, BuildingComponent::className() );
    $buildingsTaxMod = 1.0;

    foreach ($this->getBuildingCounters() as $buildingId => $buildingCounter)
    {
      /* @var $building Building */
      $building = $buildingsComponent->getById( $buildingId );
      $buildingsTaxMod *= ($buildingCounter * $building->balance_credits);
    }
    
    // TODO some planetary specialities can influence the tax mod
    // - radioactivity: $planetTaxMod *= 0.5;
    $planetTaxMod = 1.0;
    
    $taxMod = $buildingsTaxMod * $planetTaxMod;
    return $this->stored_people * $incomePerEmployee * $taxes * $taxMod;
  }
  
  
  private function getProductionCapacity( $resourceName, $buildingProperty='' )
  {
    if ($buildingProperty === '') {
      $buildingProperty = 'balance_' . $resourceName;
    }
    
    if (!array_key_exists($resourceName,$this->productionCapacity))
    {
      $buildingsComponent = Instance::ensure( $this->building, BuildingComponent::className() );
      $capacity = 0;
      
      foreach ($this->getBuildingCounters() as $buildingId => $buildingCounter)
      {
        $building = $buildingsComponent->getById( $buildingId );
        $capacity += ($buildingCounter * $building->$buildingProperty);
      }
      
      $this->productionCapacity[$resourceName] = $capacity;
    }
    
    
    return $this->productionCapacity[$resourceName];
  }
  
  
  public function getIronProduction()
  {
    if (!array_key_exists('iron',$this->production))
    {
      // TODO: only if there is energy left.
      //       How to handle production when we're out of energy?
      
      $production = $this->getIronProductionCapacity() - (2 * $this->getSteelProduction());
      
      $this->production['iron'] = $production;
    }
    
    return $this->production['iron'];
  }

  
  public function getSteelProduction()
  {
    if (!array_key_exists('steel',$this->production))
    {
      $production = 0;
      
      // TODO: only if there is energy left.
      //       How to handle conversion when we're out of energy?
      // TODO: only if there is iron left.
      //       How to handle conversion when we're out of iron?
      if ($this->produced_steel === null)
      {
        $production = $this->getSteelProductionCapacity();
      }
      else
      {
        $production = $this->produced_steel - (2 * $this->getVv4aProduction());
      }
      
      $this->production['steel'] = $production;
    }
    
    return $this->production['steel'];
  }

  
  public function getChemicalsProduction()
  {
    $production = $this->getProduction();
    return $production['chemicals'];
  }

  
  public function getVv4aProduction()
  {
    $production = $this->getProduction();
    return $production['vv4a'];
  }

  public function getIceProduction()
  {
    $production = $this->getProduction();
    return $production['ice'];
  }

  public function getWaterProduction()
  {
    $production = $this->getProduction();
    return $production['water'];
  }

  public function getEnergyProduction()
  {
    $production = $this->getProduction();
    return $production['energy'];
  }

  public function getPeopleProduction()
  {
    $production = $this->getProduction();
    return $production['people'];
  }

  public function getCreditsProduction()
  {
    $production = $this->getProduction();
    return $production['credits'];
  }

  public function getSatisfactionProduction()
  {
    $production = $this->getProduction();
    return $production['satisfaction'];
  }  
  
  private function ensureBuildingCountersLoaded()
  {
    if (is_array($this->buildingCounters)) {
      return;
    } else {
      $this->buildingCounters = [];
    }

    $buildingsOnBase = BuildingsOnBase::find()
      ->where(['base_id' => $this->id])
      ->asArray()
      ->all();

    foreach ($buildingsOnBase as $row) {
      $this->buildingCounters[$row['building_id']] = $row['buildings_count'];
    }
  }
  
  /**
   * @return array buildingId => buildingCounter
   */
  public function getBuildingCounters()
  {
    $this->ensureBuildingCountersLoaded();
    return $this->buildingCounters;
  }
  
  public function increaseBuildingCounter( $buildingId, $inc=1 )
  {
    $this->ensureBuildingCountersLoaded();
    
    $buildingsOnBase = BuildingsOnBase::findOne([
      'base_id' => $this->id,
      'building_id' => $buildingId,
    ]);
    
    if ($buildingsOnBase instanceof BuildingsOnBase) {
      $buildingsOnBase->updateCounters(['buildings_count' => $inc]);
    } else {
      $buildingsOnBase = new BuildingsOnBase();
      $buildingsOnBase->base_id = $this->id;
      $buildingsOnBase->buildings_count = $inc;
      $buildingsOnBase->building_id = $buildingId;
      $buildingsOnBase->save();
    }
    
    if (array_key_exists($buildingId,$this->buildingCounters)) {
      $this->buildingCounters[$buildingId] += $inc;
    } else {
      $this->buildingCounters[$buildingId] = $inc;
    }
  }
}
