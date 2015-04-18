<?php

namespace common\validators\doctrine;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ExistValidator
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ExistValidator extends \yii\validators\Validator
{
  /**
   * @var EntityRepository
   */
  private $repository = null;
  /**
   * @var string the name of the Entity attribute that should be used to
   * validate the existence of the current attribute value. If not set, it will
   * use the name of the attribute currently being validated.
   */  
  public $targetAttribute;
  
  public function getRepository() {
    return $this->repository;
  }

  public function setRepository(EntityRepository $repository) {
    $this->repository = $repository;
  }
    
  /**
   * @inheritdoc
   */
  public function init()
  {
    parent::init();
    
    if ($this->message === null) {
      $this->message = \Yii::t('yii', '{attribute} is invalid.');
    }
  }
  
  /**
   * @inheritdoc
   */
  public function validateAttribute( $model, $attribute )
  {
    $targetAttribute = $this->targetAttribute === null ? $attribute : $this->targetAttribute;

    $findResult = $this->repository->findOneBy([
      $targetAttribute => $model->$attribute,
    ]);

    if ($findResult === null) {
      $this->addError( $model, $attribute, $this->message );
    }
  }  
}
