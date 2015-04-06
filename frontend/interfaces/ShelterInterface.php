<?php

namespace frontend\interfaces;

/**
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
interface ShelterInterface
{
  /**
   * @return \frontend\objects\Resources
   */
  public function getShelterCapacity();
}
