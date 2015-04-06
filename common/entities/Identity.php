<?php

namespace common\entities;

/**
 * The identity entity stores information about users, which have been
 * authenticated by an external auth-provider.
 * 
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"authProvider","externalUserId"}),
 *  }
 * )
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class Identity
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
  private $authProvider = '';
  /**
   * @var string
   * @Column(type = "string")
   */
  private $externalUserId = '';
  /**
   * Bidirectional - Many Identities can be linked to one user (OWNING SIDE)
   * 
   * @var User
   * @ManyToOne(targetEntity="User", inversedBy="identities")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $user = null;
  
  public function getId() {
    return $this->id;
  }

  public function getAuthProvider() {
    return $this->authProvider;
  }

  public function setAuthProvider($authProvider) {
    $this->authProvider = (string)$authProvider;
  }

  public function getExternalUserId() {
    return $this->externalUserId;
  }

  public function setExternalUserId($externalUserId) {
    $this->externalUserId = (string)$externalUserId;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser(User $user, $sync=true )
  {
    $this->user = $user;
    
    if ($sync) {
      $user->addIdentity( $this, false );
    }
  }
}
