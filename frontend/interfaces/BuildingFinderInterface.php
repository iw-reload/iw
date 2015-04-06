<?php

namespace frontend\interfaces;

use frontend\models\Building;

/**
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
interface BuildingFinderInterface
{
  /**
   * @param int $id
   * @return Building
   */
  public function getById( $id );
  /**
   * @param string $group
   * @return Building[] indexed by id
   */
  public function getByGroup( $group );
}
