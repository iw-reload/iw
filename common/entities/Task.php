<?php

namespace common\entities;

/**
 * A task represents something that is in progress and will finish at a certain
 * point in time.
 * 
 * Logically, a task will finish some time in the future, but in the DB, there
 * can be tasks that already have been finished, but have not yet been
 * processed.
 *
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class Task
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var DateTime
   * @Column(type="datetime")
   */
  private $finished = null;
  /**
   * Bidirectional - Many Tasks can be startet by one user (OWNING SIDE)
   * 
   * @var User
   * @ManyToOne(targetEntity="User", inversedBy="initiatedTasks")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $initiator = null;
  
  public function getId() {
    return $this->id;
  }

  public function getInitiator() {
    return $this->initiator;
  }

  public function setInitiator(User $initiator, $sync=true )
  {
    $this->initiator = $initiator;
    
    if ($sync) {
      $initiator->addInitiatedTask( $this );
    }
  }

  public function getFinished() {
    return $this->finished;
  }

  /**
   * We expect the application has set the default timezone to UTC, so all
   * DateTime instances should be stored and loaded in UTC.
   * 
   * @param DateTime $finished
   */
  public function setFinished(DateTime $finished) {
    $this->finished = $finished;
  }
}
