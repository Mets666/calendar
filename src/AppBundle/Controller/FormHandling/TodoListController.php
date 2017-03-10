<?php


namespace AppBundle\Controller\FormHandling;


use AppBundle\Entity\TodoList;
use AppBundle\Form\TodoListType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends Controller
{

    /**
     * @Route("/add_todo_list", name="add_todo_list", options = { "expose" = true })
     */
    public function addList(Request $request)
    {
        $todoListRepository = $this->get('app.todo_list.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $list = new TodoList();
        $list->setUser($user);
        $addListForm = $this->createForm(TodoListType::class, $list);
        $addListForm->remove('id');
        $addListForm->remove('description');

        $addListForm->handleRequest($request);
        if ($addListForm->isSubmitted()) {
            if($addListForm->isValid()) {
                try {
                    $todoListRepository->add($list);
                    $todoListRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to create To-Do list!'
                    );
                }
                $this->addFlash(
                    'success',
                    'To-Do list successfully added!'
                );
                return $this->redirectToRoute('lists');
            }
            else {
                $errors = $validator->validate($list);
                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to edit To-Do list: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('lists');
    }

    /**
     * @Route("/edit_todo_list", name="edit_todo_list", options = { "expose" = true })
     */
    public function editList(Request $request)
    {
        $todoListRepository = $this->get('app.todo_list.repository');
        $validator = $this->get('validator');

        $formData = $request->request->get('todo_list');
        try {
            /** @var \AppBundle\Entity\TodoList $list */
            $list = $todoListRepository->get($formData['id']);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to edit To-Do list!'
            );
            return $this->redirectToRoute('lists', array('listId' => $formData['id']));
        }

        $originalItems = new ArrayCollection();
        foreach ($list->getItems() as $item) {
            $originalItems->add($item);
        }

        $editListForm = $this->createForm(TodoListType::class, $list);

        $editListForm->handleRequest($request);
        if ($editListForm->isSubmitted()) {
            if($editListForm->isValid()){
                try {
                    foreach ($list->getItems() as $item){
                        if($item->getList() === null){
                            $item->setList($list);
                        }
                    }
    
                    foreach ($originalItems as $item) {
                        if (false === $list->getItems()->contains($item)) {
                            $todoListRepository->remove($item);
                        }
                    }
                    $todoListRepository->add($list);
                    $todoListRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to edit To-Do list!'
                    );
                    return $this->redirectToRoute('lists', array('listId' => $formData['id']));
                }
                $this->addFlash(
                    'success',
                    'To-Do list successfully edited!'
                );
            }
            else {
                $errors = $validator->validate($list);
                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to edit To-Do list: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('lists', array('listId' => $formData['id']));
    }

    /**
     * @Route("/delete_todo_list/{listId}", name="delete_todo_list", options = { "expose" = true })
     */
    public function deleteList($listId)
    {
        /** @var \AppBundle\Repository\TodoListRepository $todoListRepository */
        $todoListRepository = $this->get('app.todo_list.repository');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        try {
            /** @var \AppBundle\Entity\TodoList $list */
            $list = $todoListRepository->get($listId);
            if($list->getUser() === $user) {
                $todoListRepository->remove($list);
                $todoListRepository->save();
            }
            else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to delete To-Do list!'
            );
            return $this->redirectToRoute('lists');
        }
        $this->addFlash(
            'success',
            'To-Do list successfully deleted!'
        );
        
        return $this->redirectToRoute('lists');
    }
}