<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CalendarEvent", mappedBy="user", fetch="LAZY")
     */
    private $calendarEvents;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EventCategory", mappedBy="user", fetch="LAZY")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $eventCategories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TodoList", mappedBy="user", fetch="LAZY")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $todoLists;

    public function __construct()
    {
        $this->isActive = true;
        $this->calendarEvents = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
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
     * Set password
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add calendarEvent
     *
     * @param \AppBundle\Entity\CalendarEvent $calendarEvent
     *
     * @return User
     */
    public function addCalendarEvent(\AppBundle\Entity\CalendarEvent $calendarEvent)
    {
        $this->calendarEvents[] = $calendarEvent;

        return $this;
    }

    /**
     * Remove calendarEvent
     *
     * @param \AppBundle\Entity\CalendarEvent $calendarEvent
     */
    public function removeCalendarEvent(\AppBundle\Entity\CalendarEvent $calendarEvent)
    {
        $this->calendarEvents->removeElement($calendarEvent);
    }

    /**
     * Get calendarEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCalendarEvents()
    {
        return $this->calendarEvents;
    }

    /**
     * Add eventCategory
     *
     * @param \AppBundle\Entity\EventCategory $eventCategory
     *
     * @return User
     */
    public function addEventCategory(\AppBundle\Entity\EventCategory $eventCategory)
    {
        $this->eventCategories[] = $eventCategory;

        return $this;
    }

    /**
     * Remove eventCategory
     *
     * @param \AppBundle\Entity\EventCategory $eventCategory
     */
    public function removeEventCategory(\AppBundle\Entity\EventCategory $eventCategory)
    {
        $this->eventCategories->removeElement($eventCategory);
    }

    /**
     * Get eventCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventCategories()
    {
        return $this->eventCategories;
    }

    /**
     * Add toDoList
     *
     * @param \AppBundle\Entity\TodoList $todoList
     *
     * @return User
     */
    public function addTodoList(\AppBundle\Entity\TodoList $todoList)
    {
        $this->todoLists[] = $todoList;

        return $this;
    }

    /**
     * Remove todoList
     *
     * @param \AppBundle\Entity\TodoList $todoList
     */
    public function removeTodoList(\AppBundle\Entity\TodoList $todoList)
    {
        $this->todoLists->removeElement($todoList);
    }

    /**
     * Get toDoLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTodoLists()
    {
        return $this->todoLists;
    }
}
