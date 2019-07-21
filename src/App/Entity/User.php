<?php

namespace App\Entity;

use JsonSerializable;

/**
 * Class User
 * @Table(name="users")
 * @Entity()
 * @package App\Entity
 */
class User implements JsonSerializable
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=64, unique=true)
     */
    private $username;

    /**
     * @Column(type="string", length=64)
     */
    private $password;

    /**
     * @OneToMany(targetEntity="Todo", mappedBy="user")
     */
    private $todos;

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
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->todos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add todo.
     *
     * @param \App\Entity\Todo $todo
     *
     * @return User
     */
    public function addTodo(\App\Entity\Todo $todo)
    {
        $this->todos[] = $todo;

        return $this;
    }

    /**
     * Remove todo.
     *
     * @param \App\Entity\Todo $todo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTodo(\App\Entity\Todo $todo)
    {
        return $this->todos->removeElement($todo);
    }

    /**
     * Get todos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTodos()
    {
        return $this->todos;
    }

    public function jsonSerialize()
    {
        return array(
            'id'   => $this->id,
            'username' => $this->username,
        );
    }
}
