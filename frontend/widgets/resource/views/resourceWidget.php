<?php

/* @var $population \frontend\objects\Population */
/* @var $production \frontend\objects\Resources */
/* @var $shelter \frontend\objects\Resources */
/* @var $stock \frontend\objects\Resources */
/* @var $storage \frontend\objects\Resources */
/* @var $iceDensityChange double */

use yii\widgets\Menu;

$f = Yii::$app->formatter;

$lblIron = Yii::t('app','iron');
$ironProduction = $f->asDecimal( $production->iron );
$storedIron = $f->asInteger( $stock->iron );

$lblSteel = Yii::t('app','steel');
$steelProduction = $f->asDecimal( $production->steel );
$storedSteel = $f->asInteger( $stock->steel );

$lblChemicals = Yii::t('app','chemicals');
$chemicalsProduction = $f->asDecimal( $production->chemicals );
$storedChemicals = $f->asInteger( $stock->chemicals );

$lblVv4a = Yii::t('app','vv4a');
$vv4aProduction = $f->asDecimal( $production->vv4a );
$storedVv4a = $f->asInteger( $stock->vv4a );

$lblPopulation = Yii::t('app','population');
// TODO $currentPopulation can not be larger than $populationLimit
//      However, this should be handled when updating the $base
$lblUnemployedPopulation = Yii::t('app','unemployed');
$lblCurrentPopulation = Yii::t('app','current');
$lblPopulationSpace = Yii::t('app','limit');
$populationGrowth = $f->asDecimal( $population->growth );
$unemployedPopulation = $f->asInteger( $population->current - $population->employed );
$currentPopulation = $f->asInteger( $population->current );
$populationSpace = $f->asInteger( $population->space );

$lblSatisfaction = Yii::t('app','satisfaction');
$currentSatisfaction = 'TODO';
$satisfactionGrowth = 'TODO';

$lblIce = Yii::t('app','ice');
$iceProduction = $f->asDecimal( $production->ice );
$storedIce = $f->asInteger( $stock->ice );
$iceDensityDecline = $f->asDecimal( $iceDensityChange );

$lblWater = Yii::t('app','water');
$waterProduction = $f->asDecimal( $production->water );
$storedWater = $f->asInteger( $stock->water );

$lblEnergy = Yii::t('app','energy');
$energyProduction = $f->asDecimal( $production->energy );
$storedEnergy = $f->asInteger( $stock->energy );

$lblCredits = Yii::t('app','credits');
$creditsProduction = 'TODO';
$currentCredits = $f->asInteger( $stock->credits );

$lblResearchPoints = Yii::t('app','research points');
$researchPointsProduction = 'TODO';
$remainingResearchPoints = 'TODO';

$lblStorageCapacity = Yii::t('app','storage capacity');
$storageCapacityIceAndWater = $f->asInteger( $storage->ice );
$storageCapacityChemicals = $f->asInteger( $storage->chemicals );
$storageCapacityEnergy = $f->asInteger( $storage->energy );

$lblShelterCapacity = Yii::t('app','shelter capacity');
$shelterCapacityIron = $f->asInteger( $shelter->iron );
$shelterCapacitySteel = $f->asInteger( $shelter->steel );
$shelterCapacityChemicals = $f->asInteger( $shelter->chemicals );
$shelterCapacityVv4a = $f->asInteger( $shelter->vv4a );
$shelterCapacityPopulation = $f->asInteger( $shelter->population );
$shelterCapacityIceAndWater = $f->asInteger( $shelter->ice );
$shelterCapacityEnergy = $f->asInteger( $shelter->energy );

$lblIceAndWater = Yii::t('app','ice and water');

$notepad = Yii::t('app','notepad');
$resourceConversion = Yii::t('app','resource conversion');
?>

<fieldset class="resource-widget">
  <legend>Resources</legend>
  <div class="row resources">
    
    <div class="col-sm-6">
      <dl>
        <dt class="iron"><?= $lblIron ?> (<?= $ironProduction ?>)</dt>
        <dd><?= $storedIron ?></dd>
        <dt class="steel"><?= $lblSteel ?> (<?= $steelProduction ?>)</dt>
        <dd><?= $storedSteel ?></dd>
        <dt class="chemicals"><?= $lblChemicals ?> (<?= $chemicalsProduction ?>)</dt>
        <dd><?= $storedChemicals ?></dd>
        <dt class="vv4a"><?= $lblVv4a ?> (<?= $vv4aProduction ?>)</dt>
        <dd><?= $storedVv4a ?></dd>
        <dt class="population"><?= $lblPopulation ?> (<?= $populationGrowth ?>)</dt>
        <dd>
          <?= $unemployedPopulation ?> / <?= $currentPopulation ?> / <?= $populationSpace ?><br />
          (<?= $lblUnemployedPopulation ?> / <?= $lblCurrentPopulation ?> / <?= $lblPopulationSpace ?>)
        </dd>
        <dt class="satisfaction"><?= $lblSatisfaction ?> (<?= $satisfactionGrowth ?>)</dt>
        <dd><?= $currentSatisfaction ?></dd>
      </dl>
    </div>
    
    <div class="col-sm-6">
      <dl>
        <dt class="ice">
          <?= $lblIce ?> (<?= $iceProduction ?>)<br />
          (Abbau pro Tag <?= $iceDensityDecline ?>%)
        </dt>
        <dd><?= $storedIce ?></dd>
        <dt class="water"><?= $lblWater ?> (<?= $waterProduction ?>)</dt>
        <dd><?= $storedWater ?></dd>
        <dt class="energy"><?= $lblEnergy ?> (<?= $energyProduction ?>)</dt>
        <dd><?= $storedEnergy ?></dd>
        <dt>&nbsp;</dt>
        <dd>&nbsp;</dd>
        <dt class="credits"><?= $lblCredits ?> (<?= $creditsProduction ?>)</dt>
        <dd><?= $currentCredits ?></dd>
        <dt class="research"><?= $lblResearchPoints ?> (<?= $researchPointsProduction ?>)</dt>
        <dd><?= $remainingResearchPoints ?></dd>
      </dl>
    </div>
    
  </div>
  <div class="row storage">
    <div class="col-sm-12">
      <?= $lblStorageCapacity ?>
      <ul>
        <li class="ice-and-water" title="<?= $lblIceAndWater ?>"><?= $storageCapacityIceAndWater ?></li>
        <li class="chemicals" title="<?= $lblChemicals ?>"><?= $storageCapacityChemicals ?></li>
        <li class="energy" title="<?= $lblEnergy ?>"><?= $storageCapacityEnergy ?></li>
      </ul>
    </div>
  </div>
  <div class="row shelter">
    <div class="col-sm-12">
      <?= $lblShelterCapacity ?>
      <ul>
        <li class="iron" title="<?= $lblIron ?>"><?= $shelterCapacityIron ?></li>
        <li class="steel" title="<?= $lblSteel ?>"><?= $shelterCapacitySteel ?></li>
        <li class="vv4a" title="<?= $lblVv4a ?>"><?= $shelterCapacityVv4a ?></li>
        <li class="ice-and-water" title="<?= $lblIceAndWater ?>"><?= $shelterCapacityIceAndWater ?></li>
        <li class="chemicals" title="<?= $lblChemicals ?>"><?= $shelterCapacityChemicals ?></li>
        <li class="energy" title="<?= $lblEnergy ?>"><?= $shelterCapacityEnergy ?></li>
        <li class="population" title="<?= $lblPopulation ?>"><?= $shelterCapacityPopulation ?></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <?= Menu::widget([
        'items' => [
          ['label' => $notepad, 'url' => ['game/notepad'], 'options' => ['class' => 'disabled']],
          ['label' => $resourceConversion, 'url' => ['game/resource-conversion'], 'options' => ['class' => 'disabled']],
        ],
      ]); ?>
    </div>
  </div>
</fieldset>
