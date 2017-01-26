<?php


namespace AppBundle\Repository\Exception;


use Doctrine\Common\Persistence\ManagerRegistry;

class ToDoListRepository
{

    /** @var ManagerRegistry $doctrine */
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}