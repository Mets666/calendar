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
    public function get($id);
    public function save();
    public function add($object);
    public function remove($object);
}