<?php


namespace AppBundle\Controller\FormHandling;


use AppBundle\Controller\DefaultController;
use AppBundle\Entity\EventCategory;
use AppBundle\Form\EventCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventCategoryController extends DefaultController
{
    /**
     * @Route("/add_category", name="add_category", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addCategoryAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new EventCategory();
        $category->setUser($user);
        $addCategoryForm = $this->createForm(EventCategoryType::class, $category);

        $addCategoryForm->handleRequest($request);
        if ($addCategoryForm->isSubmitted()) {
            if($addCategoryForm->isValid()) {
                try {
                    $eventCategoryRepository->add($category);
                    $eventCategoryRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to create category!'
                    );
                }
                return $this->redirectToRoute('time_log');
            }
            else {
                $errors = $validator->validate($category);

                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to add category: ' . $error->getMessage()
                    );
                }
            }
        }
        return $this->redirectToRoute('time_log');
    }

    /**
     * @Route("/edit_category", name="edit_category", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editCategoryAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $formData = $request->request->get('event_category');

        try {
            $category = $eventCategoryRepository->get($formData['id']);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to edit category!'
            );
            return $this->redirectToRoute('time_log');
        }

        $editCategoryForm = $this->createForm(EventCategoryType::class, $category);

        $editCategoryForm->handleRequest($request);
        if ($editCategoryForm->isSubmitted()) {
            if($editCategoryForm->isValid()){
                try {
                    $eventCategoryRepository->add($category);
                    $eventCategoryRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to edit category!'
                    );
                    return $this->redirectToRoute('time_log');
                }
                $this->addFlash(
                    'success',
                    'Category successfully edited!'
                );
            }
            else{
                $errors = $validator->validate($category);
                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to edit category: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('time_log');
    }

    /**
     * @Route("/delete_category/{categoryId}", name="delete_category", options = { "expose" = true })
     * @param integer $categoryId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCategoryAction($categoryId)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        try {
            $category = $eventCategoryRepository->get($categoryId);
            if($category->getUser() === $user) {
                $eventCategoryRepository->remove($category);
                $eventCategoryRepository->save();
            }
            else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to delete category!'
            );
            return $this->redirectToRoute('time_log');
        }
        $this->addFlash(
            'success',
            'Category successfully deleted!'
        );
        return $this->redirectToRoute('time_log');
    }
}