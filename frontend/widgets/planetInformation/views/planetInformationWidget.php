<?php

use yii\helpers\Url;
use \Yii;

$f = Yii::$app->formatter;
$ctx = $this->context;

/* @var $base \common\models\Base */
/* @var $ctx \frontend\widgets\construction\PlanetInformationWidget */
/* @var $f yii\i18n\Formatter */
/* @var $this yii\web\View */

$lblPlanetInformation = Yii::t('app','Planet Information');

$lblColony = Yii::t('app','Colony {label}', [
  'label' => $base->getLabel(),
]);

$lblLivingConditions = Yii::t('app','Living conditions');
$livingConditions = $f->asDecimal( $base->celestialBody->living_conditions );

$lblFleetScannerRange = Yii::t('app','Fleet Scanner range');
$fleetScannerRange = 'TODO';

$lblColonizationStatus = Yii::t('app','Current / maximum number of colonies');
$colonizationStatus = 'TODO';

$lblServerTime = Yii::t('app','Server time');
$serverTime = $f->asDatetime( new \DateTime() );

$lblResearchStatus = Yii::t('app','Research status');
$research = 'TODO';
$researchEnd = 'TODO';
?>

<fieldset class="planet-information-widget">
  <legend><?= $lblPlanetInformation ?></legend>
  
  <div class="row">
    
    <div class="col-sm-6">
      <h4><?= $lblColony ?></h4>
      <img src="<?= Url::to('@web/images/whole-earth.jpg') ?>" style="width: 100%;" />
      <dl>
        <dt><?= $lblLivingConditions ?></dt>
        <dd><?= $livingConditions ?></dd>
        
        <dt><?= $lblFleetScannerRange ?></dt>
        <dd><?= $fleetScannerRange ?></dd>
        
        <dt><?= $lblColonizationStatus ?></dt>
        <dd><?= $colonizationStatus ?></dd>
      </dl>
    </div>
    
    <div class="col-sm-6">
      <dl>
        <dt><?= $lblServerTime ?></dt>
        <dd><?= $serverTime ?></dd>
      </dl>
      <h4><?= $lblResearchStatus ?></h4>
      <dl>
        <dt><?= $research ?></dt>
        <dd><?= $researchEnd ?></dd>
      </dl>
    </div>
    
  </div>
  
</fieldset>
