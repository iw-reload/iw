<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = 'IceWars Reload - Construct';

$lblConstruct = \Yii::t('app', 'Construction');
$lblHints = \Yii::t('app', 'Current hints');


$base = $user->currentBase;
$buildingCounters = $base->getBuildingCounters();

echo \frontend\widgets\resource\ResourceWidget::widget([
  'base' => $base,
]);

echo frontend\widgets\FlashWidget::widget();

echo \frontend\widgets\construction\ConstructionWidget::widget([
  'displayBase' => false,
  'displayImages' => true,
  'tasksProvider' => $base,
]);

?>
<fieldset class="construction">
  <legend><?= $lblConstruct ?></legend>

  <h4><?= $lblHints ?></h4>
  <ul class="hints">
    <!-- TODO dynamic hints -->
    <li>
      Es werden verschiedene Gebäude auf Userwunsch nicht angezeigt.
      <a href="#">Alle Gebäude anzeigen</a>
    </li>
    <li>
      Maximale Anzahl der Gebäude in der Bauschleife erreicht.
      Aktuelle Maximalanzahl: 2
    </li>
    <li>
      2 Gebäude im Bau. Kosten & Dauer für weitere Gebäude steigen um 20%
    </li>
  </ul>
  
  <?php
  /* @var $buildingComponent \frontend\components\building\BuildingComponent */
  $buildingComponent = \Yii::$app->building;
  $groups = $buildingComponent->getGroups();
  sort($groups);
  ?>
  
  <ul class="groups">
  <?php foreach ($groups as $group): ?>
    <?php
    $buildings = $buildingComponent->getByGroup($group);
    /* @var $building frontend\models\Building */
    // TODO sort buildings by name
    $lblGroup = \Yii::t('app', $group);
    ?>
    <li>
      <h4><?= $lblGroup ?></h4>
      <ul class="buildings">
      <?php foreach ($buildings as $building): ?>
        <?php
        $name   = \Yii::t( 'app', $building->getName() );
        $count  = array_key_exists( $building->getId(), $buildingCounters )
          ? $buildingCounters[$building->getId()]
          : 0;
        ?>
        <li>
          
          <a href="#"><?= $name ?></a>
          <span>(<?= $count ?> vorhanden)</span>
          <?= \frontend\widgets\CostWidget::widget([
            'resources' => $building->getCost(),
          ]) ?>
          <?= \frontend\widgets\DurationWidget::widget([
            'duration' => $building->getCostTime(),
          ]) ?>
          <?= \frontend\widgets\BalanceWidget::widget([
            'effects'   => $building->getEffects(),
            'resources' => $building->getBalance(),
          ]) ?>
          
          <form method="post">
            <input type="hidden" name="ConstructBuildingForm[baseId]" value="<?= $base->id ?>" />
            <input type="hidden" name="ConstructBuildingForm[buildingId]" value="<?= $building->getId() ?>" />
            <input type="submit" value="Bauen" />
          </form>
          
        </li>
      <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
  </ul>
  
</fieldset>