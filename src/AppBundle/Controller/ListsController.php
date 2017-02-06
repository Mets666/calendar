<?php


namespace AppBundle\Controller;


use AppBundle\Form\TodoListType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListsController extends DefaultController
{

    /**
     * @Route("/lists/{listId}", name="lists", defaults={"listId" = 0})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listsAction(Request $request, $listId)
    {

        $todoListRepository = $this->get('app.todo_list.repository');

        /** @var \AppBundle\Entity\User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();


        /** @var \AppBundle\Entity\TodoList[] $todoLists */
        $todoLists = $user->getTodoLists();

        $selectedList = null;

        if(!empty($todoLists)){
            if($listId == 0) {
                $selectedList = $todoLists[0];
            }
            else{
                foreach ($todoLists as $todoList){
                    if ($todoList->getId() == $listId){
                        $selectedList = $todoList;
                    }
                }
            }
        }

        $todoListForm = $this->createForm(TodoListType::class, array(), array(
            'action' => $this->generateUrl('add_category')
        ));

        return $this->render('default/lists.html.twig', array(
            'todo_list_form' => $todoListForm->createView(),
            'todo_lists' => $todoLists,
            'selected_list' => $selectedList
        ));
    }
    
}