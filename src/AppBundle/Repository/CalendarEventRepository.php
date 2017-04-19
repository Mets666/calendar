<?php

namespace AppBundle\Repository;


use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Persistence\ManagerRegistry;

class CalendarEventRepository extends AbstractRepository
{

    /**
     * CalendarEventRepository constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct($doctrine);
    }

    /**
     * @param integer $id
     * @return object
     * @throws DatabaseException
     */
    public function get($id)
    {
        try {
            $event = $this->doctrine->getRepository('AppBundle:CalendarEvent')->find($id);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to get data from database!');
        }
        return $event;
    }
}