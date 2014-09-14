<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Base */

$this->title = 'Create Base';
$this->params['breadcrumbs'][] = ['label' => 'Bases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
