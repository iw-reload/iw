<?php

namespace frontend\interfaces;

use common\models\Base;

/**
 *
 * @author ben
 */
interface BaseFinderInterface
{
  /**
   * @param int $id
   * @return Base
   */
  public function getBaseById( $id );
}
