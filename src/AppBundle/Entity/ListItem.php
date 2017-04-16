<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="list_item")
 * @ORM\Entity
 */
class ListItem
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
     *     message="You must add some text first."
     * )
     * @Assert\Length(max=200,
     *     maxMessage="Text cannot be longer than {{ limit }} characters."
     * )
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     * */
    private $done;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TodoList", inversedBy="items", fetch="LAZY", cascade={"persist", "remove" } )
     * @ORM\JoinColumn(name="list_id", referencedColumnName="id", nullable=false)
     */
    private $list;

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
     * Set text
     *
     * @param string $text
     *
     * @return ListItem
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set done
     *
     * @param boolean $done
     *
     * @return ListItem
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return boolean
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set list
     *
     * @param \AppBundle\Entity\TodoList $list
     *
     * @return ListItem
     */
    public function setList(\AppBundle\Entity\TodoList $list = null)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * Get list
     *
     * @return \AppBundle\Entity\TodoList
     */
    public function getList()
    {
        return $this->list;
    }
}
