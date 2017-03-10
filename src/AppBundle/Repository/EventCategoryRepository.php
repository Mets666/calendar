<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;

class EventCategoryRepository extends BasicRepository implements RepositoryInterface
{

    /**
     * EventCategoryRepository constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct($doctrine);
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\EventCategory $category
     * @throws DatabaseException
     */
    public function get($id)
    {
        try {
            $category = $this->doctrine->getRepository('AppBundle:EventCategory')->find($id);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
        return $category;
    }

    /**
     * @param $userId
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getSpendTimeByCategoriesForUser($userId, $startDate = null, $endDate = null)
    {
        if($startDate && $endDate){
            return $this->doctrine->getRepository('AppBundle:CalendarEvent')
                ->createQueryBuilder('events')
                ->select(array('SUM(TIME_DIFF(events.endDate, events.startDate)) as time' , 'IDENTITY(events.category) as id', 'category.title as title', 'category.color as color'))
                ->leftjoin('events.category', 'category')
                ->where('events.startDate BETWEEN :startDate and :endDate')
                ->andWhere('events.user = :userId')
                ->groupBy('category')
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                ->setParameter('userId', $userId)
                ->getQuery()->getResult();
        }
        else{
            return $this->doctrine->getRepository('AppBundle:CalendarEvent')
                ->createQueryBuilder('events')
                ->select(array('SUM(TIME_DIFF(events.endDate, events.startDate)) as time' , 'IDENTITY(events.category) as id', 'category.title as title', 'category.color as color'))
                ->leftjoin('events.category', 'category')
                ->where('events.user = :userId')
                ->groupBy('category')
                ->setParameter('userId', $userId)
                ->getQuery()->getResult();
        }
    }
}