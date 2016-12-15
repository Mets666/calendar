<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Exception\DatabaseException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventCategoryRepository
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
    public function add($category)
    {
        try {
            $em = $this->doctrine->getManager();
            $em->persist($category);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
    }

    /**
     * @param \AppBundle\Entity\EventCategory $category
     */
    public function remove($category)
    {
        $em = $this->doctrine->getManager();
        $em->remove($category);
    }

    /**
     * @param \AppBundle\Entity\User $user
     * @return \AppBundle\Entity\EventCategory[]
     * @throws DatabaseException
     */
    public function allForUser($user)
    {
        try {
            $categories = $this->doctrine->getRepository('AppBundle:EventCategory')
                ->createQueryBuilder('c')
                ->where('c.user = :userId')
                ->setParameter('userId', $user->getId())
                ->getQuery()->getResult();
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }

        return $categories;
    }

    public function get($id)
    {
        try {
            $category = $this->doctrine->getRepository('AppBundle:EventCategory')->find($id);
        } catch (\Exception $e) {
            throw new DatabaseException('Failed to save data to database!', $e->getCode(), $e);
        }
        return $category;
    }
}