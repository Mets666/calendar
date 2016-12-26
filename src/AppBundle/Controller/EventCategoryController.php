<?php


namespace AppBundle\Controller;


use AppBundle\Entity\EventCategory;
use AppBundle\Form\EventCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventCategoryController extends Controller
{
    /**
     * @Route("/add_category", name="add_category", options = { "expose" = true })
     */
    public function addCategoryAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new EventCategory();
        $category->setUser($user);
        $addCategoryForm = $this->createForm(EventCategoryType::class, $category);

        $addCategoryForm->handleRequest($request);
        if ($addCategoryForm->isSubmitted() && $addCategoryForm->isValid()) {
            try {
                $eventCategoryRepository->add($category);
                $eventCategoryRepository->save();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to create category!'
                );
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/edit_category", name="edit_category", options = { "expose" = true })
     */
    public function editCategoryAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');

        $formData = $request->request->get('event_category');

        $category = $eventCategoryRepository->get($formData['id']);

        $categoryForm = $this->createForm(EventCategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            try {
                $eventCategoryRepository->save();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to edit category!'
                );
            }
            $this->addFlash(
                'success',
                'Category successfully edited!'
            );
            return $this->redirectToRoute('time_log');
        }

        return $this->redirectToRoute('time_log');
    }

    /**
     * @Route("/delete_category/{categoryId}", name="delete_category", options = { "expose" = true })
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