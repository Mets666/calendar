<?php


namespace AppBundle\Controller;


use AppBundle\Entity\TodoList;
use AppBundle\Form\TodoListType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListsController extends DefaultController
{

    /**
     * @Route("/lists/{listId}", name="lists", defaults={"listId" = 0})
     * @param integer $listId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listsAction($listId)
    {
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

        $addListForm = $this->createForm(TodoListType::class, new TodoList(), array(
            'action' => $this->generateUrl('add_todo_list')
        ));
        $addListForm->remove('id');
        $addListForm->remove('description');
        $addListForm->remove('items');

        $editListForm = $this->createForm(TodoListType::class, $selectedList, array(
            'action' => $this->generateUrl('edit_todo_list')
        ));

        return $this->render('default/lists.html.twig', array(
            'add_todo_list_form' => $addListForm->createView(),
            'edit_todo_list_form' => $editListForm->createView(),
            'todo_lists' => $todoLists,
            'selected_list' => $selectedList
        ));
    }
    
}