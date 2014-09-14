<?php

$f = Yii::$app->formatter;
$ctx = $this->context;

/* @var $aConstructionInfo \frontend\objects\ConstructionInfo[] */
/* @var $f yii\i18n\Formatter */
/* @var $this yii\web\View */
/* @var $ctx \frontend\widgets\construction\ConstructionWidget */

$lblConstruction = Yii::t('app','Construction');

$aColumns = [];

if ($ctx->displayBase) {
  $aColumns[] = 'base';
}

if ($ctx->displayImages) {
  // TODO implement
  // TODO images via css?
  // $aColumns[] = 'buildingImage';
}

$aColumns[] = 'building';
$aColumns[] = [
  'attribute' => 'finished',
  'format' => 'dateTime',
];

$aColumns[] = 'countdown';

?>

<fieldset class="construction-widget">
  <legend><?= $lblConstruction ?></legend>
  <?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
      'allModels' => $aConstructionInfo,
      'sort' => [
        'attributes' => ['base', 'building', 'finished', 'countdown'],
      ],
      'pagination' => [
        'pageSize' => 10,
      ],
    ]),
    //'filterModel' => $searchModel,
    'columns' => $aColumns,
    'summary' => '',
    'tableOptions' => ['class' => ''],
  ]); ?>
</fieldset>
