<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Title must be filled."
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $acronym;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeLimit;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CalendarEvent", mappedBy="project", fetch="LAZY")
     * @ORM\OrderBy({"startDate" = "ASC"})
     */
    private $calendarEvents;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="projects", fetch="LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->calendarEvents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set timeLimit
     *
     * @param integer $timeLimit
     *
     * @return Project
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * Get timeLimit
     *
     * @return integer
     */
    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    /**
     * Add calendarEvent
     *
     * @param \AppBundle\Entity\CalendarEvent $calendarEvent
     *
     * @return Project
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
     * Set user 
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Project
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set acronym
     *
     * @param string $acronym
     *
     * @return Project
     */
    public function setAcronym($acronym)
    {
        $this->acronym = $acronym;

        return $this;
    }

    /**
     * Get acronym
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'acronym' => $this->getAcronym(),
            'description' => $this->getDescription(),
            'timeLimit' => $this->getTimeLimit(),
            'user' => $this->getUser()->getId()
        ];
    }
}
