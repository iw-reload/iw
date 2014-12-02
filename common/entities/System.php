<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class System
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var int
   * @Column(type = "smallint", options={"unsigned":true})
   */
  private $number = 0;
  /**
   * @var SystemWideModifier
   * @ManyToOne(targetEntity="SystemWideModifier")
   * @JoinColumn(onDelete="SET NULL")
   */
  private $modifier = null;
  
  public function getId() {
    return $this->id;
  }
  
  public function getNumber() {
    return $this->number;
  }

  public function setNumber($number) {
    $this->number = (int)$number;
  }

  public function getModifier() {
    return $this->modifier;
  }
}
