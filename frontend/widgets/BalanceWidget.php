<?php

namespace frontend\widgets;

use yii\base\Widget;
use frontend\objects\Resources;

/**
 * Renders a list of running expenses and benefits (for buildings, ships, ...).
 * 
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class BalanceWidget extends Widget
{
  /**
   * @var array
   */
  public $effects = [];
  /**
   * @var Resources
   */
  public $resources = null;
  
  
  public function run()
  {
    return \yii\helpers\Html::ul( array_merge($this->effects, [
      'iron'      => $this->resources->iron,
      'steel'     => $this->resources->steel,
      'chemicals' => $this->resources->chemicals,
      'vv4a'      => $this->resources->vv4a,
      'population'=> $this->resources->population,
      'ice'       => $this->resources->ice,
      'water'     => $this->resources->water,
      'energy'    => $this->resources->energy,
      'credits'   => $this->resources->credits,
    ]), [
      'class' => 'balance',
      'item' => function( $item, $index ) {
        $item = intval($item);
        if ($item === 0) {
          return '';
        } elseif ($item > 0) {
          return "<li>+{$item} {$index}</li>";
        } else {
          return "<li>{$item} {$index}</li>";
        }        
      },
    ]);
  }

}
