<?php

namespace frontend\interfaces;

use common\models\Base;

/**
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
interface BaseFinderInterface
{
  /**
   * @param int $id
   * @return Base
   */
  public function getBaseById( $id );
}
