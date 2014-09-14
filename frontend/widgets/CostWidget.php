<?php

namespace frontend\widgets;

use yii\base\Widget;
use frontend\objects\Resources;

/**
 * Renders a list of resource costs (for buildings, ships, ...).
 * 
 * @author ben
 */
class CostWidget extends Widget
{
  /**
   *
   * @var Resources
   */
  public $resources = null;
  
  
  public function run()
  {
    return \yii\helpers\Html::ul([
      'iron'      => $this->resources->iron,
      'steel'     => $this->resources->steel,
      'chemicals' => $this->resources->chemicals,
      'vv4a'      => $this->resources->vv4a,
      'population'=> $this->resources->population,
      'ice'       => $this->resources->ice,
      'water'     => $this->resources->water,
      'energy'    => $this->resources->energy,
      'credits'   => $this->resources->credits,
    ], [
      'class' => 'cost',
      'item' => function( $item, $index ) {
        return $item == 0 ? '' : "<li class=\"{$index}\" title=\"{$index}\">{$item}</li>";
      },
    ]);
  }

}
