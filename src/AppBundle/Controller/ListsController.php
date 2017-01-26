<?php


namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListsController extends DefaultController
{

    /**
     * @Route("/lists", name="lists")
     */
    public function listsAction(Request $request)
    {

    }
    
}