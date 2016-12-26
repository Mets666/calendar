<?php


namespace AppBundle\Controller;


use AppBundle\Entity\EventCategory;
use AppBundle\Form\EventCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TimeLogController extends Controller
{
    /**
     * @Route("/time_log", name="time_log")
     */
    public function timeLogAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');
        $calendarEventRepository = $this->get('app.calendar_event.repository');



        $user = $this->get('security.token_storage')->getToken()->getUser();

        $spendTime = $calendarEventRepository->getSpendTimeByCategoriesForUser($user->getId());

//        dump($spendTime); die;

        $categories = $eventCategoryRepository->allForUser($user);

        $editCategoryForm = $this->createForm(EventCategoryType::class, array(), array(
            'action' => $this->generateUrl('edit_category')
        ));

        return $this->render('default/timeLog.html.twig', array(
            'category_form' => $editCategoryForm->createView(),
            'categories' => $categories,
            'spend_time' => $spendTime,
        ));
    }
}