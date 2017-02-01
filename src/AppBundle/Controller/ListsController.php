<?php


namespace AppBundle\Controller;


use AppBundle\Form\TodoListType;
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

        $todoListForm = $this->createForm(TodoListType::class, array(), array(
            'action' => $this->generateUrl('add_category')
        ));

        return $this->render('default/lists.html.twig', array(
            'todo_list_form' => $todoListForm->createView(),
            'todo_lists' => $todoLists,

        ));
    }
    
}