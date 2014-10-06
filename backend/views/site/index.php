<?php

use yii\helpers\Url;

/* @var $this yii\web\View */


$this->title = 'My Yii Application';
?>
<div class="site-index">
  <div class="body-content">
    <div class="row">
      
      <div class="col-lg-4">
        <h2>Buildings</h2>
        <p>Administer buildings.</p>
        <p><a class="btn btn-default" href="<?= Url::toRoute('building/index') ?>">Buildings &raquo;</a></p>
      </div>
      
      <div class="col-lg-4">
        <h2>Celestial Bodies</h2>
        <p>Administer celestial bodies.</p>
        <p><a class="btn btn-default" href="<?= Url::toRoute('celestial-body/index') ?>">Celestial Bodies &raquo;</a></p>
      </div>
      
      <div class="col-lg-4">
          <h2>Users</h2>
          <p>Administer registered users.</p>
          <p><a class="btn btn-default" href="<?= Url::toRoute('user/index') ?>">Registered Users &raquo;</a></p>
      </div>
      
    </div>
  </div>
</div>
