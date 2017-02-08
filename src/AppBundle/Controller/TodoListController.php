<?php


namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends DefaultController
{

    /**
     * @Route("/add_todo_list", name="add_todo_list", options = { "expose" = true })
     */
    public function addList(Request $request)
    {

    }

    /**
     * @Route("/edit_todo_list", name="edit_todo_list", options = { "expose" = true })
     */
    public function editList(Request $request)
    {

    }

    /**
     * @Route("/delete_todo_list/{listId}", name="delete_todo_list", options = { "expose" = true })
     */
    public function deleteList($listId)
    {
        $todoListRepository = $this->get('app.todo_list.repository');

        return $this->redirectToRoute('lists');
    }
}
