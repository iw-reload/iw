<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'IceWars Reload';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>IceWars: Reloading...</h1>

        <p>
          <!--
            <img src="images/TODO.png" />
          -->
          
        <?= '' /* yii\authclient\widgets\AuthChoice::widget([
             'baseAuthUrl' => ['site/auth']
        ]) */ ?>          
          
        </p>
        
        <p class="lead"></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Erfinde</h2>

                <p>Du hast Ideen? Weißt wie man den Relaunch schneller, besser, toller machen kann?
                    Dann ab ins Forum! Jetzt ist Zeit Änderungen anzugehen. Alles neu!
                </p>

                <p><a class="btn btn-default" href="http://www.icewars-forum.de/">IceWars Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Baue auf</h2>

                <p>Du bist Entwickler? Designer? Allrounder? Hilf mit das Universum wieder aufzubauen.
                    Ist alles Open-Source, in kleinen Iterationen kriegen wir das Schritt für Schritt
                    wieder hin.
                </p>

                <p><a class="btn btn-default" href="https://github.com/iw-reload/">GitHub &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Kolonisiere</h2>

                <p>Du willst einfach nur zocken? Fritten? Fehler finden? Log dich ein, schau dich um,
                    ein bisschen was geht vielleicht schon.
                </p>

                <p>
                    <?= Html::a('Login &raquo;', ['login'], ['class' => 'btn btn-default']) ?>
                </p>
            </div>
        </div>

    </div>
</div>
