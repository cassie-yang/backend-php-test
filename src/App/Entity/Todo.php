<?php

namespace App\Entity;

use JsonSerializable;

/**
 * Class Todo
 * @Table(name="todos")
 * @Entity()
 * @package App\Entity
 */
class Todo implements JsonSerializable
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="todos")
     */
    private $user;

    /**
     * @Column(type="string", length=64)
     */
    private $description;

    /**
     * @Column(type="datetime")
     */
    private $completed_at;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Todo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set user.
     *
     * @param \App\Entity\User|null $user
     *
     * @return Todo
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function jsonSerialize()
    {
        return array(
            'id'   => $this->id,
            'user_id' => $this->user->getId(),
            'description' => $this->description
        );
    }

    /**
     * Set completedAt.
     *
     * @param \DateTime $completedAt
     *
     * @return Todo
     */
    public function setCompletedAt($completedAt)
    {
        $this->completed_at = $completedAt;

        return $this;
    }

    /**
     * Get completedAt.
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completed_at;
    }
}
