<?php


namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;

class TodoListController
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
     * @Route("/remove_todo_list", name="remove_todo_list", options = { "expose" = true })
     */
    public function removeList(Request $request)
    {

    }
}