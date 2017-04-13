<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 5.3.2017
 * Time: 21:18
 */

namespace AppBundle\Repository;


interface RepositoryInterface
{
    /**
     * @param integer $id
     * @return object
     */
    public function get($id);

    /**
     * @return void
     */
    public function save();

    /**
     * @param $object
     * @return void
     */
    public function add($object);

    /**
     * @param $object
     * @return void
     */
    public function remove($object);
}