<?php

namespace common\entities;

/**
 * Description of User
 *
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class User
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var string
   * @Column(type = "string")
   */
  private $name = '';
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="Identity", mappedBy="user")
   */
  private $identities = null;
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="Outpost", mappedBy="owner")
   */
  private $outposts = null;
  
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = (string)$name;
  }

  public function getIdentities() {
    return $this->identities;
  }

  public function addIdentity(Identity $identity, $sync=true)
  {
    $this->identities->add( $identity );
    
    if ($sync) {
      $identity->setUser( $this, false );
    }
  }

  public function getOutposts() {
    return $this->outposts;
  }

  public function addOutpost(Outpost $outpost, $sync=true)
  {
    $this->outposts->add( $outpost );
    
    if ($sync) {
      $outpost->setOwner( $this, false );
    }
  }
}
