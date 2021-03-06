<?php

namespace common\objects;

use common\entities\CelestialBody as CelestialBodyEntity;
use common\entityRepositories\CelestialBodySpecialty as CelestialBodySpecialtyRepository;
use common\models\celestialBodies\Asteroid as AsteroidModel;
use common\models\celestialBodies\ElectricityStorm as ElectricityStormModel;
use common\models\celestialBodies\GasGiant as GasGiantModel;
use common\models\celestialBodies\GravimetricAnomaly as GravimetricAnomalyModel;
use common\models\celestialBodies\IceGiant as IceGiantModel;
use common\models\celestialBodies\IonStorm as IonStormModel;
use common\models\celestialBodies\SpatialDistortion as SpatialDistortionModel;
use common\models\celestialBodies\TerrestrialPlanet as TerrestrialPlanetModel;
use common\models\celestialBodies\Void as VoidModel;

/**
 * Description of CelestialBodyFactory
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class CelestialBodyFactory
{
  static public function create
    ( CelestialBodyEntity $celestialBodyEntity
    , CelestialBodySpecialtyRepository $celestialBodySpecialtyRepository
    )
  {
    $result = null;
    
    switch ($celestialBodyEntity->getType())
    {
    case CelestialBodyEntity::DISCR_ASTEROID:
        $result = new AsteroidModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_ELECTRICITY_STORM:
        $result = new ElectricityStormModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_GAS_GIANT:
        $result = new GasGiantModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_GRAVIMETRIC_ANOMALY:
        $result = new GravimetricAnomalyModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_ICE_GIANT:
        $result = new IceGiantModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_ION_STORM:
        $result = new IonStormModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_SPATIAL_DISTORTION:
        $result = new SpatialDistortionModel( $celestialBodyEntity );
        break;
    case CelestialBodyEntity::DISCR_TERRESTRIAL_PLANET:
        $result = new TerrestrialPlanetModel( $celestialBodyEntity, $celestialBodySpecialtyRepository );
        break;
    case CelestialBodyEntity::DISCR_VOID:
        $result = new VoidModel( $celestialBodyEntity );
        break;
    }
    
    return $result;
  }
}
