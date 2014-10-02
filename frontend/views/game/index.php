<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
$this->title = 'IceWars Reload - Home';

$base = $user->currentBase;

echo \frontend\widgets\resource\ResourceWidget::widget([
  'base' => $base,
]);

echo \frontend\widgets\construction\ConstructionWidget::widget([
  'displayBase' => true,
  'displayImages' => false,
  'tasksProvider' => $user,
]);

echo \frontend\widgets\planetInformation\PlanetInformationWidget::widget([
  'base' => $base,
]);

?>
