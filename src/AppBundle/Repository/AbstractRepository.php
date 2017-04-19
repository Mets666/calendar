<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 28.1.2017
 * Time: 13:13
 */

namespace AppBundle\Repository;


use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class AbstractRepository implements RepositoryInterface
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
     * @param Object $object
     * @throws DatabaseException
     */
    public function add($object)
    {
        try {
            $em = $this->doctrine->getManager();
            $em->persist($object);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
    }

    /**
     * @param Object $object
     */
    public function remove($object)
    {
        $em = $this->doctrine->getManager();
        $em->remove($object);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    abstract public function get($id);

}