<?php

namespace frontend\interfaces;

use common\models\User;

/**
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
interface UserFinderInterface
{
  /**
   * @param int $id
   * @return User
   */
  public function getUserById( $id );
}
