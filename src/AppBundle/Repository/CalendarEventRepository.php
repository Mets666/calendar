<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Persistence\ManagerRegistry;

class CalendarEventRepository
{

    /** @var ManagerRegistry $doctrine */
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function save($object)
    {
        $em = $this->doctrine->getManager();
        $em->persist($object);
        $em->flush();
    }

    /**
     * @param \AppBundle\Entity\EventCategory $category
     * @throws DatabaseException
     */
    public function add($event)
    {
        try {
            $this->save($event);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
    }
}