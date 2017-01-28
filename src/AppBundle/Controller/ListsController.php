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

        $todoListRepository = $this->get('app.todo_list.repository');

        /** @var \AppBundle\Entity\User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $todoLists = $user->getTodoLists();

        return $this->render('default/timeLog.html.twig', array(
//            'category_form' => $editCategoryForm->createView(),
            'todo_lists' => $todoLists,

        ));
    }
    
}