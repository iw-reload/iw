<?php

namespace common\objects;

/**
 * Defines role name constants for use with auth manager.
 *
 * @author ben
 */
class RbacRole
{
  const ADMIN = 'ADMIN';
  
  const BUILDING_LIST   = 'BUILDING_LIST';
  const BUILDING_VIEW   = 'BUILDING_VIEW';
  const BUILDING_CREATE = 'BUILDING_CREATE';
  const BUILDING_UPDATE = 'BUILDING_UPDATE';
  const BUILDING_DELETE = 'BUILDING_DELETE';
  
  const CELESTIAL_BODY_LIST   = 'CELESTIAL_BODY_LIST';
  const CELESTIAL_BODY_VIEW   = 'CELESTIAL_BODY_VIEW';
  const CELESTIAL_BODY_CREATE = 'CELESTIAL_BODY_CREATE';
  const CELESTIAL_BODY_UPDATE = 'CELESTIAL_BODY_UPDATE';
  const CELESTIAL_BODY_DELETE = 'CELESTIAL_BODY_DELETE';
  
  const USER_LIST   = 'USER_LIST';
  const USER_VIEW   = 'USER_VIEW';
  const USER_CREATE = 'USER_CREATE';
  const USER_UPDATE = 'USER_UPDATE';
  const USER_DELETE = 'USER_DELETE';

}
