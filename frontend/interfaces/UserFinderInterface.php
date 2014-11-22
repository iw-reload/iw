<?php

namespace frontend\interfaces;

use common\models\User;

/**
 *
 * @author ben
 */
interface UserFinderInterface
{
  /**
   * @param int $id
   * @return User
   */
  public function getUserById( $id );
}
