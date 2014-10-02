<?php

namespace frontend\widgets\planetInformation;

use yii\base\Widget;

/**
 * Renders the planet information overview for a given base.
 *
 * @author ben
 */
class PlanetInformationWidget extends Widget
{
  /**
   * @var \common\models\Base
   */
  public $base = null;
  
  public function run()
  {
    return $this->render('planetInformationWidget',[
      'base' => $this->base,
    ]);
  }
}
