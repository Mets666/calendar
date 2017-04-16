<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="calendar_event")
 * @ORM\Entity
 */
class CalendarEvent implements \JsonSerializable
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
     * @Assert\Length(max=100,
     *     maxMessage="Title cannot be longer than {{ limit }} characters."
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\Length(max=1000,
     *     maxMessage="Note cannot be longer than {{ limit }} characters."
     * )
     */
    private $note;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="calendarEvents", fetch="LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventCategory", inversedBy="calendarEvents", fetch="LAZY")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="calendarEvents", fetch="LAZY")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    private $project;

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
     * @return CalendarEvent
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return CalendarEvent
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return CalendarEvent
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return CalendarEvent
     */
    public function setUser(\AppBundle\Entity\Uskiser $user = null)
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
     * Set category
     *
     * @param \AppBundle\Entity\EventCategory $category
     *
     * @return CalendarEvent
     */
    public function setCategory(\AppBundle\Entity\EventCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\EventCategory
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * @Assert\IsTrue(message = "Start date must be sooner then end date.")
     */
    public function isDatesValid()
    {
        return ($this->startDate < $this->endDate);
    }


    /**
     * Set note
     *
     * @param string $note
     *
     * @return CalendarEvent
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return CalendarEvent
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getMainTitle()
    {
        if($this->project){
            return '['.$this->getProject()->getAcronym().'] '.$this->getTitle();
        }
        else{
            return $this->getTitle();
        }
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
            'note' => $this->getNote(),
            'start' => $this->getStartDate()->format('c'),
            'end'   => $this->getEndDate()->format('c'),
            'project' => $this->getProject()->jsonSerialize(),
            'category' => $this->getCategory()->jsonSerialize(),
            'user' => $this->getUser()->getId(),
            'mainTitle' => $this->getMainTitle(),
        ];
    }
}
