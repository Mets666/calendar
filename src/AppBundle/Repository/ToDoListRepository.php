<?php


namespace AppBundle\Repository;


use AppBundle\Repository\BasicRepository;
use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Persistence\ManagerRegistry;

class TodoListRepository extends BasicRepository
{

    /**
     * TodoListRepository constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct($doctrine);
    }


    /**
     * @param $id
     * @return object
     * @throws DatabaseException
     */
    public function get($id)
    {
        try {
            $list = $this->doctrine->getRepository('AppBundle:TodoList')->find($id);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to get data from database!');
        }
        return $list;
    }
    
}