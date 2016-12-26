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

    /**
     * Execute queries and save changes to database
     */
    public function save()
    {
        $em = $this->doctrine->getManager();
        $em->flush();
    }

    /**
     * @param \AppBundle\Entity\EventCategory $category
     * @throws DatabaseException
     */
    public function add($event)
    {
        try {
            $em = $this->doctrine->getManager();
            $em->persist($event);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
    }

    public function remove($event)
    {
        $em = $this->doctrine->getManager();
        $em->remove($event);
    }

    public function get($id)
    {
        try {
            $event = $this->doctrine->getRepository('AppBundle:CalendarEvent')->find($id);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to get data from database!');
        }
        return $event;
    }

    public function getSpendTimeByCategoriesForUser($userId)
    {
        return $this->doctrine->getRepository('AppBundle:CalendarEvent')
            ->createQueryBuilder('events')
            ->select(array('SUM(TIME_DIFF(events.endDate, events.startDate)) as time' , 'IDENTITY(events.category) as categoryId', 'category.title as categoryTitle'))
            ->leftjoin('events.category', 'category')
            ->where('events.user = :userId')
            ->groupBy('events.category')
            ->setParameter('userId', $userId)
            ->getQuery()->getResult();
    }
}